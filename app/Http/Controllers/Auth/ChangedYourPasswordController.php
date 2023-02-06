<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\support\Notify;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class ChangedYourPasswordController extends Controller
{
    public function show()
    {
        $S = SchoolController::myschool();
        return view('auth.changed-your-password', [
            'SCHOOL_name' => $S->name(),
            'SCHOOL_badge' => $S->badge(),
        ]);
    }

    public function verified(Request $request)
    {
        // dd($request);

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::min(6)]
        ]);

        if ( ! Hash::check($request->current_password, Auth::user()->password) ) {

            Notify::fail(__('Current password does not match'));
            return back();
        }

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
