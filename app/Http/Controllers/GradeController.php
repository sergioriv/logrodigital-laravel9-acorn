<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Grade;
use App\Models\Period;
use App\Models\PeriodPermit;
use App\Models\Student;
use App\Models\TeacherSubjectGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
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
                return redirect()->back()->withErrors(__("The student (:STUDENT) doesn't belong to the group: :GROUP",
                        [
                            'STUDENT' => $code,
                            'GROUP' => $group->name
                        ]));
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
                return redirect()->back()->withErrors(__("The student (:STUDENT) doesn't belong to the group: :GROUP",
                        [
                            'STUDENT' => $code,
                            'GROUP' => $group->name
                        ]));
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
        $def = number_format( round($def, $studyTime->decimal, static::round($studyTime->round)), $studyTime->decimal );

        return $def;
    }

    private static function round($r)
    {
        return $r ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN;
    }

    public static function performance($studyTime, $value)
    {
        return $value > $studyTime->high_performance ? __('superior') :
                ($value > $studyTime->basic_performance ? __('high') :
                ($value > $studyTime->low_performance ? __('basic') :
                '<span class="alert alert-danger px-2 py-1">'. __('low') .'</span>'  ));
    }
}
