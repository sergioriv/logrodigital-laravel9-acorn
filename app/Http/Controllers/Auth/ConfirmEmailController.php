<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;


class ConfirmEmailController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $auth = Auth::user();

        $diff = Carbon::create($auth->email_verified_at)->diffInMinutes(Carbon::now());

        if ($diff <= 2)
        {
            return view('auth.confirm-email')->with('status', 'success');
        } else
        {
            Auth::logout();
            return view('auth.confirm-email')->with('status', 'fail');

        }



    }
}
