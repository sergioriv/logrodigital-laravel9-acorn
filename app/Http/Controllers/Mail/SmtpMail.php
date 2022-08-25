<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SchoolController;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SmtpMail extends Controller
{

    private static $subject;
    private static $userName;
    private static $userEmail;

    public static function sendEmailVerificationNotification(User $user)
    {

        static::$subject = Lang::get("Registration Notification");
        static::$userName = $user->name;
        static::$userEmail = $user->email;

        $verificationUrl = static::verificationUrl($user);

        $content = (new ContentMail)
            ->title( Lang::get('Hi') .', '. $user->name )
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('If you did not create an account, no further action is required.'))
            ->subcopy();

        static::send_email( $content->toContent() );
    }

    public static function sendPasswordResetNotification(User $user, $token)
    {

        static::$subject = Lang::get("Reset Password Notification");
        static::$userName = $user->name;
        static::$userEmail = $user->email;

        $resetUrl = static::resetUrl($user, $token);

        $content = (new ContentMail)
            ->title( Lang::get('Hi') .', '. $user->name )
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $resetUrl)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'))
            ->subcopy();

        static::send_email( $content->toContent() );
    }

    public static function sendEmailEnrollmentNotification(User $user, $student, $studyyear, $groupname)
    {

        static::$subject = Lang::get("Enrollment Notification");
        static::$userName = $user->name;
        static::$userEmail = $user->email;

        $content = (new ContentMail)
            ->title( Lang::get('Hi') .', '. $user->name )
            ->line(Lang::get('The student, :student, has been enrolled in the group :studyyear :groupname.', [
                'student' => $student,
                'studyyear' => $studyyear,
                'groupname' => $groupname
            ]));

        static::send_email( $content->toContent() );
    }

    private static function send_email( $contentEmail )
    {

        $mail = new PHPMailer(true);
        $schoolName = (new SchoolController)->name();

        try {
            $mail->isSMTP();
            $mail->CharSet = 'utf-8';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption');
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->Port = config('mail.mailers.smtp.port');
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            $mail->setFrom(config('mail.from.address'), $schoolName);
            $mail->Subject = static::$subject;
            $mail->MsgHTML($contentEmail);
            $mail->addAddress(static::$userEmail, static::$userName);
            $mail->send();
        } catch (Exception $e) {
            dd($e);
        }
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    private static function verificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @return string
     */
    private static function resetUrl($user, $token)
    {
        return url(route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ], false));
    }
}
