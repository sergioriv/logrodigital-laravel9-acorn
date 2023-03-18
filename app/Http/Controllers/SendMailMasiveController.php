<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Jobs\SentEmailTutor;
use App\Models\Group;
use App\Models\SentEmail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class SendMailMasiveController extends Controller
{
    public function forGroup(Request $request, Group $group)
    {
        $request->validate([
            'email_subject' => ['required', 'string', 'max:191'],
            'email_message' => ['required', 'string', 'max:3000']
        ]);

        $subject = trim($request->email_subject);
        $message = trim(nl2br($request->email_message));


        $students = Student::where('group_id', $group->id)
        ->with('myTutorIs')
        ->get();

        if ( empty($students) ) {
            Notify::fail('El grupo no tiene estudiantes');
            return back();
        }


        $jobs = [];
        $save = [];
        foreach ($students as $student) {

            if ( ! is_null($student->myTutorIs) ) {

                if ( ! is_null($student->myTutorIs->email) ) {


                    if ( ! is_null($student->myTutorIs->user->email_verified_at) ) {

                        /*
                         * Job donde se enviarán los correos para aquellos acudientes que verificaron su correo
                         * */
                        $jobs[] = new SentEmailTutor(
                            $subject,
                            $message,
                            $student->myTutorIs->name,
                            $student->myTutorIs->email
                        );

                        array_push($save, [
                            $student->myTutorIs->name,
                            $student->myTutorIs->email,
                            'Sent'
                        ]);
                    } else {

                        array_push($save, [
                            $student->myTutorIs->name,
                            $student->myTutorIs->email,
                            'Email not verified'
                        ]);
                    }
                }
            }
        }


        if (count($jobs))
            Bus::chain($jobs)->dispatch();


        $sent = SentEmail::create([
            'subject' => $subject,
            'message' => $message,
            'sentTo' => $save,
            'created_user_type' => UserController::myModelIs(),
            'created_user_id' => auth()->id()
        ]);

        return $sent;
        dd();
    }
}
