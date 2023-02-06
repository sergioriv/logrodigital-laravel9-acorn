<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\support\Notify;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;


class ConfirmEmailController extends Controller
{
    private $providers = ['microsoft'];
    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $auth = Auth::user();
        $SCHOOL = SchoolController::myschool();

        if ( NULL === $auth->password )
        {
            return view('auth.confirm-email', [
                'SCHOOL_name' => $SCHOOL->name(),
                'SCHOOL_badge' => $SCHOOL->badge(),
                'status' => 'password'
            ]);
        }
        else
        {
            Auth::logout();
            return view('auth.confirm-email', [
                'SCHOOL_name' => $SCHOOL->name(),
                'SCHOOL_badge' => $SCHOOL->badge(),
                'status' => 'fail'
            ]);
        }
    }

    public function change_password(Request $request)
    {

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::min(6)],
        ]);

        $user = auth()->user();

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'change_password' => 1
        ])->save();

        event(new PasswordReset($user));

        return (new AuthenticatedSessionController)->redirect();
    }
}
