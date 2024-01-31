<?php

use App\Http\Controllers\api\UserAlertsController;
use App\Http\Controllers\AttendanceStudentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChangeEmailAddressAdmin;
use App\Http\Controllers\ConsolidateGradesByArea;
use App\Http\Controllers\CoordinationController;
use App\Http\Controllers\CoordinationDegreeController;
use App\Http\Controllers\CoordinationEmploymentHistoryController;
use App\Http\Controllers\CoordinationHierarchyController;
use App\Http\Controllers\CoordinationPermitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DescriptorController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupDirectorController;
use App\Http\Controllers\GroupFinishController;
use App\Http\Controllers\HeadersRemissionController;
use App\Http\Controllers\HeadquartersController;
use App\Http\Controllers\LeveledStudentController;
use App\Http\Controllers\OrientationController;
use App\Http\Controllers\OrientationDegreeController;
use App\Http\Controllers\OrientationEmploymentHistoryController;
use App\Http\Controllers\OrientationHierarchyController;
use App\Http\Controllers\OrientationPermitController;
use App\Http\Controllers\OtherOptionsController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PeriodPermitController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\PersonChargeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RemarkController;
use App\Http\Controllers\ResourceAreaController;
use App\Http\Controllers\ResourceSubjectController;
use App\Http\Controllers\RestorePasswordController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SecretariatController;
use App\Http\Controllers\SendMailMasiveController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFileController;
use App\Http\Controllers\StudentObserverController;
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
use App\Http\Controllers\VotingSystemController;
use App\Http\Controllers\VotingSystemGuestController;
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

Route::middleware('active_plataform')->group(function () {

    Route::middleware(['auth', 'changedYourPassword', 'active'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'show'])->name('dashboard');


        /* ACCESS SUPPORT */
        Route::middleware('can:support.access')->group(function () {

            Route::get('admin/{action}/{id?}', [\App\Http\Controllers\support\AccessSupportController::class, 'support']);

            Route::get('verification/{user}', function (User $user) {
                if (is_null($user->email_verified_at)) {
                    if ( (new UserController($user))->sendVerification() ) {

                        Notify::success('Correo enviado');
                        return back();
                    }

                    Notify::fail('Error');
                    return back();
                }
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
        Route::get('profile/avatar/edit', [ProfileController::class, 'auth_avatar_edit'])->name('profile.auth.avatar.edit');
        Route::put('profile/avatar/edit', [ProfileController::class, 'auth_avatar_edit_update'])->name('profile.auth.avatar.update');

        /* My Institution */
        Route::get('myinstitution', [SchoolController::class, 'show'])->name('myinstitution');
        Route::put('myinstitution', [SchoolController::class, 'update'])->name('myinstitution.update');
        Route::get('myinstitution/send-confirmation', [SchoolController::class, 'sendConfirmationEmail']);
        Route::patch('myinstitution', [SchoolController::class, 'security_email'])->name('myinstitution.security.email');
        Route::patch('myinstitution/signature', [SchoolController::class, 'signature_rector'])->name('myinstitution.security.signature');
        Route::patch('myinstitution/additional', [SchoolController::class, 'additional'])->name('myinstitution.additional.store');

        /* Route School Year */
        Route::resource('school-years', SchoolYearController::class)->except('destroy','edit','update')->names('schoolYear');
        Route::put('school-years', [SchoolYearController::class, 'choose'])->name('schoolYear.selected');

        /* Route Headquarters */
        Route::resource('headquarters', HeadquartersController::class)->except('destroy')->names('headquarters');
        Route::get('headquarters/{headquarters}/students', [HeadquartersController::class, 'download_students'])->name('headquarters.download-students');

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
        Route::get('study-years/{study_year}/groups-guide', [StudyYearController::class, 'download_guide_groups'])->name('studyYear.groups-guide');

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
        Route::resource('teachers', TeacherController::class)->except('destroy','edit','update')->names('teacher');
        // Route::post('teachers/{teacher}/permit', [TeacherPermitController::class, 'store'])->name('teachers.permits.store');
        Route::patch('teachers/{teacher}/permit', [TeacherPermitController::class, 'store_document'])->name('teachers.permits.document');
        Route::patch('teachers/{teacher}/permit/accept-or-deny', [TeacherPermitController::class, 'acceptedOrDenied'])->name('teachers.permit.accepted');
        Route::post('teachers/hierarchy', [TeacherHierarchyController::class, 'store'])->name('teacher.hierarchy.store');
        Route::post('teachers/degree', [TeacherDegreeController::class, 'store'])->name('teacher.degree.store');
        Route::post('teachers/employment', [TeacherEmploymentHistoryController::class, 'store'])->name('teacher.employment.store');
        Route::get('teacher/{teacher}/guide-groups', [TeacherController::class, 'download_guide_group'])->name('teacher.guide-groups');
        Route::get('teacher/{teacher}/permit-tab', [TeacherController::class, 'permitTab'])->name('teacher.show.permit-tab');

        Route::get('alert-permit/{permit}/read', [TeacherPermitController::class, 'deleteAlertPermit'])->name('alert-permit.delete');

        Route::post('add-permit', [PermitController::class, 'store'])->name('add-permit');



        /* Route Secretariat */
        Route::resource('secretariat', SecretariatController::class)->only('index', 'create','store')->names('secreatariat');



        /* Route Coordination */
        Route::resource('coordination', CoordinationController::class)->only('index', 'show', 'create','store')->names('coordination');
        // Route::post('coordination/{coordination}/permit', [CoordinationPermitController::class, 'store'])->name('coordination.permits.store');
        Route::patch('coordination/{coordination}/permit', [CoordinationPermitController::class, 'store_document'])->name('coordination.permits.document');
        Route::patch('coordination/{coordination}/permit/accept-or-deny', [CoordinationPermitController::class, 'acceptedOrDenied'])->name('coordination.permit.accepted');
        Route::post('coordination/hierarchy', [CoordinationHierarchyController::class, 'store'])->name('coordination.hierarchy.store');
        Route::post('coordination/degree', [CoordinationDegreeController::class, 'store'])->name('coordination.degree.store');
        Route::post('coordination/employment', [CoordinationEmploymentHistoryController::class, 'store'])->name('coordination.employment.store');
        Route::get('coordination/{coordination}/permit-tab', [CoordinationController::class, 'permitTab'])->name('coordination.show.permit-tab');



        /* Route Orientation */
        Route::resource('orientation', OrientationController::class)->only('index', 'show', 'create','store')->names('orientation');
        // Route::post('orientation/{orientation}/permit', [OrientationPermitController::class, 'store'])->name('orientation.permits.store');
        Route::patch('orientation/{orientation}/permit', [OrientationPermitController::class, 'store_document'])->name('orientation.permits.document');
        Route::patch('orientation/{orientation}/permit/accept-or-deny', [OrientationPermitController::class, 'acceptedOrDenied'])->name('orientation.permit.accepted');
        Route::post('orientation/hierarchy', [OrientationHierarchyController::class, 'store'])->name('orientation.hierarchy.store');
        Route::post('orientation/degree', [OrientationDegreeController::class, 'store'])->name('orientation.degree.store');
        Route::post('orientation/employment', [OrientationEmploymentHistoryController::class, 'store'])->name('orientation.employment.store');
        Route::get('orientation/{orientation}/permit-tab', [OrientationController::class, 'permitTab'])->name('orientation.show.permit-tab');



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
        Route::get('group/export-student-list-guide/{subject}', [GroupController::class, 'exportStudentListGuide'])->name('group.export.student-list-guide');
        Route::get('group/{group}/export-information-student-list', [GroupController::class, 'exportStudentsWithFiles'])->name('group.export.information-student-list');
        Route::get('group/{group}/export-attendance-control', [GroupController::class, 'exportAttendanceControlGuide'])->name('group.export.attendance-control');
        Route::get('group/{group}/report-notes', [GradeController::class, 'reportForPeriod']);
        Route::delete('groups/{group}', [GroupController::class, 'delete'])->name('groups.delete');
        Route::get('groups/{group}/transfer-students', [TransferController::class, 'groupStudents'])->name('group.transfer-students');
        Route::put('groups/{group}/transfer-students', [TransferController::class, 'groupStudents_selection'])->name('group.transfer-students.select-students');
        Route::post('groups/{group}/transfer-students', [TransferController::class, 'groupStudents_hss'])->name('group.transfer-students.hss');
        Route::post('groups/transfer-sel-group', [TransferController::class, 'selectionGroup'])->name('group.transfer-students.selGroup');
        Route::post('groups/{group}/grade-report', [GradeController::class, 'reportForGroup'])->name('group.reportGrade');

        Route::put('attendance/upload-file', [AttendanceStudentController::class, 'upload_file'])->name('attendance.upload_file');

        Route::post('groups/{group}/consolidate/grades', [ConsolidateGradesByArea::class, 'make'])->name('group.consolidate-grades');

        Route::get('groups/{group}/finish', [GroupFinishController::class, 'show'])->name('group.finish');
        Route::post('groups/{group}/finish', [GroupFinishController::class, 'store'])->name('group.finish.store');


        /* Route Group Directors */
        Route::resource('group-directors', GroupDirectorController::class)->only('index', 'edit', 'update')->names('group-directors');


        /* Remarks */
        Route::post('group/{group}/remark-students', [RemarkController::class, 'store'])->name('remark.store');


        /* Descriptors */
        Route::get('subject/{subject}/descriptors', [DescriptorController::class, 'index'])->name('subject.descriptors');
        Route::get('subject/{subject}/descriptors/create', [DescriptorController::class, 'create'])->name('subject.descriptors.create');
        Route::post('subject/{subject}/descriptors', [DescriptorController::class, 'store'])->name('subject.descriptors.store');
        Route::get('subject/{subject}/descriptors/import', [DescriptorController::class, 'import_view'])->name('subject.descriptors.import');
        Route::post('subject/{subject}/descriptors/import', [DescriptorController::class, 'import_store'])->name('subject.descriptors.import.store');
        /* access for teachers */
        Route::get('subject/{subject}/{studyYear}/descriptors', [DescriptorController::class, 'index'])->name('teacher.subject.descriptors');
        Route::get('subject/{subject}/{studyYear}/descriptors/create', [DescriptorController::class, 'create'])->name('teacher.subject.descriptors.create');
        Route::post('subject/{subject}/{studyYear}/descriptors', [DescriptorController::class, 'store'])->name('teacher.subject.descriptors.store');
        Route::get('subject/{subject}/{studyYear}/descriptors/import', [DescriptorController::class, 'import_view'])->name('teacher.subject.descriptors.import');
        Route::post('subject/{subject}/{studyYear}/descriptors/import', [DescriptorController::class, 'import_store'])->name('teacher.subject.descriptors.import.store');


        /* Permit Period */
        Route::post('subject/permit', [PeriodPermitController::class, 'store'])->name('period.permit');


        /* Qualification */
        Route::post('mysubjects/{subject}/qualify', [GradeController::class, 'store'])->name('subject.qualify.students');
        Route::get('group/{group}/student/grades/view', [GradeController::class, 'editGradesStudent']);
        Route::patch('group/{group}/student/{student}/qualification', [GradeController::class, 'saveGradesForStudent'])->name('group.student.save-qualification');
        Route::get('group/export-grades/{subject}', [GroupController::class, 'exportGradesInstructive'])->name('group.export.grades-instructive');
        Route::patch('group/import-grades/{subject}/period/{period}', [GradeController::class, 'importGroupGradesForPeriod'])->name('group.import.subject-grades');

        /* Route TeacherSubjectGroups */
        Route::resource('teachers/{teacher}/subjects', TeacherSubjectGroupController::class)->names('teacher.subjects');




        /* Route Teacher User */
        Route::get('mysubjects', [TeacherController::class, 'mysubjects'])->name('teacher.my.subjects');
        Route::get('mysubjects/{subject}', [TeacherController::class, 'mysubjects_show'])->name('teacher.my.subjects.show');
        Route::get('mysubjects/{subject}/attendance-limit-week', [TeacherController::class, 'attendanceLimitWeek']);
        Route::post('mysubjects/{subject}/leveling', [LeveledStudentController::class, 'leveling'])->name('subject.leveling');

        Route::post('attendance/{subject}', [AttendanceStudentController::class, 'subject'])->name('attendance.subject');
        Route::get('attendance/absences', [AttendanceStudentController::class, 'absences_view']);
        Route::get('attendance/{attendance}/edit', [AttendanceStudentController::class, 'absences_edit']);
        Route::put('attendance/{attendance}/edit', [AttendanceStudentController::class, 'absences_update'])->name('attendance.update');
        Route::get('attendances/student/{student}', [AttendanceStudentController::class, 'reportForStudent'])->name('attendances.student.download');



        /* Route Students */
        Route::controller(StudentController::class)->group( function () {

            Route::get('json/students.json', 'jsonEnrolled');

            Route::get('students/create', 'create')->name('students.create');
            Route::post('students/no-enrolled', 'store')->name('students.store');
            Route::get('student/{student}', 'show')->name('students.show');
            Route::get('student/{student}/edit', 'show');
            Route::get('student/{student}/view', 'show');
            Route::put('student/{student}', 'update')->name('students.update');
            Route::delete('student/{student}', 'delete')->name('students.delete');
            Route::get('student/{student}/code-confirmation', 'send_delete_code');

            Route::put('students/{student}/psychosocial', 'psychosocial_update')->name('students.psychosocial.update');
            Route::get('students/{student}/transfer', 'transfer')->name('students.transfer');
            Route::post('students/{student}/transfer', 'transfer_store')->name('students.transfer.store');

            Route::get('students/no-enrolled', 'no_enrolled')->name('students.no_enrolled');
            Route::get('students', 'enrolled')->name('students.enrolled');
            Route::get('students/inclusive', 'inclusive_students')->name('students.inclusive');
            Route::patch('students/inclusive/non-inclusive', 'changeToNonInclusive')->name('students.non-inclusive');

            Route::get('students/data/instructive', 'data_instructive')->name('students.data.instructive');
            Route::get('students/export/instructive', 'export_instructive')->name('students.instructive');
            Route::get('students/export-noenrolled', 'export_noenrolled')->name('students.export_noenrolled');

            Route::put('students/{student}/piar', 'piar_update')->name('students.piar');

            Route::get('students/{id}/matriculate', 'matriculate')->name('students.matriculate');
            Route::put('students/{student}/matriculate', 'matriculate_update')->name('students.matriculate.update');

            Route::get('students/parents.filter','create_parents_filter');

            Route::get('students/{student}/download/matriculate', 'pdf_matriculate')->name('students.pdf.matriculate');
            Route::get('students/download/certificate/{student?}', 'pdf_certificate')->name('students.pdf.certificate');
            Route::get('students/download/template-observations/{student?}', 'pdf_observations')->name('students.pdf.template-observations');
            Route::get('students/download/observations/{student?}', 'pdf_with_observations')->name('students.pdf.observations');
            Route::get('students/download/carnet/{student?}', 'pdf_carnet')->name('students.pdf.carnet');
            Route::get('students/download/matriculate/{student?}', 'pdf_matriculate')->name('student.pdf.matriculate');

            Route::get('report-grades', 'report_grades')->name('students.report_grades');
            Route::get('download/report-grades', 'pdf_report_grades')->name('students.pdf.report_grades');

            Route::get('enrolled-export', 'export_enrolled_view')->name('students.export.enrolled');
            Route::post('enrolled-export', 'export_enrolled_generate')->name('students.export.enrolled.generate');

            Route::get('students/withdrawn', 'withdrawn')->name('students.withdraw');
            Route::patch('students/{student}/withdraw', 'withdraw')->name('student.withdraw');

            Route::patch('students/{student}/activate', 'activate')->name('students.activate');

            Route::delete('students/{student}/delete-signature', 'signature_delete')->name('students.signature.delete');


        });

        Route::post('students/add-observation', [StudentObserverController::class, 'storeMultiple'])->name('students.observer.multiple');
        Route::post('student/{student}/add-observation', [StudentObserverController::class, 'store'])->name('students.observer.create');
        Route::put('student/{student}/add-disclaimers', [StudentObserverController::class, 'disclaimers'])->name('students.observer.disclaimers');


        Route::put('persons-charge/{student}', [PersonChargeController::class, 'update'])->name('personsCharge');


        Route::put('student/{student}/files', [StudentFileController::class, 'update'])->name('students.file');
        Route::patch('student/{student}/files', [StudentController::class, 'wizard_documents_request'])->name('students.file.wizard.next');
        Route::put('student/{student}/files/checked', [StudentFileController::class, 'checked'])->name('students.file.checked');
        Route::delete('student/{student}/files', [StudentFileController::class, 'delete'])->name('students.file.delete');


        Route::put('students/{student}/report-book', [StudentReportBookController::class, 'update'])->name('students.reportBook');
        Route::patch('students/{student}/report-book', [StudentController::class, 'wizard_report_books_request'])->name('students.reportBook.wizard.next');
        Route::put('students/{student}/report-book/checked', [StudentReportBookController::class, 'checked'])->name('students.reportBooks.checked');
        Route::delete('student/{student}/report-book', [StudentReportBookController::class, 'delete'])->name('students.reportBook.delete');

        Route::put('students/{student}/person-charge', [StudentController::class, 'wizard_person_charge_request'])->name('students.person-charge.wizard');
        // Route::patch('students/{student}/person-charge', [StudentController::class, 'wizard_person_charge_request'])->name('students.person-charge.wizard.next');
        Route::put('students/{student}/personal_info', [StudentController::class, 'wizard_personal_info_request'])->name('student.personal-info.wizard');

        Route::put('students/{student}/complete', [StudentController::class, 'wizard_complete_request'])->name('student.complete.wizard');

        /*
        *
        * Change Email Address Administrative
        *
        */
        Route::controller(ChangeEmailAddressAdmin::class)->group( function () {
            Route::get('teachers/{teacher}/change-email/code-confirmation', 'teacher');
            Route::patch('teachers/{teacher}/change-email}', 'teacherUpdate')->name('teachers.change-email');

            Route::get('orientation/{orientation}/change-email/code-confirmation', 'orientator');
            Route::patch('orientation/{orientation}/change-email}', 'orientatorUpdate')->name('orientation.change-email');
        });




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
            Route::get('students/tracking/{tracking}/download', 'download_tracking')->name('student.tracking.download');
        });


        /*
        *
        * Calendar
        *
        *  */
        Route::controller(CalendarController::class)->group( function () {
            Route::get('calendar', 'index')->name('calendar.index');
            Route::get('json/calendar.json', 'data');
        });



        /*
        *
        * ALERTS
        *
        * */
        Route::post('students/{student}/report-to-orientation', [UserAlertController::class, 'teacher_to_orientation'])->name('teacher.report.students.store');

        Route::get('alert/{alert}/checked', [UserAlertController::class, 'checked'])->name('alert.checked');
        Route::get('alert/{alert}/approval', [UserAlertController::class, 'approval'])->name('alert.approval');



        /*
        *
        * Other Options
        *
        *  */
        Route::middleware('hasroles:SUPPORT,SECRETARY')->controller(OtherOptionsController::class)->group( function () {
            Route::get('other-options', 'index')->name('other-options.index');

            Route::get('other-options/type-permission', 'create');
            Route::post('other-options/type-permission', 'store')->name('type-permission.store');
            Route::put('other-options/type-permissions/{typePermission}', 'update')->name('type-permission.update');
        });



        /*
        *
        * Resporte Password
        *
        *  */
        Route::get('user/restore-password', [RestorePasswordController::class, 'restore'])->middleware('hasroles:SUPPORT,SECRETARY');



        /*
        *
        * Add Role VOTING DIRECTOR
        *
        *  */
        Route::middleware('hasroles:SUPPORT,SECRETARY')->controller(VotingSystemController::class)->group( function () {
            Route::patch('add-user', 'addUser')->name('voting.add-user');
            Route::delete('remove-user', 'removeUser')->name('voting.remove-user');
        });



        /*
        *
        * Headers Remissions
        *
        *  */
        Route::middleware('hasroles:ORIENTATION')->resource('headers-remissions', HeadersRemissionController::class)->except('destroy', 'show')->names('headers-remissions');


        /*
        *
        * System Voting
        *
        *  */
        Route::middleware('hasroles:VOTING_COORDINATOR')->controller(VotingSystemController::class)->group( function () {
            Route::get('voting', 'index')->name('voting.index');
            Route::get('voting/create', 'create')->name('voting.create');
            Route::post('voting/store', 'store')->name('voting.store');
            Route::patch('voting/start', 'start')->name('voting.start');
            Route::get('voting/{voting}/report', 'report')->name('voting.report');
            Route::patch('voting/{voting}/report', 'finish')->name('voting.finish');
            Route::get('voting/{voting}/students', 'download_students')->name('voting.download.students');
        });



        /*
        *
        * Send Mail Masive
        *
        *  */
        Route::controller(SendMailMasiveController::class)->group( function () {
            Route::post('send-mail/group/{group}', 'forGroup')->name('send-mail.group');
            Route::get('mails_sent', 'index')->name('mails-sent.index');
            Route::get('mails_sent/{mail}', 'show')->name('mails-sent.show');
            Route::get('mails_sent/{mail}/log', 'log');
        });


        /*
        *
        * API
        *
        *  */
        Route::get('api/user-alerts-count', [UserAlertsController::class, 'alertsCount']);
    });


    /*
    *
    * Student Voting
    *
    *  */
    Route::controller(VotingSystemGuestController::class)->group(function () {
        Route::get('votacion', 'toVote')->name('voting.to-vote');
        Route::get('votar', 'toStart')->name('voting.to-start');
        Route::patch('votar', 'saveVote')->name('voting.save-vote');
    });

});

require __DIR__.'/auth.php';
