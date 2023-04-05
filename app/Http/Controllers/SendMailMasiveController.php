<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Group;
use App\Models\SentEmail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendMailMasiveController extends Controller
{
    private $subject;
    private $message;

    public function index()
    {
        $mails = SentEmail::where('created_user_type', UserController::myModelIs())
            ->where('created_user_id', auth()->id())
            ->get();

        return view('logro.mails-sent.index', [
            'mails' => $mails
        ]);
    }

    public function log(SentEmail $mail)
    {
        $content = '<table class="table table-striped mb-0"><tbody>';

        if ($mail->sentTo) {

            foreach ($mail->sentTo as $student_id => $data) {
                $student = Student::select('id', 'first_name', 'second_name', 'first_last_name', 'second_last_name')
                    ->where('id', $student_id)->first();

                $content .= '<tr>';
                $content .= '<td scope="row">' . $student->getCompleteNames() . '</td>';

                if ( is_array($data) ) {
                    $content .= '<td>' . $data[0] . '</td>';
                    $content .= '<td>' . $data[1] . '</td>';
                    $content .= '<td>' . $this->tag($data[2]) . '</td>';
                } else {
                    $content .= '<td colspan="3">' . $this->tag($data) . '</td>';
                }

                $content .= '</tr>';
            }
        } else {
            $content .= '<tr><td scope="row">No hay registro</td></tr>';
        }

        $content .= '</tbody></table>';

        return ['content' => $content];
    }

    private function tag($status)
    {
        return match ($status) {
            'NT' => '<div class="badge bg-outline-danger">Sin acudiente</div>',
            'NC' => '<div class="badge bg-outline-danger">Sin correo</div>',
            'NV' => '<div class="badge bg-outline-danger">Sin verificar</div>',
            'SF' => '<div class="badge bg-outline-danger">Falló al enviar</div>',
            'S' => '<div class="badge bg-outline-success">Enviado</div>',
            default => ''
        };
    }

    public function show(SentEmail $mail)
    {
        dd($mail);
    }

    public function forGroup(Request $request, Group $group)
    {
        $request->validate([
            'email_subject' => ['required', 'string', 'max:191'],
            'email_message' => ['required', 'string', 'max:3000']
        ]);

        $this->subject = trim($request->email_subject);
        $this->message = trim(nl2br($request->email_message));


        $students = Student::where('group_id', $group->id)
        ->with('myTutorIs')
        ->get();

        if ( empty($students) ) {
            Notify::fail('El grupo no tiene estudiantes');
            return back();
        }


        $toSave = $this->students($students);


        try {
            SentEmail::create([
                'subject' => $this->subject,
                'message' => $this->message,
                'sentTo' => $toSave,
                'created_user_type' => UserController::myModelIs(),
                'created_user_id' => auth()->id()
            ]);
        } catch (\Throwable $th) {
            Notify::fail(__('An error has occurred'));
            return back();
        }

        Notify::success(__('Mail send!'));
        return back();
    }

    private function students($students): array
    {
        $toSave = [];
        foreach ($students as $student) {

            if ( is_null($student->myTutorIs) ) {

                $toSave[$student->id] = 'NT'; //'El estudiante no tiene un tutor registrado';

            } else {

                if ( is_null($student->myTutorIs->email) ) {

                    $toSave[$student->id] = [
                        $student->myTutorIs->name,
                        $student->myTutorIs->email,
                        'NC' //'El tutor no tiene un correo electrónico registrado'
                    ];

                } else {

                    if ( is_null($student->myTutorIs->user->email_verified_at) ) {

                        $toSave[$student->id] = [
                            $student->myTutorIs->name,
                            $student->myTutorIs->email,
                            'NV' //'El tutor no ha verificado su correo electrónico'
                        ];

                    } else {

                        /*
                         * se enviarán los correos para aquellos acudientes que verificaron su correo
                         * */
                        $sendMail = SmtpMail::init()->mailToTutor(
                            $this->subject,
                            $this->message,
                            $student->myTutorIs->name,
                            $student->myTutorIs->email
                        );

                        $toSave[$student->id] = [
                            $student->myTutorIs->name,
                            $student->myTutorIs->email,
                            $sendMail === TRUE ? 'S' : 'SF'
                        ];

                    }
                }
            }
        }

        return $toSave;
    }
}
