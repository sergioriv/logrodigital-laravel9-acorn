<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Coordination;
use App\Models\Data\RoleUser;
use App\Models\Orientation;
use App\Models\Student;
use App\Models\TeacherSubjectGroup;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAlertController extends Controller
{

    const TITLE_ORIENTATION = "The orientator: :CREATE_BY, has created a recommendation for the student: :STUDENT_NAME";
    const TITLE_TEACHER = "The teacher: :CREATE_BY, has created a report for the student: :STUDENT_NAME";

    public static function orientation_to_coordinator(Coordination $coordinator, Student $student, Request $request)
    {
        /* Create alert for User Coordinator */
        if (UserController::role_auth() === RoleUser::ORIENTATION_ROL) {

            $newAlert = new UserAlert;

            $newAlert->for_user = $coordinator->id;
            $newAlert->title = self::TITLE_ORIENTATION;
            $newAlert->student_id = $student->id;
            $newAlert->message = $request->recommendations_coordinator;
            $newAlert->created_user_id = Auth::user()->id;
            $newAlert->created_rol = RoleUser::ORIENTATION_ROL;

            if ($request->priority_coordinator === '1') {
                $newAlert->priority = TRUE;
            }

            return $newAlert->save();
        }

        return false;
    }

    public static function orientation_to_teacher(Student $student, Request $request)
    {
        if ($student->enrolled /* && UserController::role_auth() === RoleUser::ORIENTATION_ROL */) {

            $teachersGroup = TeacherSubjectGroup::with('teacher')->where('group_id', $student->group_id)->distinct()->get(['teacher_id']);

            if (!$teachersGroup->count()) {
                return __('The group has no teachers');
            }

            foreach ($teachersGroup as $teacherGroup) {

                $newAlert = new UserAlert;

                $newAlert->for_user = $teacherGroup->teacher->id;
                $newAlert->title = self::TITLE_ORIENTATION;
                $newAlert->student_id = $student->id;
                $newAlert->message = $request->recommendations_teachers;
                $newAlert->created_user_id = Auth::user()->id;
                $newAlert->created_rol = RoleUser::ORIENTATION_ROL;

                if ($request->priority_teacher === '1') {
                    $newAlert->priority = TRUE;
                }

                $newAlert->save();
            }

            return true;
        }

        return false;
    }


    /* access for methode POST */
    public function teacher_to_orientation(Student $student, Request $request)
    {
        if ($student->enrolled && UserController::role_auth() === RoleUser::TEACHER_ROL) {

            $request->validate([
                'recommendations_orientation' => ['required', 'string', 'min:10', 'max:1000'],
                'priority_orientation' => ['nullable', 'boolean']
            ]);

            $orientators = Orientation::all();

            if (!$orientators->count()) {
                return redirect()->back()->withErrors(__('No registered orientators'));
            }

            foreach ($orientators as $orientator) {

                $newAlert = new UserAlert;

                $newAlert->for_user = $orientator->id;
                $newAlert->title = self::TITLE_TEACHER;
                $newAlert->student_id = $student->id;
                $newAlert->message = $request->recommendations_orientation;
                $newAlert->created_user_id = Auth::user()->id;
                $newAlert->created_rol = RoleUser::TEACHER_ROL;

                if ($request->priority_teacher === '1') {
                    $newAlert->priority = TRUE;
                }

                $newAlert->save();
            }

            Notify::success(__('Report generated!'));
            return redirect()->route('students.view', $student);

        }

        return redirect()->back()->withErrors(__('Not allowed'));
    }
}
