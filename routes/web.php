<?php

use App\Http\Controllers\Auth\ConfirmEmailController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HeadquartersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceAreaController;
use App\Http\Controllers\ResourceSubjectController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudyTimeController;
use App\Http\Controllers\StudyYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\support\RoleController;
use App\Http\Controllers\support\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherSubjectGroupController;
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


    /* Route School Year */
    Route::resource('school_years', SchoolYearController::class)->except('destroy','edit','update')->names('schoolYear');
    Route::get('school_years.json', [SchoolYearController::class, 'data']);

    /* Route Headquarters */
    Route::resource('headquarters', HeadquartersController::class)->except('destroy')->names('headquarters');
    Route::get('headquarters.json', [HeadquartersController::class, 'data']);

    /* Route StudyTime */
    Route::resource('study_times', StudyTimeController::class)->except('destroy')->names('studyTime');
    Route::get('study_times.json', [StudyTimeController::class, 'data']);

    /* Route StudyTime */
    Route::resource('study_years', StudyYearController::class)->only('index')->names('studyYear');
    Route::get('study_years/{study_year}/subjects', [StudyYearController::class, 'subjects']);
    Route::post('study_years/{study_year}/subjects', [StudyYearController::class, 'subjects_store'])->name('studyYear.subject.store');
    Route::get('study_years.json', [StudyYearController::class, 'data']);

    /* Route Resource areas */
    Route::resource('areas', ResourceAreaController::class)->except('destroy')->names('resourceArea');
    Route::get('areas.json', [ResourceAreaController::class, 'data']);

    /* Route Resource subject */
    Route::resource('subjects', ResourceSubjectController::class)->except('destroy','update','edit','show')->names('resourceSubject');
    Route::get('subjects.json', [ResourceSubjectController::class, 'data']);

    /* Route Areas & subjects */
    Route::resource('areas_subjects', SubjectController::class)->except('destroy','show')->names('subject');
    // Route::get('areas_subjects.json', [SubjectController::class, 'data']);

    /* Route Teachers */
    Route::controller(TeacherController::class)->group( function () {
        Route::get('teachers.json', 'data');
        Route::get('teachers/export', 'export')->name('teacher.export');
        Route::get('teachers/import', 'import')->name('teacher.import');
        Route::post('teachers/import', 'import_store')->name('teacher.import');
    });
    Route::resource('teachers', TeacherController::class)->except('destroy')->names('teacher');

    /* Route Groups */
    Route::resource('groups', GroupController::class)->except('destroy','edit','update')->names('group');
    Route::get('groups.filter', [GroupController::class, 'filter']);
    Route::get('groups/{group}/teachers', [GroupController::class, 'teacher_edit'])->name('group.teachers.edit');
    Route::put('groups/{group}/teachers', [GroupController::class, 'teacher_update'])->name('group.teachers.update');

    /* Route TeacherSubjectGroups */
    Route::resource('teachers/{teacher}/subjects', TeacherSubjectGroupController::class)->names('teacher.subjects');

});


require __DIR__.'/auth.php';
