<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Coordination;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function edit()
    {
        $user = Auth::user();

        switch (UserController::role_auth()) {

            case 'SUPPORT':
                $support = User::findOrFail(Auth::id());
                return view('profile.support-edit', ['support' => $support]);
                break;

            case 'COORDINATOR':
                $coordination = Coordination::where('id', Auth::id())->first();
                return (new CoordinationController)->profile($coordination);
                break;

            case 'SECRETARY':
                $support = User::findOrFail(Auth::id());
                return view('profile.support-edit', ['support' => $support]);
                break;

            case 'TEACHER':
                $teacher = Teacher::where('id', Auth::id())->first();
                return (new TeacherController)->profile($teacher);
                break;

            case 'STUDENT':
                $student = new StudentController();
                $student_find = Student::find($user->id);

                if ($student_find->wizard_documents === NULL) {
                    return $student->wizard_documents($student_find);
                    break;
                } elseif ($student_find->wizard_report_books === NULL) {
                    return $student->wizard_reportBooks($student_find);
                    break;
                } elseif ($student_find->wizard_person_charge === NULL) {
                    return $student->wizard_person_charge($student_find);
                    break;
                } elseif ($student_find->wizard_personal_info === NULL) {
                    return $student->wizard_personal_info($student_find);
                    break;
                } elseif ($student_find->wizard_complete === NULL) {
                    return $student->wizard_complete();
                    break;
                }

                return $student->show(Student::find(Auth::id()));
                break;

            default:
                return $this->not_found();
                break;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        switch (UserController::role_auth()) {
            case 'SUPPORT':
                $support = User::findOrFail(Auth::id());
                UserController::profile_update($request, $support);
                break;

            case 'COORDINATOR':
                $coordination = Coordination::where('id', Auth::id())->first();
                return (new CoordinationController)->profile_update($coordination, $request);
                break;

            case 'SECRETARY':
                $support = User::findOrFail(Auth::id());
                UserController::profile_update($request, $support);
                break;

            case 'TEACHER':
                $teacher = Teacher::where('id', Auth::id())->first();
                return (new TeacherController)->profile_update($teacher, $request);
                break;

            case 'STUDENT':
                $student = Student::findOrFail(Auth::id());
                $update = new StudentController();
                $update->update($request, $student);
                break;


            default:
                return $this->not_found();
                break;
        }

        Notify::success( __('Updated!') );
        return redirect()->route('user.profile.edit');
    }

    public function auth_avatar_edit()
    {
        return view('profile.avatar-edit', ['user' => Auth::user()]);
    }

    public function auth_avatar_edit_update(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'file', 'mimes:jpg,jpeg,png,webp','max:2048']
        ]);

        $user = Auth::user();

        UserController::_update_avatar($request, $user);

        Notify::success( __('Avatar Updated!') );
        return redirect()->route('user.profile.edit');
    }



    public function update_avatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => ['required', 'file', 'mimes:jpg,jpeg,png,webp','max:2048']
        ]);

        UserController::_update_avatar($request, $user);

        Notify::success( __('Avatar Updated!') );
        return redirect()->back();
    }

    public function wizard()
    {

        if ('STUDENT' === UserController::role_auth()) {

            $student = new StudentController();
            $student_find = Student::find( Auth::id() );

            if ($student_find->wizard_documents === NULL) {
                return $student->wizard_documents($student_find);
            } elseif ($student_find->wizard_person_charge === NULL) {
                return $student->wizard_person_charge($student_find);
            } elseif ($student_find->wizard_personal_info === NULL) {
                return $student->wizard_personal_info($student_find);
            }

            return redirect()->intended(RouteServiceProvider::PROFILE);
        } else {
            return $this->not_found();
        }
    }











    private function not_found()
    {
        Notify::fail( __('Unauthorized!') );
        return redirect()->route('dashboard');
    }
}
