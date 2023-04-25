<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
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
use App\Models\TeacherSubjectGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class GradeController extends Controller
{

    /* Usada para la generacion de reporte de notas */
    private $countAreas = 0;

    public function __construct()
    {
        $this->middleware(OnlyTeachersMiddleware::class)->only('store');
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
                    $gradeConceptual = round($grades['conceptual'], $studyTime->decimal, $round);
                    $gradeProcedural = round($grades['procedural'], $studyTime->decimal, $round);
                    $gradeAttitudinal = round($grades['attitudinal'], $studyTime->decimal, $round);

                    $gradeFinal = (($gradeConceptual * $studyTime->conceptual) / 100)
                        + (($gradeProcedural * $studyTime->procedural) / 100)
                        + (($gradeAttitudinal * $studyTime->attitudinal) / 100);

                    $gradeFinal = round($gradeFinal, $studyTime->decimal, $round);
                } else {
                    $gradeFinal = round($grades['final'], $studyTime->decimal, $round);
                }
            }

            try {
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

    /* Notas por periodo y estudiante */
    public static function forPeriod($subject, $period, $student)
    {
        return Grade::where('teacher_subject_group_id', $subject)
            ->where('period_id', $period)
            ->where('student_id', $student)
            ->first();
    }

    /* Nota general por estudiante */
    public static function forStudent($student, $subject)
    {
        $grades = Grade::select('period_id', 'final')->where('teacher_subject_group_id', $subject->id)
            ->where('student_id', $student)->get();

        if (count($grades)) {

            $studyTime = $subject->group->studyTimeSelectAll;

            $def = 0;
            foreach ($grades as $g) {
                $wl = ($g->period->workload / 100);
                $def += $g->final * $wl;
            }

            /* Verifica decimales y PHP_ROUND_HALF_UP | PHP_ROUND_HALF_DOWN  */
            $def = number_format(round($def, $studyTime->decimal, static::round($studyTime->round)), $studyTime->decimal);

        } else {
            $def = null;
        }

        return $def;
    }

    private static function round($r)
    {
        return $r ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN;
    }

    public static function performance($studyTime, $value)
    {
        if ( ! is_null($value) )

            return $value > $studyTime->high_performance ? __('superior')
            : ($value > $studyTime->basic_performance ? __('high')
            : ($value > $studyTime->low_performance ? __('basic')
            : '<span class="alert alert-danger px-2 py-1">' . __('low') . '</span>'));


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
        $areasWithSubjects = $this->teacher_subject($Y, $group);
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


        /* Extraer los periodos del StudyTime del grupo */
        $periods = Period::where('school_year_id', $group->school_year_id)
            ->where('study_time_id', $group->study_time_id)
            ->when($currentPeriod !== 'FINAL', function ($query) use ($currentPeriod) {
                return $query->where('ordering', '<=', $currentPeriod->ordering);
            })
            ->orderBy('ordering')->get();



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

    private function reportForStudentPeriod($Y, $SCHOOL, $group, $studyTime, $currentPeriod, $periods, $areasWithSubjects, $teacherSubjects, $student, $pathReport)
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

            $areaSpecialty = $this->teacher_subject($Y, $existGroupSpecialty)->first();
            $areasWithSubjects[$this->countAreas] = $areaSpecialty;

            foreach ($areaSpecialty->subjects as $subjectSpecialty) {
                if (!is_null($subjectSpecialty->teacherSubject))
                    array_push($teacherSubjects, $subjectSpecialty->teacherSubject->id);
            }
        } else {
            unset($areasWithSubjects[$this->countAreas]);
        }


        /* Notas del estudiante de los periodos y asignaturas del StudyYear actual */
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('period_id', $periods->pluck('id'))
            ->get();

        if ($currentPeriod !== 'FINAL') {

            $remark = Remark::where('group_id', $group->id)->where('period_id', $currentPeriod->id)->where('student_id', $student->id)->first()->remark ?? null;

            $descriptors = TeacherSubjectGroup::whereIn('id', $teacherSubjects)
                ->withWhereHas('descriptorsStudent',
                    fn ($descriptor) => $descriptor->where('student_id', $student->id)->with('descriptor')
                )
                ->with(['subject' => fn ($sj) => $sj->with('resourceSubject')])
                ->get();
        }
        else {
            $remark = NULL;
            $descriptors = NULL;
        }

        $pdf = Pdf::loadView('logro.pdf.report-notes', [
            'SCHOOL' => $SCHOOL,
            'date' => now()->format('d/m/Y'),
            'student' => $student,
            'areas' => $areasWithSubjects,
            'periods' => $periods,
            'currentPeriod' => $currentPeriod,
            'grades' => $grades,
            'group' => $group,
            'studyTime' => $studyTime,
            'titleReportNotes' => $titleReportNotes,
            'remark' => $remark,
            'descriptors' => $descriptors
        ]);

        $pdf->setPaper([0.0, 0.0, 612, 1008]);
        $pdf->setOption('dpi', 72);
        $pdf->save($pathReport . "Reporte de notas - ". $student->getCompleteNames() . '.pdf');
    }

    public function teacher_subject($Y, $group)
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

        // if ( ! $group->specialty ) {
        //     return ResourceArea::whereNull('specialty')
        //         ->withWhereHas('subjects', $fn_sb)
        //         ->orderBy('name')->get();
        // } else {
        //     return ResourceArea::where('id', $group->specialty_area_id)
        //         ->withWhereHas('subjects', $fn_sb)
        //         ->orderBy('name')->get();
        // }
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

    public static function numberFormat($studyTime, $value)
    {
        if ($value) {

            $round = $studyTime->round === 1 ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN;
            return number_format(round($value, $studyTime->decimal, $round), $studyTime->decimal);
        }

        return $studyTime->minimum_grade;
    }
}
