<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserAlertController;
use App\Models\Coordination;
use App\Models\Group;
use App\Models\Orientation;
use App\Models\PersonCharge;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentTrackingCoordination;
use App\Models\StudentTrackingTeacher;
use App\Models\TeacherSubjectGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;


class SmtpMail extends Controller
{

    protected $SCHOOL;

    protected $subject;
    protected $userName;
    protected $userEmail;

    public function __construct(School $school = null)
    {
        $this->SCHOOL = $school;
    }

    public static function init()
    {
        return new static(SchoolController::myschool()->getData());
    }

    public function sendEmailVerificationNotification(User $user)
    {
        if ( $user->email !== NULL )
        {

            $this->subject = Lang::get("Registration Notification");
            $this->userName = $user->name;
            $this->userEmail = $user->email;

            $verificationUrl = $this->verificationUrl($user);

            $content = (new ContentMail)
                ->title(Lang::get('Hi') .', '. $user->name)
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $verificationUrl)
                ->line(Lang::get('If you did not create an account, no further action is required.'))
                ->subcopy();

            return $this->send_email($content->toContent());
        }
        return false;
    }

    public function sendPasswordResetNotification(User $user, $token)
    {

        $this->subject = Lang::get("Reset Password Notification");
        $this->userName = $user->name;
        $this->userEmail = $user->email;

        $resetUrl = $this->resetUrl($user, $token);

        $content = (new ContentMail)
            ->title(Lang::get('Hi') .', '. $user->name)
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $resetUrl)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'))
            ->subcopy();

        $this->send_email($content->toContent());
    }

    public function sendEmailEnrollmentNotification(Student $student, Group $group)
    {
        if ($student->person_charge !== NULL) {
            /*  */
            $tutor = $student->myTutorIs;

            if ($tutor->user->email_verified_at !== NULL ) {

                $this->subject = Lang::get("Enrollment Notification");
                $this->userName = $tutor->name;
                $this->userEmail = $tutor->email;

                $content = (new ContentMail)
                    ->title(Lang::get('Hi') .', '. $tutor->name)
                    ->line(Lang::get('The student, :student, has been enrolled in the group :studyyear :groupname.', [
                        'student' => $student->getCompleteNames(),
                        'studyyear' => $group->studyYear->name,
                        'groupname' => $group->name
                    ]));

                $this->send_email($content->toContent());
            }
        }
    }

    public function sendCodeSecurityEmail($email, $code)
    {
        $this->subject = Lang::get("Security Code");
        $this->userName = $this->SCHOOL->name;
        $this->userEmail = $email;

        $content = (new ContentMail)
            ->title(Lang::get('Dear') .' '. $this->SCHOOL->name)
            ->line(Lang::get('Here is the code you need:'))
            ->line('<b>'.$code.'</b>')
            ->line(Lang::get('This email was generated because the request was made for the activation of this email for the security of your platform.'));

        return $this->send_email($content->toContent());
    }

    public function sendStudentRemovalCode(Student $student, $code)
    {
        $this->subject = Lang::get("Student Removal Code");
        $this->userName = $this->SCHOOL->name;
        $this->userEmail = $this->SCHOOL->security_email;

        $content = (new ContentMail)
            ->title(Lang::get('Dear') .' '. $this->SCHOOL->name)
            ->line('<hr>')
            ->line(Lang::get('Here is the code you need:'))
            ->line('<b>'.$code.'</b>')
            ->line(Lang::get('This code has been generated for the deletion of Student:'))
            ->line(Lang::get('names') .': <b>' . $student->getCompleteNames() . '</b><br />' . Lang::get('Document') .': <b>' . $student->document_type_code .' '. $student->document . '</b>');

        return $this->send_email($content->toContent());
    }

    public function sendChangeEmailCode($modelName, $code)
    {
        $this->subject = Lang::get("Authorization code for e-mail change");
        $this->userName = $this->SCHOOL->name;
        $this->userEmail = $this->SCHOOL->security_email;

        $content = (new ContentMail)
            ->title(Lang::get('Dear') .' '. $this->SCHOOL->name)
            ->line('<hr>')
            ->line(Lang::get('Here is the code you need:'))
            ->line('<b>'.$code.'</b>')
            ->line(Lang::get(
                "This code has been generated for the change of :NAME's e-mail address.",
                ['NAME' => '<b>' . $modelName . '</b>']
            ));

        return $this->send_email($content->toContent());
    }


    /* Mail Alert
     *
     * $recommendation es para las entradas que no vienen de un tracking,
     * como el reporte que hace el docente a orientacion
     *
     * */
    public function sendMailAlert($title, $to, $recommendation)
    {
        $this->subject = $title;


        if ($to instanceof Collection) {

            foreach ($to as $collection) {

                /*
                 *  SEND TO TEACHERS
                 *
                 *  */
                if ($collection instanceof TeacherSubjectGroup) {

                    $this->userName = $collection->teacher->getFullName();
                    $this->userEmail = $collection->teacher->institutional_email;

                    if ($collection->teacher->user->email_verified_at !== NULL) {

                        $content = (new ContentMail)
                            ->title(Lang::get('Hi') .', '. $collection->teacher->getFullName())
                            ->line($title)
                            ->line($recommendation);

                        $this->send_email($content->toContent());
                    }

                }

                /*
                 * SEND TO ORIENTATION
                 *
                 *  */
                if ($collection instanceof Orientation) {

                    $this->userName = $collection->getFullName();
                    $this->userEmail = $collection->email;

                    if ($collection->user->email_verified_at !== NULL) {

                        $content = (new ContentMail)
                            ->title(Lang::get('Hi') .', '. $collection->getFullName())
                            ->line($title)
                            ->line($recommendation);

                        $this->send_email($content->toContent());
                    }

                }

            }
        }


        if ($to instanceof Coordination) {

            $this->userName = $to->getFullName();
            $this->userEmail = $to->email;

            if ($to->user->email_verified_at !== NULL) {
                $content = (new ContentMail)
                        ->title(Lang::get('Hi') .', '. $to->getFullName())
                        ->line($title)
                        ->line($recommendation);

                return $this->send_email($content->toContent());
            }

        }

    }

    private function send_email($contentEmail)
    {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->CharSet = 'utf-8';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption');
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->Port = config('mail.mailers.smtp.port');
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            $mail->setFrom(config('mail.from.address'), $this->SCHOOL->name);
            $mail->Subject = $this->subject;
            $mail->MsgHTML($contentEmail);
            $mail->addAddress($this->userEmail, $this->userName);
            $mail->send();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    private function verificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 262800)), // 6 meses
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
    private function resetUrl($user, $token)
    {
        return url(route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ], false));
    }
}
