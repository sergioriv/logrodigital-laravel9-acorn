<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeveledStudentController extends Controller
{

    public function leveling(TeacherSubjectGroup $subject, Request $request)
    {
        $request->validate([
            'studentsLeveling' => ['nullable', 'array'],
        ]);

        $students = collect();
        DB::beginTransaction();

        try {

            \App\Models\LeveledStudent::where('teacher_subject_group_id', $subject->id)->delete();

            if ($request->has('studentsLeveling')) {

                $leveledStudents = array_keys($request->studentsLeveling);

                $students = \App\Models\Student::whereIn('code', $leveledStudents)->select('id')->get();

                foreach ($students as $student) {
                    \App\Models\LeveledStudent::create([
                        'teacher_subject_group_id' => $subject->id,
                        'student_id' => $student->id
                    ]);
                }
            }

        } catch (\Throwable $th) {
            info($th->getMessage());
            Notify::fail(__('An error has occurred'));
            return back();
        }

        DB::commit();
        Notify::success('Nivelación aplicada a ' . count($students) . ' estudiantes');
        return back();
    }

    public static function leveledStudentForReport($studentId, $tsgId, $studyTime, $currentGrate) : array
    {
        $checkLeveling = \App\Models\LeveledStudent::where('teacher_subject_group_id', $tsgId)->where('student_id', $studentId)->count();

        return $checkLeveling == 0
            ? ['showLeveling' => false, 'grade' => $currentGrate]
            : ['showLeveling' => true, 'grade' => \App\Http\Controllers\GradeController::numberFormat($studyTime, ($studyTime->low_performance + $studyTime->step))];

    }
}
