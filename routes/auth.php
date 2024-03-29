<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\IdlePlataformController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RestoreYourPasswordController;
use App\Http\Controllers\Auth\UserInactiveController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('/', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('/', [AuthenticatedSessionController::class, 'store']);

    Route::get('microsoft', [AuthenticatedSessionController::class, 'microsoft_redirect']);
    Route::get('microsoft/callback', [AuthenticatedSessionController::class, 'microsoft_callback']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');

    Route::get('inactive', [UserInactiveController::class, '__invoke'])
                ->name('inactive.notice');

    Route::get('idle_plataform', [IdlePlataformController::class, '__invoke'])
                ->name('inactive.plataform');
});

Route::middleware('auth')->group(function () {

    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send'); // reenviar correo de verificación

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');




    /*
     *
     * Restore Password
     *
     *  */

    /* Verifica si la cuenta ya ha sido confirmada */
    Route::get('confirm-email', [ConfirmEmailController::class, 'show']);

    /* Asigna la contraseña luego de confirmar el correo */
    Route::put('change-password', [ConfirmEmailController::class, 'change_password'])->name('support.users.password');

    Route::get('restore-password', [RestoreYourPasswordController::class, 'show'])
                ->name('user.changedPassword');
    Route::patch('restore-password', [RestoreYourPasswordController::class, 'verified'])
                ->name('user.changedPassword.verified');
});

Route::withoutMiddleware(['guest','auth'])->group(function () {
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed'])
                ->name('verification.verify');
});
