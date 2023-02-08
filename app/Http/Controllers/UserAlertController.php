<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
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
            $newAlert->created_user_id = Auth::id();
            $newAlert->created_rol = RoleUser::ORIENTATION_ROL;

            if ($request->priority_coordinator === '1') {
                $newAlert->priority = TRUE;

                SmtpMail::sendMailAlert(
                    __(self::TITLE_ORIENTATION, ['CREATE_BY' => UserController::myName(), 'STUDENT_NAME' => $student->getCompleteNames()]),
                    $coordinator,
                    $request->recommendations_coordinator
                );
            }

            return $newAlert->save();
        }

        return false;
    }

    public static function orientation_to_teacher(Student $student, Request $request)
    {
        if ($student->enrolled && UserController::role_auth() === RoleUser::ORIENTATION_ROL) {

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
                $newAlert->created_user_id = Auth::id();
                $newAlert->created_rol = RoleUser::ORIENTATION_ROL;

                if ($request->priority_teacher === '1') {
                    $newAlert->priority = TRUE;
                }

                $newAlert->save();
            }

            if ($request->priority_teacher === '1') {

                SmtpMail::sendMailAlert(
                    __(self::TITLE_ORIENTATION, ['CREATE_BY' => UserController::myName(), 'STUDENT_NAME' => $student->getCompleteNames()]),
                    $teachersGroup,
                    $request->recommendations_teachers
                );
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
                $newAlert->created_user_id = Auth::id();
                $newAlert->created_rol = RoleUser::TEACHER_ROL;

                if ($request->priority_teacher === '1') {
                    $newAlert->priority = TRUE;
                }

                $newAlert->save();
            }

            if ($request->priority_orientation === '1') {

                SmtpMail::init()->sendMailAlert(
                    __(self::TITLE_TEACHER, ['CREATE_BY' => UserController::myName(), 'STUDENT_NAME' => $student->getCompleteNames()]),
                    $orientators,
                    $request->recommendations_orientation
                );

            }

            Notify::success(__('Report generated!'));
            return redirect()->route('students.show', $student);
        }

        return redirect()->back()->withErrors(__('Not allowed'));
    }


    /* access for methode GET */
    public function checked(UserAlert $alert)
    {
        if ($alert->for_user === Auth::id()) {

            $alert->forceFill(['checked' => TRUE])->save();

            Notify::success(__('Read alert!'));
            return redirect()->back();
        }

        return redirect()->back()->withErrors(__('Not allowed'));
    }
}
