<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SchoolController;
use Illuminate\Http\Request;

class IdlePlataformController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $S = SchoolController::myschool();
        return view('auth.idle_plataform', [
                'SCHOOL_name' => $S->name(),
                'SCHOOL_badge' => $S->badge()
            ]);
    }
}
