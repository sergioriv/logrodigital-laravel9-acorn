<?php

namespace App\Http\Controllers;

use App\Exports\ConsolidateGeneralGradesGroup;
use App\Http\Controllers\support\Notify;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Period;
use App\Models\ResourceArea;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ConsolidateGradesByArea extends Controller
{
    private $Y;
    private $G;
    private $ST;
    private $SY;

    public $path;
    private $period;


    public function make(Request $request, Group $group)
    {
        $request->validate([
            'periodConsolidateGrades' => ['required', Rule::exists('periods', 'id')]
        ]);
        $this->period = Period::find($request->periodConsolidateGrades);

        if ($group->specialty) {
            Notify::fail(__('Not allowed'));
            return back();
        }


        /* Dirección para guardar los reportes generados */
        $pathUuid = Str::uuid();
        $pathReport = "reports/". $pathUuid ."/";

        if (!File::isDirectory(public_path($pathReport))) {
            File::makeDirectory(public_path($pathReport), 0755, true, true);
        }

        $this->path = $pathReport;


        $this->Y = SchoolYearController::current_year();
        $this->G = $group;
        $this->SY = $group->studyYear;
        $this->ST = $group->studyTime;

        $groupsSpecialty = Group::where('school_year_id', $this->Y->id)
            ->where('specialty', TRUE)
            ->where('school_year_id', $group->school_year_id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->get();
        $groupsIDS = [$group->id, ...$groupsSpecialty->pluck('id')->toArray()];


        $students = Student::singleData()->whereHas('groupYear', fn ($gr) => $gr->where('group_id', $group->id))->get();
        $studentsIDS = $students->pluck('id')->toArray();

        $students->map(function ($studentMap) use ($group) {
                $groupSpecialty = Group::where('school_year_id', $this->Y->id)
                    ->where('specialty', TRUE)
                    ->where('school_year_id', $group->school_year_id)
                    ->where('headquarters_id', $group->headquarters_id)
                    ->where('study_time_id', $group->study_time_id)
                    ->where('study_year_id', $group->study_year_id)
                    ->whereHas(
                        'groupStudents',
                        fn ($query) => $query->where('student_id', $studentMap->id)
                    )->where('specialty', TRUE)
                    ->first();

                return $studentMap->setAttribute('specialty', $groupSpecialty->specialty_area_id ?? null);
            });


        $areas = ResourceArea::query()
            ->withWhereHas(
                'subjects',
                function ($query) use ($studentsIDS, $groupsIDS) {
                    $query->where('school_year_id', $this->Y->id)->with('resourceSubject')
                        ->withWhereHas(
                            'academicWorkload',
                            function ($query) {
                                $query->where('school_year_id', $this->Y->id)->where('study_year_id', $this->SY->id)->select('id', 'course_load', 'subject_id');
                            }
                        )->with([
                            'teacherSubject' => function ($query) use ($studentsIDS, $groupsIDS) {
                                $query->where('school_year_id', $this->Y->id)->whereIn('group_id', $groupsIDS)
                                ->with([
                                    'grades' => function ($query) use ($studentsIDS) {
                                        $query->whereIn('student_id', $studentsIDS)->where('period_id', $this->period->id);
                                    }
                                ]);
                            }
                        ]);
                }
            )
            ->orderBy('last')
            ->orderBy('specialty')
            ->orderBy('name')->get()
            ->map(function ($areaMap) {
                return [
                    'id' => $areaMap->id,
                    'name' => $areaMap->name,
                    'isSpecialty' => $areaMap->specialty,
                    'subjects' => $areaMap->subjects->map(function ($subjectMap) {
                        $subjectGrade = $subjectMap?->teacherSubject?->grades;
                        $academic_porcentage = (float)($subjectMap->academicWorkload->course_load / 100);
                        return [
                            'id' => $subjectMap->id,
                            'resource_name' => $subjectMap->resourceSubject->public_name,
                            'academic_workload' => $subjectMap->academicWorkload->course_load,
                            'academic_wordload_porcentage' => $academic_porcentage,
                            'teacher_subject_group' => $subjectMap?->teacherSubject?->id,
                            'gradesByStudent' => $subjectGrade ? $subjectGrade->map(function ($gradeMap) use ($academic_porcentage) {
                                $gradeFinal = $gradeMap->final ?: $this->ST->minimum_grade;
                                return [
                                    'id' => $gradeMap->id,
                                    'period_id' => $gradeMap->period_id,
                                    'student_id' => $gradeMap->student_id,
                                    'final' => $gradeFinal,
                                    'final_workload' => $gradeFinal * $academic_porcentage
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
        /* Informe general */
        // return Excel::download(new ConsolidateGeneralGradesGroup($this->G, $this->ST, $this->period, $areas, $students), 'Consolidado general - Group ' . $this->G->name . '.xlsx');
        Excel::store(new ConsolidateGeneralGradesGroup($this->G, $this->ST, $this->period, $areas, $students), $this->path .'Consolidado general - Group ' . $this->G->name . '.xlsx', 'public');

        /* Informe por area */
        foreach ($areas as $area) {
            $pdf = Pdf::loadView('logro.pdf.consolidate-grades-by-area', [
                'SCHOOL' => SchoolController::myschool()->getData(),
                'group' => $this->G,
                'ST' => $this->ST,
                'period' => $this->period,
                'area' => $area,
                'students' => $students,
                'GradeController' => GradeController::class,
                'areaLosses' => 0
            ]);

            $pdf->setPaper([0.0, 0.0, 612, 1008], count($area['subjects']) < 5 ? 'portrait' : 'landscape');

            $pdf->setOption('dpi', 72);
            $pdf->save('app/'.$this->path . "Consolidado " . $area['name'] . " - Grupo " . $this->G->name . ".pdf");
        }

        /* Generate Zip and Download */
        return (new ZipController('app/'.$this->path))->downloadConsolidateGradesByArea($this->G->name);
    }
}
