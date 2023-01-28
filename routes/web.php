<?php

use App\Http\Controllers\AttendanceStudentController;
use App\Http\Controllers\Auth\ConfirmEmailController;
use App\Http\Controllers\CoordinationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DescriptorController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HeadquartersController;
use App\Http\Controllers\OrientationController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PeriodPermitController;
use App\Http\Controllers\PersonChargeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RemarkController;
use App\Http\Controllers\ResourceAreaController;
use App\Http\Controllers\ResourceSubjectController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SecretariatController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFileController;
use App\Http\Controllers\StudentReportBookController;
use App\Http\Controllers\StudentTrackingController;
use App\Http\Controllers\StudyTimeController;
use App\Http\Controllers\StudyYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\RoleController;
use App\Http\Controllers\support\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherDegreeController;
use App\Http\Controllers\TeacherEmploymentHistoryController;
use App\Http\Controllers\TeacherHierarchyController;
use App\Http\Controllers\TeacherPermitController;
use App\Http\Controllers\TeacherSubjectGroupController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserAlertController;
use App\Models\Grade;
use App\Models\Student;
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


Route::middleware(['auth', 'active'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'show'])->name('dashboard');


    /* ACCESS SUPPORT */
    Route::middleware('can:support.access')->group(function () {

        /* RESET PERMISSIONS */
        Route::get('permissions-reset', function() {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            return redirect()->back();
        });

        Route::get('grades-reset', function () {
            Grade::getQuery()->delete();
            Notify::success('Hecho');
            return redirect()->route('dashboard');
        });

        Route::get('fix-students-retired', function () {
            $students = Student::where('status', 'retired')->get();
            foreach ($students as $student) {
                $student->user->forceFill([
                    'active' => 0
                ])->save();
            }

            dd($students);
        });


        /* Route Users */
        Route::resource('users', UserController::class)->except('destroy','create','store')->names('support.users');
        Route::get('users.json', [UserController::class, 'data']);

        /* Route Roles */
        Route::resource('roles', RoleController::class)->except('destroy','show')->names('support.roles');
        Route::get('roles.json', [RoleController::class, 'data']);

        /* Students Import */
        Route::get('students/import', [StudentController::class, 'import']);
        Route::post('students/import', [StudentController::class, 'import_store'])->name('students.import.store');

        /* Teacher Import */
        Route::get('teachers/import', [TeacherController::class, 'import']);
        Route::post('teachers/import', [TeacherController::class, 'import_store'])->name('teacher.import.store');

        /* Route Number Students */
        Route::get('number-students', [SchoolController::class, 'number_students_show'])->name('support.number_students');
        Route::put('number-students', [SchoolController::class, 'number_students_update'])->name('support.number_students.update');
    });

    /* Asigna la contraseña luego de confirmar el correo */
    Route::put('change-password', [ConfirmEmailController::class, 'change_password'])->name('support.users.password');


    /* Route Profile */
    Route::get('profile', [ProfileController::class, 'edit'])->name('user.profile');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::put('profile/{user}/avatar', [ProfileController::class, 'update_avatar'])->name('user.profile.avatar');
    Route::put('profile/documents', [StudentController::class, 'wizard_documents_request'])->name('student.wizard.documents');
    Route::put('profile/report-books', [StudentController::class, 'wizard_report_books_request'])->name('student.wizard.reportBooks');
    Route::put('profile/person_charge', [StudentController::class, 'wizard_person_charge_request'])->name('student.wizard.person-charge');
    Route::put('profile/personal_info', [StudentController::class, 'wizard_personal_info_request'])->name('student.wizard.personal-info');
    Route::put('profile/edit', [StudentController::class, 'wizard_complete_request'])->name('student.wizard.complete');
    Route::get('profile/download/matriculate', [StudentController::class, 'pdf_matriculate'])->name('student.pdf.matriculate');
    Route::get('profile/avatar/edit', [ProfileController::class, 'auth_avatar_edit'])->name('profile.auth.avatar.edit');
    Route::put('profile/avatar/edit', [ProfileController::class, 'auth_avatar_edit_update'])->name('profile.auth.avatar.update');

    /* My Institution */
    Route::get('myinstitution', [SchoolController::class, 'show'])->name('myinstitution');
    Route::put('myinstitution', [SchoolController::class, 'update'])->name('myinstitution.update');
    Route::get('myinstitution/send-confirmation', [SchoolController::class, 'sendConfirmationEmail']);
    Route::patch('myinstitution', [SchoolController::class, 'security_email'])->name('myinstitution.security.email');
    Route::patch('myinstitution/signature', [SchoolController::class, 'signature_rector'])->name('myinstitution.security.signature');

    /* Route School Year */
    Route::resource('school-years', SchoolYearController::class)->except('destroy','edit','update')->names('schoolYear');
    Route::put('school-years', [SchoolYearController::class, 'choose'])->name('schoolYear.selected');

    /* Route Headquarters */
    Route::resource('headquarters', HeadquartersController::class)->except('destroy')->names('headquarters');
    Route::get('headquarters.json', [HeadquartersController::class, 'data']);

    /* Route StudyTime */
    Route::resource('study-times', StudyTimeController::class)->except('destroy','edit','update')->names('studyTime');
    Route::get('study-times/{study_time}/periods', [PeriodController::class, 'create'])->name('studyTime.periods');
    Route::post('study-times/{study_time}/periods', [PeriodController::class, 'store'])->name('studyTime.periods.store');
    Route::get('study-times/{study_time}/periods/edit', [PeriodController::class, 'edit'])->name('studyTime.periods.edit');

    /* Route StudyYear */
    Route::resource('study-years', StudyYearController::class)->except('destroy')->names('studyYear');
    Route::get('study-years/{study_year}/subjects', [StudyYearController::class, 'subjects'])->name('studyYear.subject.show');
    Route::post('study-years/{study_year}/subjects', [StudyYearController::class, 'subjects_store'])->name('studyYear.subject.store');
    Route::get('study-years/{study_year}/subjects/edit', [StudyYearController::class, 'subjects_edit'])->name('studyYear.subject.edit');

    /* Route Resource areas */
    Route::resource('areas', ResourceAreaController::class)->except('destroy')->names('resourceArea');
    Route::get('areas.json', [ResourceAreaController::class, 'data']);

    /* Route Resource subject */
    Route::resource('subjects', ResourceSubjectController::class)->only('index','create','store')->names('resourceSubject');
    Route::get('subjects.json', [ResourceSubjectController::class, 'data']);

    /* Route Areas & subjects */
    Route::resource('areas-subjects', SubjectController::class)->only('index', 'store')->names('subject');

    /* Reoute Areas & Subjects of Specialties */
    Route::resource('specialties', SpecialtyController::class)->only('index', 'store')->names('specialties');

    /* Route Teachers */
    Route::controller(TeacherController::class)->group( function () {
        Route::get('teachers/export', 'export')->name('teacher.export');
        Route::get('teachers/instructive', 'export_instructive')->name('teachers.instructive');
    });
    Route::resource('teachers', TeacherController::class)->except('destroy','index','edit','update')->names('teacher');
    Route::post('teachers/{teacher}/permit', [TeacherPermitController::class, 'store'])->name('teachers.permits.store');



    /* Route Secretariat */
    Route::resource('secretariat', SecretariatController::class)->only('create','store')->names('secreatariat');



    /* Route Coordination */
    Route::resource('coordination', CoordinationController::class)->only('create','store')->names('coordination');



    /* Route Orientation */
    Route::resource('orientation', OrientationController::class)->only('create','store')->names('orientation');



    /* Route Groups */
    Route::resource('groups', GroupController::class)->except('destroy')->names('group');
    Route::get('groups.filter', [GroupController::class, 'filter']);
    Route::get('groups/{group}/teachers', [GroupController::class, 'teacher_edit'])->name('group.teachers.edit');
    Route::put('groups/{group}/teachers', [GroupController::class, 'teacher_update'])->name('group.teachers.update');
    Route::get('groups/{group}/matriculate', [GroupController::class, 'matriculate'])->name('group.matriculate');
    Route::put('groups/{group}/matriculate', [GroupController::class, 'matriculate_update'])->name('group.matriculate.update');
    Route::get('groups/{group}/specialty', [GroupController::class, 'specialty'])->name('group.specialty');
    Route::post('groups/{group}/specialty', [GroupController::class, 'specialty_store'])->name('group.specialty.store');
    Route::get('group/{group}/export-student-list', [GroupController::class, 'exportStudentList'])->name('group.export.student-list');
    Route::get('group/{group}/export-information-student-list', [GroupController::class, 'exportStudentsWithFiles'])->name('group.export.information-student-list');
    Route::get('group/{group}/report-notes', [GradeController::class, 'reportForPeriod']);
    Route::delete('groups/{group}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::get('groups/{group}/transfer-students', [TransferController::class, 'groupStudents'])->name('group.transfer-students');
    Route::put('groups/{group}/transfer-students', [TransferController::class, 'groupStudents_selection'])->name('group.transfer-students.select-students');
    Route::post('groups/{group}/transfer-students', [TransferController::class, 'groupStudents_hss'])->name('group.transfer-students.hss');
    Route::post('groups/transfer-sel-group', [TransferController::class, 'selectionGroup'])->name('group.transfer-students.selGroup');
    Route::post('groups/{group}/grade-report', [GradeController::class, 'reportForGroup'])->name('group.reportGrade');


    /* Remarks */
    Route::post('group/{group}/remark-students', [RemarkController::class, 'store'])->name('remark.store');


    /* Descriptors */
    Route::get('subject/{subject}/descriptors', [DescriptorController::class, 'index'])->name('subject.descriptors');
    Route::get('subject/{subject}/descriptors/create', [DescriptorController::class, 'create'])->name('subject.descriptors.create');
    Route::post('subject/{subject}/descriptors', [DescriptorController::class, 'store'])->name('subject.descriptors.store');


    /* Permit Period */
    Route::post('subject/permit', [PeriodPermitController::class, 'store'])->name('period.permit');


    /* Qualification */
    Route::post('mysubjects/{subject}/qualify', [GradeController::class, 'store'])->name('subject.qualify.students');




    /* Route TeacherSubjectGroups */
    Route::resource('teachers/{teacher}/subjects', TeacherSubjectGroupController::class)->names('teacher.subjects');
    Route::post('teachers/hierarchy', [TeacherHierarchyController::class, 'store'])->name('teacher.hierarchy.store');
    Route::post('teachers/degree', [TeacherDegreeController::class, 'store'])->name('teacher.degree.store');
    Route::post('teachers/employment', [TeacherEmploymentHistoryController::class, 'store'])->name('teacher.employment.store');




    /* Route Teacher User */
    Route::get('mysubjects', [TeacherController::class, 'mysubjects'])->name('teacher.my.subjects');
    Route::get('mysubjects/{subject}', [TeacherController::class, 'mysubjects_show'])->name('teacher.my.subjects.show');
    Route::post('attendance/{subject}', [AttendanceStudentController::class, 'subject'])->name('attendance.subject');
    Route::get('attendance/absences', [AttendanceStudentController::class, 'absences_view']);


    /* Route Students */
    Route::controller(StudentController::class)->group( function () {

        Route::get('students/create', 'create')->name('students.create');
        Route::post('students/no-enrolled', 'store')->name('students.store');
        Route::get('students/{student}/edit', 'show')->name('students.show');
        Route::get('students/{student}/view', 'view')->name('students.view');
        Route::put('students/{student}', 'update')->name('students.update');
        Route::delete('students/{student}', 'delete')->name('students.delete');
        Route::get('students/{student}/code-confirmation', 'send_delete_code');

        Route::put('students/{student}/psychosocial', 'psychosocial_update')->name('students.psychosocial.update');
        Route::get('students/{student}/transfer', 'transfer')->name('students.transfer');
        Route::post('students/{student}/transfer', 'transfer_store')->name('students.transfer.store');

        Route::get('students/no-enrolled', 'no_enrolled')->name('students.no_enrolled');
        Route::get('students', 'enrolled')->name('students.enrolled');

        Route::get('students/data/instructive', 'data_instructive')->name('students.data.instructive');
        Route::get('students/export/instructive', 'export_instructive')->name('students.instructive');
        Route::get('students/export-noenrolled', 'export_noenrolled')->name('students.export_noenrolled');

        Route::put('students/{student}/piar', 'piar_update')->name('students.piar');

        Route::get('students/{id}/matriculate', 'matriculate')->name('students.matriculate');
        Route::put('students/{student}/matriculate', 'matriculate_update')->name('students.matriculate.update');

        Route::get('students/parents.filter','create_parents_filter');

        Route::get('students/{student}/download/matriculate', 'pdf_matriculate')->name('students.pdf.matriculate');
        Route::get('students/download/certificate/{student?}', 'pdf_certificate')->name('students.pdf.certificate');
        Route::get('students/download/observations/{student?}', 'pdf_observations')->name('students.pdf.observations');
        Route::get('students/download/carnet/{student?}', 'pdf_carnet')->name('students.pdf.carnet');

        Route::get('enrolled-export', 'export_enrolled_view')->name('students.export.enrolled');
        Route::post('enrolled-export', 'export_enrolled_generate')->name('students.export.enrolled.generate');

        Route::get('students/withdrawn', 'withdrawn')->name('students.withdraw');
        Route::patch('students/{student}/withdraw', 'withdraw')->name('student.withdraw');

        Route::patch('students/{student}/activate', 'activate')->name('students.activate');
    });


    Route::put('persons-charge/{student}', [PersonChargeController::class, 'update'])->name('personsCharge');


    Route::put('student/{student}/files/', [StudentFileController::class, 'update'])->name('students.file');
    Route::put('student/{student}/files/checked', [StudentFileController::class, 'checked'])->name('students.file.checked');
    Route::delete('student/{student}/files', [StudentFileController::class, 'delete'])->name('students.file.delete');


    Route::put('students/{student}/report-book', [StudentReportBookController::class, 'update'])->name('students.reportBook');
    Route::put('students/{student}/report-book/checked', [StudentReportBookController::class, 'checked'])->name('students.reportBooks.checked');
    Route::delete('student/{student}/report-book', [StudentReportBookController::class, 'delete'])->name('students.reportBook.delete');





    /*
     *
     * TRACKING
     *
     * */
    Route::controller(StudentTrackingController::class)->group( function () {
        Route::post('students/{student}/add-advice', 'advice_store')->name('students.tracking.advice.store');
        Route::post('students/{student}/add-remit', 'remit_store')->name('students.tracking.remit.store');
        Route::post('students/{student}/add-recom-teacher', 'teachers_store')->name('students.tracking.teachers.store');
        Route::post('students/{student}/add-recom-coordination', 'coordination_store')->name('students.tracking.coordination.store');
        Route::post('students/{student}/add-recom-family', 'family_store')->name('students.tracking.family.store');
        Route::get('students/{student}/tracking/{advice}', 'tracking_evolution')->name('students.tracking.evolution');
        Route::put('students/{student}/tracking/{advice}', 'tracking_evolution_update')->name('students.tracking.evolution.store');
        Route::get('students/tracking', 'tracking_view');
    });



    /*
     *
     * ALERTS
     *
     * */
    Route::post('students/{student}/report-to-orientation', [UserAlertController::class, 'teacher_to_orientation'])->name('teacher.report.students.store');

    Route::get('alert/{alert}/checked', [UserAlertController::class, 'checked'])->name('alert.checked');


});


require __DIR__.'/auth.php';
