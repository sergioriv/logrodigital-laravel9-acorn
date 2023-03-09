<?php

namespace App\Http\Controllers;

use App\Models\Orientation;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RestorePasswordController extends Controller
{
    public function restore(Request $request)
    {
        if ( ! $request->role || ! $request->id ) {
            return false;
        }

        return match($request->role) {
            'TEACHER' => $this->restoreTeacher($request),
            'STUDENT' => $this->restoreStudent($request),
            'ORIENTATOR' => $this->restoreOrientator($request),
            default => false
        };
    }

    private function restoreStudent($request)
    {
        $student = Student::find($request->id);

        if ( ! is_null($student) ) {

            $newPassword = $this->generatePassword();

            $student->user->forceFill([
                'password' => Hash::make($newPassword),
                'change_password' => 0,
                'remember_token' => NULL
            ])->save();


            return $this->returnContent($student->institutional_email, $newPassword);
        }

        return false;
    }

    private function restoreTeacher($request)
    {
        $teacher = Teacher::find($request->id);

        if ( ! is_null($teacher) ) {

            $newPassword = $this->generatePassword();

            $teacher->user->forceFill([
                'password' => Hash::make($newPassword),
                'change_password' => 0,
                'remember_token' => NULL
            ])->save();


            return $this->returnContent($teacher->institutional_email, $newPassword);
        }

        return false;
    }

    private function restoreOrientator($request)
    {
        $orientator = Orientation::find($request->id);

        if ( ! is_null($orientator) ) {

            $newPassword = $this->generatePassword();

            $orientator->user->forceFill([
                'password' => Hash::make($newPassword),
                'change_password' => 0,
                'remember_token' => NULL
            ])->save();


            return $this->returnContent($orientator->institutional_email, $newPassword);
        }

        return false;
    }

    private function returnContent($email, $password)
    {
        $return = '<div class="mb-2">' . __('Email') . ": {$email}</div>";
        $return .= '<div>' . __('Temporary password') . ": <div class='font-weight-bold d-inline-block readable-text h4 m-0'>{$password}</div></div>";

        return $return;
    }

    private function generatePassword(): string
    {
        return Str::random(6);
    }
}
