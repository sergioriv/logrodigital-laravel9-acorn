<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        switch (UserController::role_auth()) {
                /* case 'Support':
                $support = User::findOrFail(Auth::user()->id);
                return view('profile.support-edit')->with('support', $support);
                break; */

                /* case 'STUDENT':
                $student = User::findOrFail(Auth::user()->id);
                return view('profile.estudent-edit')->with('student', $student);
                break; */
                /*
            case 'Branch':
                $branch = Branch::with('user')->findOrFail(Auth::user()->id);
                $deps = json_decode(file_get_contents('json/colombia.min.json'), true);

                return view('profile.branch-edit')->with(['branch' => $branch, 'deps' => $deps]);
                break; */

            default:
                return $this->not_found();
                break;
        }
    }

    public function edit()
    {
        $user = Auth::user();

        switch (UserController::role_auth()) {

            case 'SUPPORT':
                $support = User::findOrFail(Auth::user()->id);
                return view('profile.support-edit', ['support' => $support]);
                break;

            case 'SECRETARY':
                $support = User::findOrFail(Auth::user()->id);
                return view('profile.support-edit', ['support' => $support]);
                break;

            case 'TEACHER':
                $teacher = Teacher::where('id', Auth::user()->id)->first();
                return (new TeacherController)->profile($teacher);
                break;

            case 'STUDENT':
                $student = new StudentController();
                $student_find = Student::find($user->id);

                if ($student_find->wizard_documents === NULL) {
                    return $student->wizard_documents($student_find);
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

                return $student->show(Student::find(Auth::user()->id));
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
                $support = User::findOrFail(Auth::user()->id);
                UserController::profile_update($request, $support);
                break;

            case 'SECRETARY':
                $support = User::findOrFail(Auth::user()->id);
                UserController::profile_update($request, $support);
                break;

            case 'TEACHER':
                $teacher = Teacher::where('id', Auth::user()->id)->first();
                return (new TeacherController)->profile_update($teacher, $request);
                break;

            case 'STUDENT':
                $student = Student::findOrFail(Auth::user()->id);
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
            $student_find = Student::find( Auth::user()->id );

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
