<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        $user = User::findOrFail($request->id);
        if($user) {

            Auth::login($user);

            if ($request->user()->hasVerifiedEmail()) {
                // return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
                return redirect('/confirm-email');
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            // return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
            return redirect('/confirm-email');
        }
        return redirect('/?fail=1');
    }
}
