<?php

namespace App\Http\Controllers;

use App\Exports\ConsolidateGeneralGradesGroup;
use App\Exports\ConsolidateGeneralGradesStudyYear;
use App\Http\Controllers\support\Notify;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Period;
use App\Models\ResourceArea;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
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
    private $schoolYear;
    private $ST;
    private $SY;

    public $path;
    private $period;


    public function make(Request $request, Group $group)
    {
        if ($request->has('periodConsolidateGrades') && $request->periodConsolidateGrades !== 'FINAL') {
            $request->validate([
                'periodConsolidateGrades' => ['required', Rule::exists('periods', 'id')]
            ]);
            $this->period = Period::find($request->periodConsolidateGrades);
        } else {
            $this->period = 'FINAL';
        }

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
        $this->schoolYear = $group->schoolYear;
        $this->SY = $group->studyYear;
        $this->ST = $group->studyTime;

        $students = Student::singleData()->whereHas('groupYear', fn ($gr) => $gr->where('group_id', $group->id))->get();
        $studentsIDS = $students->pluck('id')->toArray();

        $groupsSpecialty = Group::where('school_year_id', $this->Y->id)
            ->where('specialty', TRUE)
            ->where('school_year_id', $group->school_year_id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->whereHas(
                'groupStudents',
                function ($query) use ($studentsIDS){
                    $query->whereIn('student_id', $studentsIDS);
                }
            )
            ->get();
        $groupsIDS = [$group->id, ...$groupsSpecialty->pluck('id')->toArray()];

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

        $areasInitial = ResourceArea::query()
            ->select('id')
            ->whereNull('specialty')
            ->whereHas(
                'subjects',
                function ($query) {
                    $query->where('school_year_id', $this->Y->id)
                        ->whereHas(
                            'academicWorkload',
                            function ($query) {
                                $query->where('school_year_id', $this->Y->id)->where('study_year_id', $this->SY->id);
                            }
                        );
                }
            )
            ->get()->pluck('id')->toArray();
        $areasIDS = array_merge($areasInitial, $groupsSpecialty->pluck('specialty_area_id')->toArray());

        $periodIDS = \App\Models\Period::query()
            ->when($this->period !== 'FINAL', function ($whenNoFinal) {
                $whenNoFinal->where('ordering', '<=', $this->period->ordering);
            })
            ->where('school_year_id', $this->Y->id)
            ->where('study_time_id', $this->ST->id)
            ->get()->pluck('id')->toArray();

        $areas = ResourceArea::query()
            ->whereIn('id', $areasIDS)
            ->withWhereHas(
                'subjects',
                function ($query) use ($studentsIDS, $groupsIDS, $periodIDS) {
                    $query->where('school_year_id', $this->Y->id)->with('resourceSubject')
                        ->withWhereHas(
                            'academicWorkload',
                            function ($query) {
                                $query->where('school_year_id', $this->Y->id)->where('study_year_id', $this->SY->id)->select('id', 'course_load', 'subject_id');
                            }
                        )->with([
                            'teacherSubject' => function ($query) use ($studentsIDS, $groupsIDS, $periodIDS) {
                                $query->where('school_year_id', $this->Y->id)->whereIn('group_id', $groupsIDS)
                                ->with([
                                    'grades' => function ($query) use ($studentsIDS, $periodIDS) {
                                        $query->whereIn('student_id', $studentsIDS)->whereIn('period_id', $periodIDS);
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
        if ($this->period === 'FINAL') return Excel::download(new ConsolidateGeneralGradesGroup($this->G, $this->schoolYear, $this->ST, $this->period, $areas, $students), 'Consolidado final - Group ' . $this->G->name . '.xlsx');
        else Excel::store(new ConsolidateGeneralGradesGroup($this->G, $this->schoolYear, $this->ST, $this->period, $areas, $students), $this->path .'Consolidado general - Group ' . $this->G->name . '.xlsx', 'public');

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


    public function generateStudyYear(Request $request)
    {
        $request->validate([
            'downloadConslidateId' => ['required'],
            'downloadConslidateStudyTime' => ['required', Rule::exists('study_times', 'id')]
        ]);

        $this->period = 'FINAL';


        /* Dirección para guardar los reportes generados */
        $pathUuid = Str::uuid();
        $pathReport = "reports/". $pathUuid ."/";

        if (!File::isDirectory(public_path($pathReport))) {
            File::makeDirectory(public_path($pathReport), 0755, true, true);
        }

        $this->path = $pathReport;


        $this->Y = SchoolYearController::current_year();
        $studyYear = StudyYear::find($request->get('downloadConslidateId'));
        $ST = StudyTime::find($request->get('downloadConslidateStudyTime'));


        $groupsIDS = Group::query()
                ->whereNull('specialty')
                ->where('school_year_id', $this->Y->id)
                ->where('study_year_id', $studyYear->id)
                ->where('study_time_id', $ST->id)
                ->get()->pluck('id')->toArray();


        $periodIDS = \App\Models\Period::query()
                ->when($this->period !== 'FINAL', function ($whenNoFinal) {
                    $whenNoFinal->where('ordering', '<=', $this->period->ordering);
                })
                ->where('school_year_id', $this->Y->id)
                ->where('study_time_id', $ST->id)
                ->get()->pluck('id')->toArray();

        $students = Student::singleData()->withWhereHas('groupYear', function ($gy) use ($studyYear, $ST) {
            $gy->whereHas('group', function ($g) use ($studyYear, $ST) {
                $g->whereNull('specialty')->where('school_year_id', $this->Y->id)->where('study_year_id', $studyYear->id)->where('study_time_id', $ST->id);
            });
        })
        ->with(['grades' => function ($g) use ($periodIDS) { $g->whereIn('period_id', $periodIDS); }])
        ->get();

        $students->map(function ($studentMap) use ($studyYear, $ST) {
                $groupSpecialty = Group::where('specialty', TRUE)
                    ->where('school_year_id', $this->Y->id)
                    ->where('study_year_id', $studyYear->id)
                    ->where('study_time_id', $ST->id)
                    ->whereHas(
                        'groupStudents',
                        fn ($query) => $query->where('student_id', $studentMap->id)
                    )
                    ->first();

                return $studentMap->setAttribute('specialty', $groupSpecialty->specialty_area_id ?? null);
            });


        $areas = ResourceArea::query()
            ->withWhereHas(
                'subjects',
                function ($query) use ($groupsIDS, $studyYear) {
                    $query->where('school_year_id', $this->Y->id)->with('resourceSubject')
                        ->withWhereHas(
                            'academicWorkload',
                            function ($query) use ($studyYear) {
                                $query->where('school_year_id', $this->Y->id)->where('study_year_id', $studyYear->id)->select('id', 'course_load', 'subject_id');
                            }
                        )->with([
                            'teacherSubjectGroups' => function ($query) use ($groupsIDS) {
                                $query->where('school_year_id', $this->Y->id)->whereIn('group_id', $groupsIDS);
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
                        $academic_porcentage = (float)($subjectMap->academicWorkload->course_load / 100);
                        return [
                            'id' => $subjectMap->id,
                            'resource_name' => $subjectMap->resourceSubject->public_name,
                            'academic_workload' => $subjectMap->academicWorkload->course_load,
                            'academic_wordload_porcentage' => $academic_porcentage,
                            'tsg' => $subjectMap->teacherSubjectGroups->map(function ($tsgMap) {
                                return [
                                    'id' => $tsgMap->id,
                                    'subject_id' => $tsgMap->subject_id,
                                    'group_id' => $tsgMap->group_id
                                ];
                            })
                        ];
                    })
                ];
            });

        return $this->generateStudyYearPDF($areas, $studyYear, $ST, $students);

    }

    private function generateStudyYearPDF($areas, $studyYear, $ST, $students)
    {
        /* Informe general */
        return Excel::download(new ConsolidateGeneralGradesStudyYear($this->Y, $studyYear, $ST, $this->period, $areas, $students), 'Consolidado final - ' . strtoupper($studyYear->name) .' '. strtoupper($ST->name) . '.xlsx');
    }
}
