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

            if ($group->specialty) {

                $student = Student::where('code', $code)->where('group_specialty_id', $group->id)->first();
            } else {

                $student = Student::where('code', $code)->where('group_id', $group->id)->first();
            }


            if (!$student) {

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
            $gradeConceptual = round($grades['conceptual'], $studyTime->decimal, $round);
            $gradeProcedural = round($grades['procedural'], $studyTime->decimal, $round);
            $gradeAttitudinal = round($grades['attitudinal'], $studyTime->decimal, $round);

            $gradeFinal = (($gradeConceptual * $studyTime->conceptual) / 100)
                + (($gradeProcedural * $studyTime->procedural) / 100)
                + (($gradeAttitudinal * $studyTime->attitudinal) / 100);

            $gradeFinal = round($gradeFinal, $studyTime->decimal, $round);

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
                return redirect()->back()->withErrors(__(
                    "The student (:STUDENT) doesn't belong to the group: :GROUP",
                    [
                        'STUDENT' => $code,
                        'GROUP' => $group->name
                    ]
                ));
            }


            /* Guardando los descriptores */
            if (isset($grades['descriptors']) && is_array( $grades['descriptors'] )) {
                try {

                    /* borrar los descriptores asignados para agregar los que vienen */
                    StudentDescriptor::where('student_id', $student->id)->where('teacher_subject_group_id', $subject->id)->delete();

                    foreach ($grades['descriptors'] as $descriptor) {
                        StudentDescriptor::create([
                            'teacher_subject_group_id' => $subject->id,
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
        $studyTime = $subject->group->studyTimeSelectAll;

        $grades = Grade::select('period_id', 'final')->where('teacher_subject_group_id', $subject->id)
            ->where('student_id', $student)->get();

        if (count($grades)) {

            $def = 0;
            foreach ($grades as $g) {
                $wl = ($g->period->workload / 100);
                $def += $g->final * $wl;
            }
        } else {
            $def = $studyTime->minimum_grade;
        }

        /* Verifica decimales y PHP_ROUND_HALF_UP | PHP_ROUND_HALF_DOWN  */
        $def = number_format(round($def, $studyTime->decimal, static::round($studyTime->round)), $studyTime->decimal);

        return $def;
    }

    private static function round($r)
    {
        return $r ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN;
    }

    public static function performance($studyTime, $value)
    {
        return $value > $studyTime->high_performance ? __('superior') : ($value > $studyTime->basic_performance ? __('high') : ($value > $studyTime->low_performance ? __('basic') :
                    '<span class="alert alert-danger px-2 py-1">' . __('low') . '</span>'));
    }
    public static function performanceString($studyTime, $value)
    {
        return $value > $studyTime->high_performance ? __('superior') : ($value > $studyTime->basic_performance ? __('high') : ($value > $studyTime->low_performance ? __('basic') :
                    __('low')));
    }



    /* REPORT OF NOTES */
    public function reportForPeriod(Request $request, Group $group)
    {
        $Y = SchoolYearController::current_year();

        $SCHOOL = SchoolController::myschool()->getData();

        $request->validate([
            'period' => ['required', Rule::exists('periods', 'id')->where('school_year_id', $Y->id)->where('study_time_id', $group->study_time_id)]
        ]);

        $currentPeriod = Period::find($request->period);

        /* Obtiene las areas y asignaturas del grupo que corresponde */
        $areasWithSubjects = $this->teacher_subject($Y, $group);
        $this->countAreas = $areasWithSubjects->count();


        $teacherSubjects = [];
        foreach ($areasWithSubjects as $area) {
            foreach ($area->subjects as $sj) {
                if (!is_null($sj->teacherSubject))
                    array_push($teacherSubjects, $sj->teacherSubject->id);
            }
        }


        $studyTime = StudyTime::find($group->study_time_id);


        /* Extraer los periodos del StudyTime del grupo */
        $periods = Period::where('ordering', '<=', $currentPeriod->ordering)
            ->where('school_year_id', $group->school_year_id)
            ->where('study_time_id', $group->study_time_id)
            ->orderBy('ordering')->get();



        $groupStudents = GroupStudent::where('group_id', $group->id)
                ->with('student')
                ->get();



        /* Dirección para guardar los reportes generados */
        $pathReport = "app/reports/". Str::uuid() ."/";

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

        return view('logro.empty');

    }

    private function reportForStudentPeriod($Y, $SCHOOL, $group, $studyTime, $currentPeriod, $periods, $areasWithSubjects, $teacherSubjects, $student, $pathReport)
    {

        /* Nombre para el reporte de notas, en caso de ser el reporte final, dirá Final */
        $titleReportNotes = 'P' . $currentPeriod->ordering . ' - ' . $Y->name;

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

            foreach ($areaSpecialty->subjects as $subjectSpecialty) {
                if (!is_null($subjectSpecialty->teacherSubject))
                    array_push($teacherSubjects, $subjectSpecialty->teacherSubject->id);
            }

            $areasWithSubjects[$this->countAreas] = $areaSpecialty;
        }



        /* Notas del estudiante de los periodos y asignaturas del StudyYear actual */
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('period_id', $periods->pluck('id'))
            ->get();


        $remark = Remark::where('group_id', $group->id)->where('period_id', $currentPeriod->id)->where('student_id', $student->id)->first()->remark ?? null;



        $descriptors = TeacherSubjectGroup::whereIn('id', $teacherSubjects)
                ->withWhereHas('descriptorsStudent',
                    fn ($descriptor) => $descriptor->where('student_id', $student->id)->with('descriptor')
                )
                ->with(['subject' => fn ($sj) => $sj->with('resourceSubject')])
                ->get();



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


        if (!$group->specialty) {

            return ResourceArea::whereNull('specialty')
                ->withWhereHas('subjects', $fn_sb)
                ->orderBy('name')->get();
        } else {

            return ResourceArea::where('id', $group->specialty_area_id)
                ->withWhereHas('subjects', $fn_sb)
                ->orderBy('name')->get();
        }
    }

    public static function areaNoteStudent($area, $periods, $grades, $studyTime)
    {

        $areaNotes = [];
        $subjectNotes = [];

        $i = 1;
        foreach ($area->subjects as $subject) {

            $j = 1;
            foreach ($periods as $period) {

                $note = $grades->filter(function ($g) use ($subject, $period) {
                    if (!is_null($subject->teacherSubject))
                        return $g->teacher_subject_group_id == $subject->teacherSubject->id
                            && $g->period_id == $period->id;
                })->first()->final ?? 0;

                if (!is_null($subject->academicWorkload))
                    $subjectNotes[$i][$j] = $note * ($subject->academicWorkload->course_load / 100);
                else $subjectNotes[$i][$j] = 0;

                $j++;
            }

            $i++;
        }

        for ($x = 1; $x <= count($periods); $x++) {
            $suma = 0;
            for ($y = 1; $y <= count($area->subjects); $y++) {
                $suma += $subjectNotes[$y][$x];
            }

            /* Verifica decimales y PHP_ROUND_HALF_UP | PHP_ROUND_HALF_DOWN  */
            // $suma = number_format( round($suma, $studyTime->decimal, static::round($studyTime->round)), $studyTime->decimal );

            $areaNotes[$x] = static::numberFormat($studyTime, $suma);
        }

        $overallAvg = static::numberFormat($studyTime, (array_sum($areaNotes) / count($periods)));

        return ['overallAvg' => $overallAvg, 'area' => $areaNotes];
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
