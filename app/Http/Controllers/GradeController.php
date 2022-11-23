<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Grade;
use App\Models\Period;
use App\Models\Student;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'students.*' => ['required'],
            'students.*.conceptual' => ['numeric'],
            'students.*.procedural' => ['numeric'],
            'students.*.attitudinal' => ['numeric']
        ]);

        $group = $subject->group;
        $studyTime = $subject->group->studyTimeSelectAll;

        if ($studyTime->round === 1) {
            $round = PHP_ROUND_HALF_UP;
        } else {
            $round = PHP_ROUND_HALF_DOWN;
        }

        $period = Period::where('study_time_id', $subject->group->study_time_id)->orderBy('ordering')->get()->filter(function ($p) {
            if ( $p->active() ) return $p;
        });

        if (!count($period)) {
            return redirect()->back()->withErrors(__('No active period'));
        }


        DB::beginTransaction();
        foreach ($request->students as $code => $grades) {
            $student = Student::where('code', $code)->where('group_id', $group->id)->first();
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


            Grade::updateOrCreate(
                [
                    'teacher_subject_group_id' => $subject->id,
                    'period_id' => $period[1]->id,
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

        DB::commit();

        Notify::success(__('Saved!'));
        return redirect()->route('teacher.my.subjects.show', $subject);
    }

    public static function gradesStudent($subject, $period, $student)
    {
        return Grade::where('teacher_subject_group_id', $subject)
                    ->where('period_id', $period)
                    ->where('student_id', $student)
                    ->first();
    }
}
