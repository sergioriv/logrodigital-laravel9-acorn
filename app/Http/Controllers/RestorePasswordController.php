<?php

namespace App\Http\Controllers;

use App\Models\Student;
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

        $newPassword = Str::random(6);


        if ($request->role === 'student') {

            $student = Student::find($request->id);

            if ( ! is_null($student) ) {

                $student->user->forceFill([
                    'password' => Hash::make($newPassword),
                    'change_password' => 0,
                    'remember_token' => NULL
                ])->save();

                $return = '<div class="mb-2">' . __('Email') . ": {$student->institutional_email}</div>";
                $return .= '<div>' . __('Temporary password') . ": <div class='font-weight-bold d-inline-block readable-text h4 m-0'>{$newPassword}</div></div>";

                return $return;
            }

        }
    }
}
