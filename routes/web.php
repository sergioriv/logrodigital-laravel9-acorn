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


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); })->name('dashboard');
    Route::view('/vertical', 'vertical')->name('vertical');
    Route::view('/horizontal', 'horizontal')->name('horizontal');
    // Route::get('register', [RegisteredUserController::class, 'create'])->name('auth.register');
    // Route::post('register', [RegisteredUserController::class, 'store']);

    /* Route Users */
    Route::resource('users', UserController::class)->except('destroy')->names('support.users');
    Route::get('users.json', [UserController::class, 'data']);
    Route::get('insert_roles', [UserController::class, 'insert_roles']);
    Route::get('destroy_users', [UserController::class, 'destroy_users']);

    /* Route Roles */
    Route::resource('roles', RoleController::class)->except('destroy')->names('support.roles');
    Route::get('roles.json', [RoleController::class, 'data']);

});


require __DIR__.'/auth.php';
