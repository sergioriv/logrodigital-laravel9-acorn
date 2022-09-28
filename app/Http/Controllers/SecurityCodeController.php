<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Models\SecurityCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SecurityCodeController extends Controller
{
    protected static $code;
    protected static $email;

    public static function generate($email)
    {
        if (SecurityCode::count())
        {
            if (SecurityCode::firstOrFail()->addMinutes() > now())
            {
                $diff = Carbon::now()->diffInMinutes(SecurityCode::first()->addMinutes());
                return __("Must wait :minutes minutes to send another code.", ['minutes' => $diff]);
            }
        }

        SecurityCode::query()->delete();

        static::$email = $email;
        static::$code = Str::upper(Str::random(6));

        if (static::sendMail())
            static::save();
        else
            return __('Invalid email (:email)', ['email' => $email]);

        return true;
    }

    private static function save()
    {
        (new SecurityCode)->forceFill([
            'email' => static::$email,
            'code' => static::$code,
            'user_id' => Auth::user()->id,
            'created_at' => now()
        ])->save();
    }

    private static function sendMail()
    {
        return SmtpMail::sendCodeSecurityEmail(static::$email, static::$code);
    }

    public static function mySecurityEmail()
    {
        return SecurityCode::first() ?? null;
    }
}
