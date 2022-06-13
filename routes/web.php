<?php

use App\Http\Controllers\support\RoleController;
use App\Http\Controllers\support\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); })->name('dashboard');

    Route::get('insert_roles', [UserController::class, 'insert_roles']);
    Route::get('destroy_users', [UserController::class, 'destroy_users']);


    /* Route Users */
    Route::put('change-password', [ConfirmEmailController::class, 'change_password'])->name('support.users.password');
    Route::resource('users', UserController::class)->except('destroy','create','store')->names('support.users');
    Route::get('users.json', [UserController::class, 'data']);

    /* Route Roles */
    Route::resource('roles', RoleController::class)->except('destroy','show')->names('support.roles');
    Route::get('roles.json', [RoleController::class, 'data']);

    /* Route Profile */
    Route::get('profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('user.profile.update');

});


require __DIR__.'/auth.php';
