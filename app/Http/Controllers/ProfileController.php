<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Coordination;
use App\Models\Orientation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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

            case 'ORIENTATION':
                $orientation = Orientation::where('id', Auth::id())->first();
                return (new OrientationController)->profile($orientation);
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
                    return $student->wizard_complete($student_find);
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

            case 'ORIENTATION':
                $coordination = Orientation::where('id', Auth::id())->first();
                return (new OrientationController)->profile_update($coordination, $request);
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

    // FOR ESTUDENT
    public function student_avatar_edit(\App\Models\Student $student)
    {
        return view('profile.student-avatar-edit', ['student' => $student]);
    }

    public function student_avatar_edit_update(Request $request, \App\Models\Student $student)
    {
        $request->validate([
            'file_upload' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);

        $user = $student->user;

        $path_file = \App\Http\Controllers\StudentFileController::upload_file($request, 'file_upload', $student->id);
        if (!$path_file) {
            return redirect()->back()->withErrors(__('An error occurred while uploading the file, please try again.'));
        }

        $studentFile = \App\Models\StudentFile::where('student_id', $student->id)
            ->where('student_file_type_id', 9)
            ->first();

        if ($request->hasFile('file_upload') && $studentFile->url_absolute !== NULL) {
            File::delete(public_path($studentFile->url_absolute));
        }

        $studentFile->creation_user_id = Auth::id();
        $studentFile->url = config('app.url') . '/' . $path_file;
        $studentFile->url_absolute = $path_file;
        $studentFile->save();

        $user->avatar = $path_file;
        $user->save();

        Notify::success( __('Avatar Updated!') );
        return redirect()->route('students.show', $student->id);
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
