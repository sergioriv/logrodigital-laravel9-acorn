<?php

use App\Http\Controllers\Auth\ConfirmEmailController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HeadquartersController;
use App\Http\Controllers\Mail\ContentMail;
use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\PersonChargeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceAreaController;
use App\Http\Controllers\ResourceSubjectController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFileController;
use App\Http\Controllers\StudyTimeController;
use App\Http\Controllers\StudyYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\support\RoleController;
use App\Http\Controllers\support\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherSubjectGroupController;
use App\Models\Group;
use App\Models\Student;
use App\Models\User;
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
    Route::get('permissions-reset', function() {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->back();
    })->middleware('can:support.access');

    /* Route Users */
    Route::put('change-password', [ConfirmEmailController::class, 'change_password'])->name('support.users.password');
    Route::resource('users', UserController::class)->except('destroy','create','store')->names('support.users');
    Route::get('users.json', [UserController::class, 'data']);

    /* Route Roles */
    Route::resource('roles', RoleController::class)->except('destroy','show')->names('support.roles');
    Route::get('roles.json', [RoleController::class, 'data']);

    /* Route Profile */
    Route::get('profile', [ProfileController::class, 'edit'])->name('user.profile');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::put('profile/{user}/avatar', [ProfileController::class, 'update_avatar'])->name('user.profile.avatar');
    Route::put('profile/documents', [StudentController::class, 'wizard_documents_request'])->name('student.wizard.documents');
    Route::put('profile/person_charge', [StudentController::class, 'wizard_person_charge_request'])->name('student.wizard.person-charge');
    Route::put('profile/personal_info', [StudentController::class, 'wizard_personal_info_request'])->name('student.wizard.personal-info');
    Route::put('profile/edit', [StudentController::class, 'wizard_complete_request'])->name('student.wizard.complete');


    /* Route School Year */
    Route::resource('school_years', SchoolYearController::class)->except('destroy','edit','update')->names('schoolYear');
    // Route::get('school_years.json', [SchoolYearController::class, 'data']);
    Route::put('school_years', [SchoolYearController::class, 'choose'])->name('schoolYear.selected');

    /* Route Headquarters */
    Route::resource('headquarters', HeadquartersController::class)->except('destroy')->names('headquarters');
    Route::get('headquarters.json', [HeadquartersController::class, 'data']);

    /* Route StudyTime */
    Route::resource('study_times', StudyTimeController::class)->except('destroy')->names('studyTime');
    Route::get('study_times.json', [StudyTimeController::class, 'data']);
    Route::put('study_times/{study_time}/periods', [StudyTimeController::class, 'periods_update'])->name('periods.update');

    /* Route StudyYear */
    Route::resource('study_years', StudyYearController::class)->only('index')->names('studyYear');
    Route::get('study_years/{study_year}/subjects', [StudyYearController::class, 'subjects'])->name('studyYear.subject.show');
    Route::post('study_years/{study_year}/subjects', [StudyYearController::class, 'subjects_store'])->name('studyYear.subject.store');
    Route::get('study_years/{study_year}/subjects/edit', [StudyYearController::class, 'subjects_edit'])->name('studyYear.subject.edit');

    /* Route Resource areas */
    Route::resource('areas', ResourceAreaController::class)->except('destroy')->names('resourceArea');
    Route::get('areas.json', [ResourceAreaController::class, 'data']);

    /* Route Resource subject */
    Route::resource('subjects', ResourceSubjectController::class)->only('index','create','store')->names('resourceSubject');
    Route::get('subjects.json', [ResourceSubjectController::class, 'data']);

    /* Route Areas & subjects */
    Route::resource('areas_subjects', SubjectController::class)->except('destroy','show')->names('subject');

    /* Route Teachers */
    Route::controller(TeacherController::class)->group( function () {
        Route::get('teachers.json', 'data');
        Route::get('teachers/export', 'export')->name('teacher.export');
        Route::get('teachers/import', 'import')->name('teacher.import');
        Route::post('teachers/import', 'import_store')->name('teacher.import');
    });
    Route::resource('teachers', TeacherController::class)->except('destroy')->names('teacher');

    /* Route Groups */
    Route::resource('groups', GroupController::class)->except('destroy')->names('group');
    Route::get('groups.filter', [GroupController::class, 'filter']);
    Route::get('groups/{group}/teachers', [GroupController::class, 'teacher_edit'])->name('group.teachers.edit');
    Route::put('groups/{group}/teachers', [GroupController::class, 'teacher_update'])->name('group.teachers.update');
    Route::get('groups/{group}/matriculate', [GroupController::class, 'matriculate'])->name('group.matriculate');
    Route::put('groups/{group}/matriculate', [GroupController::class, 'matriculate_update'])->name('group.matriculate.update');

    /* Route TeacherSubjectGroups */
    Route::resource('teachers/{teacher}/subjects', TeacherSubjectGroupController::class)->names('teacher.subjects');

    /* Route Students */
    Route::controller(StudentController::class)->group( function () {

        Route::get('students/create', 'create')->name('students.create');
        Route::post('students/no-enrolled', 'store')->name('students.store');
        Route::get('students/{student}/edit', 'show')->name('students.show');
        Route::put('students/{student}', 'update')->name('students.update');
        Route::put('students/{student}/psychosocial', 'psychosocial_update')->name('students.psychosocial.update');

        Route::get('students/no-enrolled', 'no_enrolled')->name('students.no_enrolled');
        Route::get('students', 'enrolled')->name('students.enrolled');

        Route::get('students/data/instructive', 'data_instructive')->name('students.data.instructive');
        Route::get('students/export/instructive', 'export_instructive')->name('students.instructive');
        Route::get('students/import', 'import')->name('students.import');
        Route::post('students/import', 'import_store')->name('students.import');

        Route::put('students/{student}/piar', 'piar_update')->name('students.piar');

        Route::get('students/{id}/matriculate', 'matriculate')->name('students.matriculate');
        Route::put('students/{student}/matriculate', 'matriculate_update')->name('students.matriculate.update');

        Route::get('students/parents.filter','create_parents_filter');

    });


    Route::put('persons_charge/{student}', [PersonChargeController::class, 'update'])->name('personsCharge');
    Route::put('student/{student}/files/', [StudentFileController::class, 'update'])->name('studentFile');
    Route::put('student/{student}/files/checked', [StudentFileController::class, 'checked'])->name('studentFile.checked');

});
Route::get('mail/{id}', function ($id) {
    $user = User::find($id);
    SmtpMail::sendEmailVerificationNotification($user);
});

require __DIR__.'/auth.php';
