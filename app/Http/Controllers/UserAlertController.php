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

class UserAlertController extends Controller
{

    public static function orientation_to_coordinator(Coordination $coordinator, Student $student, Request $request)
    {
        /* Create alert for User Coordinator */
        if (UserController::role_auth() === RoleUser::ORIENTATION_ROL) {

            UserAlert::create([
                'for_users' => [$coordinator->id],
                'priority' => $request->priority_coordinator === '1' ? TRUE : FALSE,
                'message' => $request->recommendations_coordinator,
                'student_id' => $student->id,
                'created_user_type' => UserController::myModelIs(),
                'created_user_id' => auth()->id(),
                'checked' => []
            ]);

            if ($request->priority_coordinator === '1') {

                SmtpMail::init()->sendMailAlert(
                    __('An alert has been generated for the student :STUDENT_NAME', ['STUDENT_NAME' => $student->getCompleteNames()]),
                    $coordinator,
                    $request->recommendations_coordinator
                );
            }

            return true;
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

            $teachers = $teachersGroup->pluck('teacher_id')->toArray();

            UserAlert::create([
                'for_users' => $teachers,
                'priority' => $request->priority_teacher === '1' ? TRUE : FALSE,
                'message' => $request->recommendations_teachers,
                'student_id' => $student->id,
                'created_user_type' => UserController::myModelIs(),
                'created_user_id' => auth()->id(),
                'checked' => []
            ]);


            if ($request->priority_teacher === '1') {

                SmtpMail::init()->sendMailAlert(
                    __('An alert has been generated for the student :STUDENT_NAME', ['STUDENT_NAME' => $student->getCompleteNames()]),
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
                'recommendations_orientation' => ['required', 'string', 'min:10', 'max:5000'],
                'actions_teacher' => ['required', 'string', 'min:10', 'max:5000'],
                'priority_orientation' => ['nullable', 'boolean']
            ]);

            $orientators = Orientation::all();

            if (!$orientators->count()) {
                return redirect()->back()->withErrors(__('No registered orientators'));
            }

            $orientatorsArray = $orientators->pluck('id')->toArray();

            UserAlert::create([
                'for_users' => $orientatorsArray,
                'priority' => $request->priority_orientation === '1' ? TRUE : FALSE,
                'message' => $request->recommendations_orientation,
                'sub_message' => $request->actions_teacher,
                'student_id' => $student->id,
                'created_user_type' => UserController::myModelIs(),
                'created_user_id' => auth()->id(),
                'checked' => []
            ]);


            if ($request->priority_orientation === '1') {

                SmtpMail::init()->sendMailAlert(
                    __('An alert has been generated for the student :STUDENT_NAME', ['STUDENT_NAME' => $student->getCompleteNames()]),
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
        if ($alert->for_user === auth()->id()) {

            $checked = (array)$alert->checked;
            array_push($checked, auth()->id());

            $alert->update([
                'checked' => $checked
            ]);

            Notify::success(__('Read alert!'));
            return redirect()->back();
        }

        return redirect()->back()->withErrors(__('Not allowed'));
    }
}
