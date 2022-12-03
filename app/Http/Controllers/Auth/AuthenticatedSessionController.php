<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->login_redirect();
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function microsoft_redirect()
    {
        return Socialite::driver('azure')->redirect();
    }

    public function microsoft_callback(Request $request)
    {

        dd($request->error);

        try {
            $microsoft = Socialite::driver('azure')->stateless()->user();
        } catch (InvalidStateException $e) {
            $microsoft = Socialite::driver('azure')->stateless()->user();
        }

        if ($microsoft->error) {
            return redirect()->route('login')->withErrors(__('Error when logging in'));
        }

        $user = User::where('email', $microsoft->email)->first();

        if ( $user )
        {
            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();

                event(new Verified($user));
            }

            $user->forceFill([
                'remember_token' => $microsoft->token
            ])->save();

            Auth::login($user);
            return $this->login_redirect();
        }
        else
        {
            return redirect()->route('login')->withErrors( $microsoft->email .' '. __("unregistered on our platform"));
        }
    }

    /* public function microsoft_logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->flush();
        $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
        return redirect($azureLogoutUrl);
    } */


    private function login_redirect()
    {
        Notify::welcome(__('Welcome to Logro Digital!'));
        switch ( UserController::role_auth() ) :
            case 'STUDENT':
                return redirect()->intended(RouteServiceProvider::PROFILE);
                break;

            default:
                return redirect()->intended(RouteServiceProvider::HOME);
                break;
        endswitch;
    }
}
