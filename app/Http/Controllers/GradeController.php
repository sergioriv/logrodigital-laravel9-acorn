<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Imports\GroupGradesImport;
use App\Models\Grade;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Period;
use App\Models\PeriodPermit;
use App\Models\Remark;
use App\Models\ResourceArea;
use App\Models\Student;
use App\Models\StudentDescriptor;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\TeacherSubjectGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class GradeController extends Controller
{

    /* Usada para la generacion de reporte de notas */
    private $countAreas = 0;

    public function __construct()
    {
        $this->middleware(OnlyTeachersMiddleware::class)->only('store');
        $this->middleware('hasroles:SUPPORT,COORDINATOR')->only('editGradesStudent', 'saveGradesForStudent');
    }

    public function store(TeacherSubjectGroup $subject, Request $request)
    {
        $request->validate([
            'students' => ['required', 'array'],
            'period' => ['required', Rule::exists('periods', 'id')],
            'students.*' => ['required']
        ]);

        $group = $subject->group;
        $studyTime = $subject->group->studyTimeSelectAll;
        $studyYear = $subject->group->studyYear;

        /* Verifica si PHP_ROUND_HALF_UP | PHP_ROUND_HALF_DOWN  */
        $round = static::round($studyTime->round);

        /* Traemos el periodo para verificar si esta disponible para su calificacion */
        $period = Period::where('id', $request->period)
            ->withCount(['permits as permit' => fn ($p) => $p->teacher_subject_group_id = $subject->id])
            ->first();

        if (!$period->active() && !$period->permit) {
            return redirect()->back()->withErrors(__('No active period'));
        }


        DB::beginTransaction();
        foreach ($request->students as $code => $grades) {

            if ($group->specialty) { $student = Student::where('code', $code)->where('group_specialty_id', $group->id)->first(); }
            else { $student = Student::where('code', $code)->where('group_id', $group->id)->first(); }

            if ( ! $student ) {

                DB::rollBack();
                return redirect()->back()->withErrors(__(
                    "The student (:STUDENT) doesn't belong to the group: :GROUP",
                    [
                        'STUDENT' => $code,
                        'GROUP' => $group->name
                    ]
                ));
            }

            /*
             * Se ajustan los decimales y redondeo definido para la Jornada del grupo
             *
             *  */
            $gradeConceptual = null;
            $gradeProcedural = null;
            $gradeAttitudinal = null;
            $gradeFinal = null;

            if ($studyYear->useGrades()) {
                if ($studyYear->useComponents()) {
                    $gradeConceptual = GradeController::validateGradeWithStudyTime($studyTime, $grades['conceptual']);
                    $gradeProcedural = GradeController::validateGradeWithStudyTime($studyTime, $grades['procedural']);
                    $gradeAttitudinal = GradeController::validateGradeWithStudyTime($studyTime, $grades['attitudinal']);

                    $gradeFinal = (($gradeConceptual * $studyTime->conceptual) / 100)
                        + (($gradeProcedural * $studyTime->procedural) / 100)
                        + (($gradeAttitudinal * $studyTime->attitudinal) / 100);

                    $gradeFinal = GradeController::validateGradeWithStudyTime($studyTime, $gradeFinal);
                } else {
                    $gradeFinal = GradeController::validateGradeWithStudyTime($studyTime, $grades['final']);
                }
            }

            try {
                if ( ! is_null($gradeFinal) ) {
                    Grade::updateOrCreate(
                        [
                            'teacher_subject_group_id' => $subject->id,
                            'period_id' => $period->id,
                            'student_id' => $student->id
                        ],
                        [
                            'conceptual'    => $gradeConceptual,
                            'procedural'    => $gradeProcedural,
                            'attitudinal'   => $gradeAttitudinal,
                            'final'         => $gradeFinal
                        ]
                    );
                }
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->withErrors(__('An error has occurred'));
            }


            /* Guardando los descriptores */
            if ($studyYear->useDescriptors()) {
                if (isset($grades['descriptors']) && is_array( $grades['descriptors'] )) {
                    try {

                        /* borrar los descriptores asignados para agregar los que vienen */
                        StudentDescriptor::where('student_id', $student->id)
                            ->where('teacher_subject_group_id', $subject->id)
                            ->where('period_id', $period->id)
                            ->delete();

                        foreach ($grades['descriptors'] as $descriptor) {
                            StudentDescriptor::create([
                                'teacher_subject_group_id' => $subject->id,
                                'period_id' => $period->id,
                                'student_id' => $student->id,
                                'descriptor_id' => $descriptor
                            ]);
                        }

                    } catch (\Throwable $th) {

                        DB::rollBack();
                        Notify::fail(__('saving error'));
                        return back();
                    }
                }
            }
        }

        DB::commit();

        /* En caso de tener un permiso, este se eliminará */
        PeriodPermit::where('teacher_subject_group_id', $subject->id)
            ->where('period_id', $period->id)->delete();


        Notify::success(__('Qualifications saved!'));
        return redirect()->route('teacher.my.subjects.show', $subject);
    }

    /*
     * Nota general por estudiante
     * @param $grades array
     * @param $ST StudyTime
    */
    public static function calculateGradeWithEvaluationComponents($grades, $ST)
    {
        if (count($grades) || ! is_null($grades) ) {

            $def = 0;
            foreach ($grades as $g) {
                $wl = ($g->period->workload / 100);
                $def += $g->final * $wl;
            }

            /* Verifica decimales y PHP_ROUND_HALF_UP | PHP_ROUND_HALF_DOWN  */
            $def = number_format(round($def, $ST->decimal, $ST->round ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN), $ST->decimal);

        } else {
            $def = null;
        }

        return ['definitive' => (float)$def, 'performance' => static::performanceHtml($ST, $def)];
    }

    private static function round($r)
    {
        return $r ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN;
    }

    private static function performanceHtml($studyTime, $value)
    {
        if ( ! is_null($value) ) {
            return match(true) {
                $value > $studyTime->high_performance => __('superior'),
                $value > $studyTime->basic_performance => __('hight'),
                $value > $studyTime->low_performance => __('basic'),
                default => '<span class="alert alert-danger px-2 py-1">' . __('low') . '</span>',
            };
        }

        return null;
    }
    public static function performanceString($studyTime, $value)
    {
        return $value > $studyTime->high_performance ? __('superior') : ($value > $studyTime->basic_performance ? __('high') : ($value > $studyTime->low_performance ? __('basic') :
                    __('low')));
    }


    /*
     *
     *
     *  REPORT OF NOTES
     *
     *
     * */
    public function editGradesStudent(Request $request, Group $group)
    {
        $request->validate([
            'studentId' => ['required', Rule::exists('students', 'id')]
        ]);

        $existsInGroup = GroupStudent::query()
            ->whereGroupId($group->id)
            ->whereStudentId($request->studentId)
            ->with('student:id,first_name,second_name,first_last_name,second_last_name')
            ->first();

        if ( ! $existsInGroup ) return false;

        $Y = SchoolYearController::current_year();
        $areas = GradeController::teacher_subject($Y, $group);
        $studyTime = $group->studyTimeSelectAll;

        $teacherSubject = [];
        foreach ($areas as $area) {
            foreach ($area->subjects as $subject) {
                if (!is_null($subject->teacherSubject))
                    array_push($teacherSubject, $subject->teacherSubject->id);
            }
        }

        $grades = Grade::whereIn('teacher_subject_group_id', $teacherSubject)->whereStudentId($request->studentId)->get();

        $periods = Period::query()
            ->where('school_year_id', $Y->id)
            ->where('study_time_id', $group->study_time_id)
            ->where('start', '<=', today()->format('Y-m-d'))
            ->orderBy('ordering')->get();

        $content = view('logro.grades.student', [
            'studentId' => $request->studentId,
            'groupId' => $group->id,
            'studyTime' => $studyTime,
            'periods' => $periods,
            'areas' => $areas,
            'grades' => $grades
        ])->render();

        return [
            'title' => $existsInGroup->student->getCompleteNames(),
            'content' => $content
        ];
    }

    public function saveGradesForStudent(Request $request, Group $group, Student $student)
    {
        if ( ! GroupStudent::whereGroupId($group->id)->whereStudentId($student->id)->first() ) {
            Notify::fail("El estudiante no pertenece a este grupo");
            return back();
        }

        $Y = SchoolYearController::current_year();
        $periods = $request->input('period');

        foreach ($periods as $periodId => $grades) {

            if ( is_array($grades) ) {

                // ya existe la calificacion y actualizará su valor
                if ( array_key_exists('grades', $grades) ) {
                    foreach ($grades['grades'] as $grade => $value) {
                        $gradeFind = Grade::whereId($grade)->wherePeriodId($periodId)->first();
                        if ( !is_null($gradeFind) ) if ($gradeFind->final != $value) $gradeFind->update(['final' => $value]);
                    }
                }

                // no existe una calificacion guardada pero si existe un docente asignado
                if ( array_key_exists('grades_teachers', $grades) ) {
                    foreach ($grades['grades_teachers'] as $teacherSubject => $value) {
                        if ( ! is_null($value) ) $this->createGrade($teacherSubject, $periodId, $student->id, $value);
                    }
                }

                // no existe una calificacion guardada ni existe un docente asignado
                if ( array_key_exists('grades_subjects', $grades) ) {
                    foreach ($grades['grades_subjects'] as $subject => $value) {
                        if ( ! is_null($value) ) {
                            $teacherSubject = TeacherSubjectGroup::updateOrCreate(
                                [
                                    'school_year_id' => $Y->id,
                                    'group_id' => $group->id,
                                    'subject_id' => $subject
                                ],
                                []
                            );

                            $this->createGrade($teacherSubject->id, $periodId, $student->id, $value);
                        }
                    }
                }
            }
        }

        Notify::success(__('Grades saved!'));
        return back();
    }

    private function createGrade($teacherSubject, $periodId, $studentId, $value)
    {
        Grade::create([
            'teacher_subject_group_id' => $teacherSubject,
            'period_id' => $periodId,
            'student_id' => $studentId,
            'conceptual' => null,
            'procedural' => null,
            'attitudinal' => null,
            'final' => $value
        ]);
    }

    public function importGroupGradesForPeriod(Request $request, TeacherSubjectGroup $subject, Period $period)
    {
        $request->validate([
            'grades_file' => ['required', 'file', 'max:5000', 'mimes:xls,xlsx']
        ]);

        $studyTime = $subject->group->studyTime;

        Excel::import(new GroupGradesImport($subject, $studyTime, $period->id), $request->file('grades_file'));

        Notify::success(__('Loaded Excel!'));
        return back();
    }

    /*
     *
     *
     *  REPORT OF NOTES
     *
     *
     * */
    public function reportForGroup(Request $request, Group $group)
    {
        $Y = SchoolYearController::current_year();

        $SCHOOL = SchoolController::myschool()->getData();

        if ($request->has('periodGradeReport') && $request->periodGradeReport !== 'FINAL') {
            $request->validate([
                'periodGradeReport' => ['required', Rule::exists('periods', 'id')->where('school_year_id', $Y->id)->where('study_time_id', $group->study_time_id)]
            ]);

            $currentPeriod = Period::find($request->periodGradeReport);
        } else {
            $currentPeriod = 'FINAL';
        }


        /* Obtiene las areas y asignaturas del grupo que corresponde */
        $areasWithSubjects = GradeController::teacher_subject($Y, $group);
        $this->countAreas = $areasWithSubjects->count();

        if (!$this->countAreas) {
            Notify::fail(__('An error has occurred'));
            return back();
        }


        $teacherSubjects = [];
        foreach ($areasWithSubjects as $area) {
            foreach ($area->subjects as $sj) {
                if (!is_null($sj->teacherSubject))
                    array_push($teacherSubjects, $sj->teacherSubject->id);
            }
        }


        $studyTime = StudyTime::find($group->study_time_id);
        $studyYear = StudyYear::find($group->study_year_id);


        /* Extraer los periodos del StudyTime del grupo */
        if ( $studyYear->useGrades() ) {
            $periods = Period::where('school_year_id', $group->school_year_id)
                ->where('study_time_id', $group->study_time_id)
                ->when($currentPeriod !== 'FINAL', function ($query) use ($currentPeriod) {
                    return $query->where('ordering', '<=', $currentPeriod->ordering);
                })
                ->orderBy('ordering')->get();
        } else {
            $periods = $currentPeriod;
        }

        $groupStudents = GroupStudent::where('group_id', $group->id)
                ->with('student:id,first_name,second_name,first_last_name,second_last_name')
                ->get();



        /* Dirección para guardar los reportes generados */
        $pathUuid = Str::uuid();
        $pathReport = "app/reports/". $pathUuid ."/";

        if (!File::isDirectory(public_path($pathReport))) {
            File::makeDirectory(public_path($pathReport), 0755, true, true);
        }

        foreach ($groupStudents as $GS) {
            $this->reportForStudentPeriod(
                $Y,
                $SCHOOL,
                $group,
                $studyYear,
                $studyTime,
                $currentPeriod,
                $periods,
                $areasWithSubjects,
                $teacherSubjects,
                $GS->student,
                $pathReport
            );
        }

        /* Generate Zip and Download */
        return (new ZipController($pathUuid))->downloadGradesGroup($group->name);
    }

    private function reportForStudentPeriod($Y, $SCHOOL, $group, $studyYear, $studyTime, $currentPeriod, $periods, $areasWithSubjects, $teacherSubjects, $student, $pathReport)
    {

        /* Nombre para el reporte de notas, en caso de ser el reporte final, dirá Final */
        $titleReportNotes = $currentPeriod !== 'FINAL'
            ? 'P' . $currentPeriod->ordering . ' - ' . $Y->name
            : $currentPeriod . ' - ' . $Y->name;


        /* Si el estudiante pertenece a un grupo de especialidad */
        $existGroupSpecialty = Group::where('study_year_id', $group->study_year_id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('specialty', 1)
            ->whereHas('groupStudents', function ($query) use ($student) {
                return $query->where('student_id', $student->id);
            })
            ->whereNotNull('specialty_area_id')->first();


        /* Si el estudiante tiene un grupo de especialidad, se agregará a la lista general con su area y asignatura de especialidad */
        if ($existGroupSpecialty) {

            $areaSpecialty = GradeController::teacher_subject($Y, $existGroupSpecialty)->first();
            $areasWithSubjects[$this->countAreas] = $areaSpecialty;

            foreach ($areaSpecialty->subjects as $subjectSpecialty) {
                if (!is_null($subjectSpecialty->teacherSubject))
                    array_push($teacherSubjects, $subjectSpecialty->teacherSubject->id);
            }
        } else {
            unset($areasWithSubjects[$this->countAreas]);
        }


        /* Notas del estudiante de los periodos y asignaturas del StudyYear actual */
        if ( $studyYear->useGrades() ) {
            $grades = Grade::where('student_id', $student->id)
                ->whereIn('period_id', $periods->pluck('id'))
                ->get();
        } else {
            $grades = null;
        }

        if ($currentPeriod !== 'FINAL') {

            $remark = Remark::where('group_id', $group->id)->where('period_id', $currentPeriod->id)->where('student_id', $student->id)->first()->remark ?? null;

            if ( $studyYear->useGrades() ) {
                $descriptors = TeacherSubjectGroup::whereIn('id', $teacherSubjects)
                    ->withWhereHas('descriptorsStudent',
                        fn ($descriptor) => $descriptor->where('student_id', $student->id)->with('descriptor')
                    )->with(['subject' => fn ($sj) => $sj->with('resourceSubject')])
                    ->get();
            } else {
                $descriptors = StudentDescriptor::whereIn('teacher_subject_group_id', $teacherSubjects)
                    ->where('period_id', $currentPeriod->id)
                    ->where('student_id', $student->id)
                    ->with('descriptor')
                    ->get();
            }
        }
        else {
            $remark = NULL;
            $descriptors = NULL;
        }

        $absencesTSG = TeacherSubjectGroup::whereIn('id', $teacherSubjects)
            ->withCount(['attendances' => function ($query) use ($student, $currentPeriod) {
                $query->whereBetween('date', [$currentPeriod->start, $currentPeriod->end]) ->whereHas('student', function ($queryAttend) use ($student) {
                    $queryAttend->where('student_id', $student->id)->whereIn('attend', ['N', 'L']);
                });
            }])->get();

        if ( $studyYear->useGrades() ) {
            $pdf = Pdf::loadView('logro.pdf.report-notes', [
                'SCHOOL' => $SCHOOL,
                'date' => now()->format('d/m/Y'),
                'student' => $student,
                'areas' => $areasWithSubjects,
                'periods' => $periods,
                'currentPeriod' => $currentPeriod,
                'grades' => $grades,
                'absencesTSG' => $absencesTSG,
                'group' => $group,
                'studyTime' => $studyTime,
                'titleReportNotes' => $titleReportNotes,
                'remark' => $remark,
                'descriptors' => $descriptors
            ]);
        } else {
            $pdf = Pdf::loadView('logro.pdf.report-notes-only-descriptors', [
                'SCHOOL' => $SCHOOL,
                'date' => now()->format('d/m/Y'),
                'student' => $student,
                'areas' => $areasWithSubjects,
                'currentPeriod' => $currentPeriod,
                'absencesTSG' => $absencesTSG,
                'group' => $group,
                'studyTime' => $studyTime,
                'titleReportNotes' => $titleReportNotes,
                'remark' => $remark,
                'descriptors' => $descriptors
            ]);
        }

        $pdf->setPaper([0.0, 0.0, 612, 1008]);
        $pdf->setOption('dpi', 72);
        $pdf->save($pathReport . "Reporte de notas - ". $student->getCompleteNames() . '.pdf');
    }

    public static function teacher_subject($Y, $group)
    {
        $fn_sy = fn ($sy) =>
        $sy->where('school_year_id', $Y->id)
            ->where('study_year_id', $group->study_year_id);

        $fn_tsg = fn ($tsg) =>
        $tsg->where('school_year_id', $Y->id)
            ->where('group_id', $group->id)
            ->with('teacher');

        $fn_sb = fn ($s) =>
        $s->where('school_year_id', $Y->id)
            ->withWhereHas('academicWorkload', $fn_sy)
            ->with('resourceSubject')
            ->with(['teacherSubject' => $fn_tsg]);

        return ResourceArea::when(
            ! $group->specialty,
                function ($query) { $query->whereNull('specialty'); },
                function ($query) use ($group) { $query->where('id', $group->specialty_area_id); }
        )->withWhereHas('subjects', $fn_sb)->orderBy('name')->get();
    }

    public static function areaNoteStudent($area, $periods, $grades, $studyTime)
    {

        $areaNotes = [];
        $subjectNotes = [];

        //final
        $total = 0;
        $totalSubject = [];

        $i = 1;
        foreach ($area->subjects as $subject) {

            $j = 1;

            //final
            $totalSubject[$subject->id] = 0;

            foreach ($periods as $period) {

                $note = $grades->filter(function ($g) use ($subject, $period) {
                    if (!is_null($subject->teacherSubject))
                        return $g->teacher_subject_group_id == $subject->teacherSubject->id
                            && $g->period_id == $period->id;
                })->first()->final ?? 0;

                $notePeriod = $note * ($subject->academicWorkload->course_load / 100);
                $subjectNotes[$i][$j] = $notePeriod;

                // final
                $total += $notePeriod * ($period->workload / 100);
                $totalSubject[$subject->id] += $note * ($period->workload / 100);

                $j++;
            }

            //final
            $totalSubject[$subject->id] = static::numberFormat($studyTime, $totalSubject[$subject->id]);

            $i++;
        }

        for ($x = 1; $x <= count($periods); $x++) {
            $suma = 0;
            for ($y = 1; $y <= count($area->subjects); $y++) {
                $suma += $subjectNotes[$y][$x];
            }
            $areaNotes[$x] = static::numberFormat($studyTime, $suma);
        }

        $overallAvg = static::numberFormat($studyTime, (array_sum($areaNotes) / count($periods)));

        //final
        $total = static::numberFormat($studyTime, $total);

        return ['overallAvg' => $overallAvg, 'area' => $areaNotes, 'total' => $total, 'totalSubject' => $totalSubject];
    }

    /**
     * @return array [periods, areas with grades]
     *  */
    public static function studentGrades($Y, $student)
    {
        $groups = GroupStudent::where('student_id', $student->id)
        ->whereHas('group', fn ($group) => $group->where('school_year_id', $Y->id) )
        ->get();

        if (!count($groups)) return ['periods' => NULL, 'areasGrade' => NULL];

        $groupsIDS = $groups->pluck('group_id')->toArray();
        $studyYear = $groups->first()->group->studyYear;
        $studyTime = $groups->first()->group->studyTime;

        $periods = Period::where('study_time_id', $studyTime->id)->orderBy('ordering')->get();

        $areas = ResourceArea::query()
            ->withWhereHas(
                'subjects', function ($query) use ($Y, $groupsIDS, $studyYear, $student) {
                    $query->where('school_year_id', $Y->id)->with('resourceSubject')
                    ->withWhereHas(
                        'academicWorkload', function ($query) use ($Y, $studyYear) {
                            $query->where('school_year_id', $Y->id)->where('study_year_id', $studyYear->id)->select('id', 'course_load', 'subject_id');
                        }
                    )->with([
                        'teacherSubject' => function ($query) use ($Y, $groupsIDS, $student) {
                            $query->where('school_year_id', $Y->id)->whereIn('group_id', $groupsIDS)->with([
                                'grades' => function ($query) use ($student) {
                                    $query->where('student_id', $student->id);
                                }
                            ]);
                        }
                    ]);
                }
            )
            ->orderBy('name')->get()
            ->map(function ($areasMap) use ($periods, $studyTime) {
                $areaMap = [
                    'id' => $areasMap->id,
                    'name' => $areasMap->name,
                    'subjects' => $areasMap->subjects->map(function ($subjectMap) use ($studyTime, $periods) {
                        $subjectGrade = $subjectMap?->teacherSubject?->grades;
                        return [
                            'id' => $subjectMap->id,
                            'resource_name' => $subjectMap->resourceSubject->name,
                            'academic_workload' => $subjectMap->academicWorkload->course_load,
                            'academic_wordload_porcentage' => (float)($subjectMap->academicWorkload->course_load / 100),
                            'teacher_subject_group' => $subjectMap?->teacherSubject?->id,
                            'grades' => $subjectGrade ? $subjectGrade->map(function ($gradeMap) use ($studyTime) {
                                return [
                                    'id' => $gradeMap->id,
                                    'period_id' => $gradeMap->period_id,
                                    'final' => $gradeMap->final ?: $studyTime->minimum_grade,
                                ];
                            }) : []
                        ];
                    }),
                ];

                $areaMap['period_grades'] = GradeController::periodGradesXArea($areaMap, $periods)->map(function ($periodGradeMap) use ($studyTime) {
                    return GradeController::numberFormat($studyTime, $periodGradeMap);
                });

                return $areaMap;
            });

        $periods->map(function ($period) use ($studyTime, $areas) {
            return $period->setAttribute('gradeAVG', GradeController::periodAVG($studyTime, $period, $areas));
        });

        return ['periods' => $periods, 'areasGrade' => $areas];
    }

    private static function periodAVG($studyTime, $period, $gradeAreas)
    {
        $periodAVG = 0;
        foreach ($gradeAreas as $gradeArea) {
            $periodAVG += $gradeArea['period_grades'][$period->id] ?: 0;
        }
        return GradeController::numberFormat($studyTime, ($periodAVG / count($gradeAreas)) );
    }

    /**
     * @return collect
     *  */
    public static function periodGradesXArea($area, $periods)
    {
        $periodTotal = [];
        foreach ($periods as $period) {
            $periodTotal[$period->id] = 0;
            foreach ($area['subjects'] as $subject) {
                foreach ($subject['grades'] as $grade) {
                    $periodTotal[$period->id] +=
                        $grade['period_id'] === $period->id
                        ? $grade['final'] * $subject['academic_wordload_porcentage']
                        : 0;
                }
            }
        }

        return collect($periodTotal);
    }

    public static function numberFormat($studyTime, $value)
    {
        if (is_null($value) || !$value) return NULL;

        if ($value) {

            $round = $studyTime->round === 1 ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN;
            return number_format(round($value, $studyTime->decimal, $round), $studyTime->decimal);
        }

        return $studyTime->minimum_grade;
    }

    public static function validateGradeWithStudyTime(StudyTime $studyTime, $grade)
    {
        if (is_null($grade)) return null;
        if (!is_numeric($grade)) return null;

        $min = $studyTime->minimum_grade;
        $max = $studyTime->maximum_grade;

        if ($grade < $min) throw ValidationException::withMessages(['data' => 'La nota no puede ser inferior a ' . $min]);
        if ($grade > $max) throw ValidationException::withMessages(['data' => 'La nota no puede ser superior a ' . $max]);

        return GradeController::numberFormat($studyTime, $grade);
    }
}
