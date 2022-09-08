<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // $diff = Carbon::create($auth->email_verified_at)->diffInMinutes(Carbon::now());

        if ( NULL === $auth->password )
        {
            return view('auth.confirm-email')->with('status', 'password');
        }
        else
        {
            Auth::logout();
            return view('auth.confirm-email')->with('status', 'fail');
        }

        /* if ( in_array($auth->provider, $this->providers ) && $diff <= 2 )
        {
            return view('auth.confirm-email')->with('status', 'provider');
        }
        elseif ( NULL === $auth->password && NULL === $auth->provider )
        {
            return view('auth.confirm-email')->with('status', 'password');
        }
        else
        {
            Auth::logout();
            return view('auth.confirm-email')->with('status', 'fail');
        } */
    }

    public function change_password(Request $request)
    {

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::min(6)],
        ]);

        $user = User::find(Auth::user()->id);

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        Notify::success('Welcome ' . $user->name);
        return redirect()->route('dashboard');
    }
}
