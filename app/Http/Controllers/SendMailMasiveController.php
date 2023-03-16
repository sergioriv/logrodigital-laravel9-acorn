<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;

class SendMailMasiveController extends Controller
{
    public function forGroup(Group $group)
    {
        $students = Student::where('group_id', $group->id)
        ->with('myTutorIs')
        ->get();

        $sentTo = [];
        foreach ($students as $student) {

            if ( ! is_null($student->myTutorIs) ) {

                if ( ! is_null($student->myTutorIs->email) ) {


                    if ( ! is_null($student->myTutorIs->user->email_verified_at) ) {

                        /* Correos a los que se enviará correo */
                        array_push($sentTo, [
                            $student->myTutorIs->name, $student->myTutorIs->email, 'POR ENVIAR'
                        ]);
                    } else {

                        array_push($sentTo, [
                            $student->myTutorIs->name, $student->myTutorIs->email, 'CORREO NO VERIFICADO'
                        ]);
                    }
                }
            }
        }

        return $sentTo;

    }
}
