<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Period;
use App\Models\ResourceArea;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ConsolidateGradesByArea extends Controller
{
    private $Y;
    private $G;
    private $ST;
    private $SY;

    public $path;


    public function make(Group $group)
    {
        /* Dirección para guardar los reportes generados */
        $pathUuid = Str::uuid();
        $pathReport = "app/reports/". $pathUuid ."/";

        if (!File::isDirectory(public_path($pathReport))) {
            File::makeDirectory(public_path($pathReport), 0755, true, true);
        }

        $this->path = $pathReport;


        $this->Y = SchoolYearController::current_year();
        $this->G = $group;
        $this->SY = $group->studyYear;
        $this->ST = $group->studyTime;

        $students = Student::singleData()->whereHas('groupYear', fn ($gr) => $gr->where('group_id', $group->id))->get();
        $studentsIDS = $students->pluck('id')->toArray();

        $areas = ResourceArea::query()
            ->when($group->specialty, function ($when) use ($group) {
                $when->where('id', $group->specialty_area_id);
            }, function ($when) {
                $when->whereNull('specialty');
            })
            ->withWhereHas(
                'subjects',
                function ($query) use ($studentsIDS) {
                    $query->where('school_year_id', $this->Y->id)->with('resourceSubject')
                        ->withWhereHas(
                            'academicWorkload',
                            function ($query) {
                                $query->where('school_year_id', $this->Y->id)->where('study_year_id', $this->SY->id)->select('id', 'course_load', 'subject_id');
                            }
                        )->with([
                            'teacherSubject' => function ($query) use ($studentsIDS) {
                                $query->where('school_year_id', $this->Y->id)->where('group_id', $this->G->id)->with([
                                    'grades' => function ($query) use ($studentsIDS) {
                                        $query->whereIn('student_id', $studentsIDS);
                                    }
                                ]);
                            }
                        ]);
                }
            )
            ->orderBy('last')
            ->orderBy('name')->get()
            ->map(function ($areaMap) {
                return [
                    'id' => $areaMap->id,
                    'name' => $areaMap->name,
                    'subjects' => $areaMap->subjects->map(function ($subjectMap) {
                        $subjectGrade = $subjectMap?->teacherSubject?->grades;
                        return [
                            'id' => $subjectMap->id,
                            'resource_name' => $subjectMap->resourceSubject->name,
                            'academic_workload' => $subjectMap->academicWorkload->course_load,
                            'academic_wordload_porcentage' => (float)($subjectMap->academicWorkload->course_load / 100),
                            'teacher_subject_group' => $subjectMap?->teacherSubject?->id,
                            'gradesByStudent' => $subjectGrade ? $subjectGrade->map(function ($gradeMap) {
                                return [
                                    'id' => $gradeMap->id,
                                    'period_id' => $gradeMap->period_id,
                                    'student_id' => $gradeMap->student_id,
                                    'final' => $gradeMap->final ?: $this->ST->minimum_grade
                                ];
                            }) : []
                        ];
                    })
                ];
            });


        return $this->generatePDF($areas, $students);
    }

    private function generatePDF($areas, $students)
    {
        $periods = Period::where('school_year_id', $this->Y->id)->where('study_time_id', $this->ST->id)->orderBy('ordering')->get()
            ->map(function ($periodMap) {
                return [
                    'id' => $periodMap->id,
                    'ordering' => $periodMap->ordering,
                    'start' => $periodMap->start,
                    'end' => $periodMap->end,
                    'workload' => $periodMap->workload . '%',
                    'workload_porcentage' => (float)$periodMap->workload / 100
                ];
            });

        foreach ($areas as $area) {
            $pdf = Pdf::loadView('logro.pdf.consolidate-grades-by-area', [
                'SCHOOL' => SchoolController::myschool()->getData(),
                'group' => $this->G,
                'ST' => $this->ST,
                'periods' => $periods,
                'area' => $area,
                'students' => $students,
                'GradeController' => GradeController::class
            ]);

            $pdf->setPaper([0.0, 0.0, 612, 1008], count($area['subjects']) < 3 ? 'portrait' : 'landscape');

            $pdf->setOption('dpi', 72);
            $pdf->save($this->path . "Consolidado " . $area['name'] . " - Grupo " . $this->G->name . ".pdf");
        }

        /* Generate Zip and Download */
        return (new ZipController($this->path))->downloadConsolidateGradesByArea($this->G->name);
    }
}
