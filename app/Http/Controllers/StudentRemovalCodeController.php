<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Models\Student;
use App\Models\StudentRemovalCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentRemovalCodeController extends Controller
{
    protected static $code;
    protected static $student;

    public static function generate(Student $student)
    {
        $studentCodes = StudentRemovalCode::where('student_id', $student->id);
        if ($studentCodes->count())
        {
            if ($studentCodes->firstOrFail()->addMinutes() > now())
            {
                $diff = Carbon::now()->diffInMinutes($studentCodes->first()->addMinutes());
                return __("Must wait :minutes minutes to send another code.", ['minutes' => $diff]);
            }

            $studentCodes->delete();
        }


        static::$student = $student;
        static::$code = Str::upper(Str::random(6));

        if (static::sendMail())
            static::save();
        else
            return __('Unexpected Error');

        return true;
    }

    private static function save()
    {
        (new StudentRemovalCode())->forceFill([
            'student_id' => static::$student->id,
            'code' => static::$code,
            'user_id' => Auth::user()->id,
            'created_at' => now()
        ])->save();
    }

    private static function sendMail()
    {
        return SmtpMail::sendStudentRemovalCode(static::$student, static::$code);
    }

}
