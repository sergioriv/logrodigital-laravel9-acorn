<?php

namespace App\Http\Controllers;

use App\Exports\StudentsEnrolledExport;
use App\Exports\StudentsInstructuveExport;
use App\Exports\StudentsWithFiles;
use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\GenerateStudentCode;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Imports\StudentsImport;
use App\Models\Attendance;
use App\Models\City;
use App\Models\Coordination;
use App\Models\Country;
use App\Models\Data\RoleUser;
use App\Models\Disability;
use App\Models\DocumentType;
use App\Models\DwellingType;
use App\Models\EconomicDependence;
use App\Models\EthnicGroup;
use App\Models\Gender;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\HeadersRemission;
use App\Models\Headquarters;
use App\Models\HealthManager;
use App\Models\IcbfProtectionMeasure;
use App\Models\Kinship;
use App\Models\LinkageProcess;
use App\Models\Orientation;
use App\Models\Period;
use App\Models\PersonCharge;
use App\Models\Piar;
use App\Models\Religion;
use App\Models\Reservation;
use App\Models\ResourceStudyYear;
use App\Models\Rh;
use App\Models\Sisben;
use App\Models\Student;
use App\Models\StudentFileType;
use App\Models\StudentObserver;
use App\Models\StudentRemovalCode;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Teacher;
use App\Models\TypesConflict;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /* $this->middleware('hasroles:SUPPORT,SECRETARY,STUDENT')->only(
            'pdf_carnet',
            'pdf_observations',
            'pdf_certificate',
            'pdf_matriculate'); */

        $this->middleware('can:students.info')->only(
            'update'
        );

        $this->middleware('can:students.index')->only(
            'export_noenrolled',
            'inclusive_students',
            'no_enrolled',
            'enrolled'
        );

        $this->middleware('hasroles:SECRETARY,COORDINATOR,TEACHER,SUPPORT')->only(
            'export_enrolled_generate',
            'export_enrolled_view'
        );

        $this->middleware('can:students.create')->only(
            'store',
            'create'
        );

        $this->middleware('can:students.matriculate')->only(
            'transfer_store',
            'transfer',
            'matriculate_update',
            'matriculate',
            'create_parents_filter'
        );

        $this->middleware('can:students.import')->only(
            'import_store',
            'import',
            'export_instructive',
            'data_instructive'
        );

        $this->middleware('can:students.psychosocial')->only(
            'psychosocial_update',
            'piar_update');

        $this->middleware('can:students.delete')->only(
            'send_delete_code',
            'delete',
            'withdraw',
            // 'withdrawn',
            'activate',
            'signature_delete');

        $this->middleware('hasroles:SUPPORT,ORIENTATION,SECRETARY,COORDINATOR')->only('withdrawn');

        $this->middleware(YearCurrentMiddleware::class)->only(
            'create',
            'store',
            'transfer_store',
            'transfer',
            'matriculate_update',
            'matriculate',
            'create_parents_filter');

        $this->middleware('countStudents')->only(
            'create',
            'store',
            'import',
            'import_store');

        $this->middleware('hasroles:SUPPORT,ORIENTATION')->only('changeToNonInclusive');
    }


    public function show(Student $student)
    {
        if ('PARENT' == UserController::role_auth() && $student) {

            $parentCheck = \App\Models\PersonCharge::where('email', auth()->user()->email)->where('student_id', $student->id)->count();
            if (!$parentCheck) {
                Notify::fail("No permitido");
                return back();
            }

        }

        $user = Auth::user();
        if ( $user->hasPermissionTo('students.view') ) {

            return $this->view($student);

        } else if ( $user->hasPermissionTo('students.info') ) {

            return $this->edit($student);
        }

        return back();
    }

    /*
     * PRE-REGISTRATION SECTION
     */
    public function no_enrolled()
    {
        $Y = SchoolYearController::current_year();

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'institutional_email',
            'document_type_code',
            'document',
            'status',
            'inclusive',
            'headquarters_id',
            'study_time_id',
            'study_year_id',
            'created_at'
        )
            ->with('headquarters', 'studyTime', 'studyYear')
            ->withCount('filesRequired')
            ->where('school_year_create', '<=', $Y->id)
            ->where(function ($query) {
                $query->whereIn('status', ['new', 'repeat'])->orWhereNull('status');
            })
            ->whereNot(
                fn ($q) =>
                $q->whereHas('groupYear', fn ($gs) =>
                    $gs->whereHas('group', fn ($g) => $g->where('school_year_id', $Y->id)))
            );

        if (0 === $Y->available) {
            $students->whereNull('enrolled');
        }


        $countFileTypes = StudentFileType::where('required', 1)->count();

        return view('logro.student.noenrolled', [
            'students' => $students->get(),
            'countFileTypes' => $countFileTypes
        ]);
    }

    public function create()
    {
        $Y = SchoolYearController::current_year();


        /* notificación de estudiantes restantes */
        $S = SchoolController::myschool()->getData();
        $numberStudents = Student::available()->count();

        if ($numberStudents >= ($S->number_students - 100)) {
            Notify::info(__(":count students remain from the contracted plan.", ['count' => $S->number_students - $numberStudents]));
        }

        return view("logro.student.create", [
            'SCHOOL' => $S,
            'headquarters' => Headquarters::all(),
            'studyTime' => StudyTime::all(),
            'studyYear' => StudyYear::all(),
            'cities' => City::with('department')->get(),
            'countries' => Country::all(),
            'documentType' => DocumentType::orderBy('foreigner')->get(),
            'countGroups' => Group::where('school_year_id', $Y->id)->count(),
            'nationalCountry' => NationalCountry::country()
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'firstName' => ['required', 'string', 'max:191'],
            'secondName' => ['nullable', 'string', 'max:191'],
            'firstLastName' => ['required', 'string', 'max:191'],
            'secondLastName' => ['nullable', 'string', 'max:191'],
            'document_type' => ['required', Rule::exists('document_types', 'code')],
            'document' => ['required', 'max:20', Rule::unique('students', 'document')],
            'country' => ['required', Rule::exists('countries', 'id')],
            'birth_city' => ['nullable', Rule::exists('cities', 'id')],
            'birthdate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'siblings_in_institution' => ['nullable', 'boolean'],
            'institutional_email' => ['required', 'max:191', 'email', Rule::unique('users', 'email')],
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'studyTime' => ['required', Rule::exists('study_times', 'id')],
            'studyYear' => ['required', Rule::exists('study_years', 'id')],
            'repeat' => ['nullable', 'boolean']
        ]);

        DB::beginTransaction();

        $studentUserName = $request->firstName . ' ' . $request->firstLastName;
        $studentCreate = UserController::__create($studentUserName, $request->institutional_email, RoleUser::STUDENT);

        $Y = SchoolYearController::current_year();

        /* DATOS PAIS DE ORIGEN */
        if (NationalCountry::country()->id != $request->country) {
            $request->birth_city = NULL;
        }

        try {

            Student::create([
                'id' => $studentCreate->getUser()->id,
                'code' => GenerateStudentCode::code(),
                'first_name' => $request->firstName,
                'second_name' => $request->secondName,
                'first_last_name' => $request->firstLastName,
                'second_last_name' => $request->secondLastName,
                'document_type_code' => $request->document_type,
                'document' => $request->document,
                'country_id' => $request->country,
                'birth_city_id' => $request->birth_city,
                'birthdate' => $request->birthdate,
                'siblings_in_institution' => $request->siblings_in_institution,
                'institutional_email' => $request->institutional_email,
                'school_year_create' => $Y->id,
                'headquarters_id' => $request->headquarters,
                'study_time_id' => $request->studyTime,
                'study_year_id' => $request->studyYear,
                'status' => $request->repeat == 1 ? 'repeat' : 'new',
                'data_treatment' => TRUE
            ]);

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }


        $buttons = [
            [
                'title' => __('Go back'),
                'class' => 'btn-outline-alternate',
                'action' => route('students.no_enrolled'),
            ]
        ];

        if (1 == $request->matriculate)
        {
            array_push($buttons, [
                'title' => __('Create new'),
                'class' => 'btn-outline-primary ms-2',
                'action' => url()->previous(),
            ],[
                'title' => __('Matriculate'),
                'class' => 'btn-primary ms-2',
                'action' => route('students.matriculate', $studentCreate->getUser()->id)
            ]);

        } else
        {
            array_push($buttons, [
                'title' => __('Create new'),
                'class' => 'btn-primary ms-2',
                'action' => url()->previous(),
            ]);
        }

        return view('logro.created', [
            'role' => 'student',
            'title' => __('Student created!'),
            'email' => $request->institutional_email,
            'password' => $studentCreate->getUser()->temporalPassword,
            'buttons' => $buttons
        ]);
    }

    /*
     * WIZARD START
     * Solo para el estudiante
     */
    public function wizard_documents(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $studentFileTypes = StudentFileType::with([
                'studentFile' => fn ($files) => $files->where('student_id', $student->id)
            ]);
            if (NULL === $student->disability_id || 1 === $student->disability_id)
                $studentFileTypes->where('inclusive', 0);

            return view('logro.student.wizard-documents')->with([
                'student' => $student,
                'studentFileTypes' => $studentFileTypes->get()
            ]);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_documents_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail(Auth::id());
            if ($request->docsFails > 0) {
                return redirect()->back()->withErrors(["custom" => __("documents are missing to upload")]);
            }

            $student->forceFill([
                'wizard_documents' => TRUE
            ])->save();

            return redirect()->back()->with('student', $student);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_reportBooks(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {
            /* Años de estudio igual e inferior al año de estudio actual del estudiante */
            $resourceStudyYears = ResourceStudyYear::where('id', '<=', $student->studyYear->resource_study_year_id)
                ->with([
                    'studentReportBook' => fn ($reportBooks) => $reportBooks->where('student_id', $student->id)
                ]);


            return view('logro.student.wizard-report-books')->with([
                'student' => $student,
                'resourceStudyYears' => $resourceStudyYears->get()
            ]);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_report_books_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail(Auth::id());

            $student->forceFill([
                'wizard_report_books' => TRUE
            ])->save();

            return redirect()->back()->with('student', $student);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_person_charge(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $cities = City::all();
            $kinships = Kinship::all();

            return view('logro.student.wizard-person-charge')->with([
                'student' => $student,
                'cities' => $cities,
                'kinships' => $kinships
            ]);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_person_charge_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail(Auth::id());

            $person_charge = new PersonChargeController;
            return $person_charge->update($student, $request, TRUE);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_personal_info(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {

            return view('logro.student.wizard-personal-info')->with([
                'student'       => $student,
                'documentType'  => DocumentType::orderBy('foreigner')->get(),
                'cities'        => City::all(),
                'countries'     => Country::all(),
                'genders'       => Gender::all(),
                'rhs'           => Rh::all(),
                'healthManager' => HealthManager::all(),
                'sisbenes'      => Sisben::all(),
                'dwellingTypes' => DwellingType::all(),
                'disabilities'  => Disability::all(),
                'handbook'      => SchoolController::myschool()->handbook(),
                'nationalCountry' => NationalCountry::country(),
                'ethnicGroups'  => EthnicGroup::all(),
                'reservations'  => Reservation::all(),
                'typesConflict' => TypesConflict::all(),
                'icbfProtections' => IcbfProtectionMeasure::all(),
                'linkageProcesses' => LinkageProcess::all(),
                'religions'     => Religion::all(),
                'economicDependences' => EconomicDependence::all(),
            ]);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_personal_info_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail(Auth::id());

            return self::update($request, $student, TRUE);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    public function wizard_complete()
    {
        return view('logro.student.wizard-complete');
    }
    public function wizard_complete_request()
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail(Auth::id());

            $student->forceFill([
                'wizard_complete' => TRUE
            ])->save();

            return self::show($student);
        }

        Notify::fail(__('Unauthorized!'));
        return redirect()->route('dashboard');
    }
    /*
     * WIZARD END
     */


    /*
     * ENROLLED SECTION
     */
    public function enrolled()
    {
        $Y = SchoolYearController::current_year();

        $fn_g = fn ($g) => $g->with('headquarters', 'studyYear', 'studyTime')->where('school_year_id', $Y->id);

        $fn_gs = fn ($gs) =>
        $gs->withWhereHas('group', $fn_g);

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'institutional_email',
            'document_type_code',
            'document',
            'status',
            'inclusive'
        )
            ->whereHas('groupYear', $fn_gs)
            ->with(['groupYear' => $fn_gs])
            ->get();


        return view('logro.student.enrolled')->with('students', $students);
    }

    public function jsonEnrolled(Request $request)
    {
        $search = $request->search['value'];
        $Y = SchoolYearController::current_year();

        $students = Student::select('id', 'first_name', 'second_name', 'first_last_name', 'second_last_name', 'first_name as fullname', 'headquarters_id', 'group_id')
            ->whereRaw("CONCAT(students.first_last_name,' ',students.second_last_name,' ',students.first_name,' ',students.second_name) LIKE '%{$search}%'")
            ->whereHas('groupYear', fn($gYear) => $gYear->whereHas('group', fn($group) => $group->where('school_year_id', $Y->id)) )
            ->with([
                'group:id,name',
                'headquarters:id,name'
            ])
            ->limit(15)
            ->get();

        return response()->json(['data' => $students]);
    }

    public function matriculate($student_id)
    {
        $Y = SchoolYearController::current_year();

        $student = Student::select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'headquarters_id',
            'study_time_id',
            'study_year_id',
            'group_id',
            'status',
            'inclusive'
        )->findOrFail($student_id);

        /*
         * VALIDACION PARA ESTUDIANTES MATRICULADOS EN AÑOS ANTERIORES
         *  */

        $groups = Group::whereNull('specialty')
            ->where('school_year_id', $Y->id)
            ->where('headquarters_id', $student->headquarters_id)
            ->where('study_time_id', $student->study_time_id)
            ->where('study_year_id', $student->study_year_id)
            ->withCount('groupStudents as student_quantity')
            ->with('headquarters', 'studyTime', 'studyYear', 'teacher')
            ->with(['groupStudents' => fn($GS) => $GS->with('student')])
            ->get();

        if (0 === count($groups)) {
            Notify::fail(__('No groups'));
            return redirect()->back();
        }

        return view('logro.student.matriculate')->with([
            'student' => $student,
            'groups' => $groups
        ]);
    }

    public function matriculate_update(Request $request, Student $student)
    {
        $request->validate([
            'group' => ['required', Rule::exists('groups', 'id')->whereNull('specialty')]
        ]);

        $group = Group::find($request->group);

        if (
            $group->headquarters_id === $student->headquarters_id
            && $group->study_time_id === $student->study_time_id
            && $group->study_year_id === $student->study_year_id
        ) {

            if ($student->group_id != $request->group) {

                $oldGroupStudentExist = GroupStudent::where('group_id', $student->group_id)->where('student_id', $student->id)->first();

                $Y = SchoolYearController::current_year();
                if ($oldGroupStudentExist) {
                    /* Inicio Migracion de calificaciones */
                    $grades_oldGroup = \App\Models\Grade::where('student_id', $student->id)->withWhereHas('teacherSubjectGroup', fn ($tsgOldGroup) => $tsgOldGroup->where('group_id', $oldGroupStudentExist->group_id)->with('subject'))->get();
                    foreach ($grades_oldGroup as $grade_oldGroup) {
                        $tsgNew = \App\Models\TeacherSubjectGroup::updateOrCreate(
                            [
                                'school_year_id' => $Y->id,
                                'group_id' => $group->id,
                                'subject_id' => $grade_oldGroup->teacherSubjectGroup->subject_id
                            ],
                            []
                        );

                        $newPeriod = \App\Models\Period::where('ordering', $grade_oldGroup->period->ordering)->where('study_time_id', $group->study_time_id)->first();
                        $newPeriod && $grade_oldGroup->update([
                            'teacher_subject_group_id' => $tsgNew->id,
                            'period_id' => $newPeriod->id
                        ]);
                    }
                    /* Final Migracion de calificaciones */
                }


                if (NULL === $oldGroupStudentExist) {
                    GroupStudent::create([
                        'group_id' => $request->group,
                        'student_id' => $student->id
                    ]);

                    /* Si tiene algun registro como estudiante retirado, estos serán eliminados */
                    \App\Models\GroupStudentRetired::where('student_id', $student->id)->delete();

                    /* Send message WhatsApp */
                    // self::send_msg($student, $group);

                    /* Send mail to Email Person Charge */
                    SmtpMail::init()->sendEmailEnrollmentNotification($student, $group);

                    Notify::success(__('Student matriculate!'));
                } else {
                    $oldGroupStudentExist->update([
                        'group_id' => $request->group
                    ]);
                    Notify::success(__('Changed group!'));
                }

                $student->update([
                    'group_id' => $group->id,
                    'enrolled_date' => now(),
                    'enrolled' => TRUE
                ]);

                return redirect()->route('students.show', $student);
            } else {

                Notify::info(__('Unchanged!'));
                return redirect()->route('students.show', $student);
            }
        } else {
            return redirect()->back()->withErrors(__("Unexpected Error"));
        }
    }



    /* Tienen acceso Secretarias, Orientacion, El estudiante y Soporte */
    private function edit($student)
    {
        $Y = SchoolYearController::current_year();
        $YAvailable = SchoolYearController::available_year();

        $user = User::find(Auth::id());

        $studentFileTypes = StudentFileType::with([
            'studentFile' => function ($files) use ($student) {
                $files->where('student_id', $student->id);
            }
        ]);
        if (NULL === $student->disability_id || 1 === $student->disability_id) {
            $studentFileTypes->where('inclusive', 0);
        }

        /* Años de estudio igual e inferior al año de estudio actual del estudiante */
        $resourceStudyYears = ResourceStudyYear::where('id', '<=', $student->studyYear->resource_study_year_id)
            ->with([
                'studentReportBook' => fn ($reportBooks) => $reportBooks->where('student_id', $student->id)
            ]);


        /* opciones de orientacion */
        $orientationOptions = ['coordinators' => [], 'myTeachers' => []];
        if ($user->hasPermissionTo('students.psychosocial')) {
            $orientationOptions['coordinators'] = Coordination::all();

            $orientationOptions['myTeachers'] = Teacher::select('id', 'uuid', 'names', 'last_names')->whereHas('teacherSubjectGroups', function ($tsg) use ($student) {
                    $tsg->where('group_id', $student->group_id);
                })
                ->orderBy('names')
                ->orderBy('last_names')
                ->get();
        }

        $studentGradesxGroup = GradeController::studentGrades($Y, $student);

        return view('logro.student.profile')->with([
            'Y' => $Y,
            'YAvailable' => $YAvailable->id,
            'SCHOOL' => SchoolController::myschool(),
            'student' => $student,
            'documentType' => DocumentType::orderBy('foreigner')->get(),
            'cities' => City::with('department')->get(),
            'countries' => Country::all(),
            'genders' => Gender::all(),
            'rhs' => Rh::all(),
            'dwellingTypes' => DwellingType::all(),
            'healthManager' => HealthManager::all(),
            'sisbenes'      => Sisben::all(),
            'disabilities'  => Disability::all(),
            'ethnicGroups'  => EthnicGroup::all(),
            'reservations'  => Reservation::all(),
            'typesConflict' => TypesConflict::all(),
            'icbfProtections' => IcbfProtectionMeasure::all(),
            'linkageProcesses' => LinkageProcess::all(),
            'religions'     => Religion::all(),
            'economicDependences' => EconomicDependence::all(),
            'kinships'      => Kinship::all(),
            'studentFileTypes' => $studentFileTypes->get(),
            'resourceStudyYears' => $resourceStudyYears->get(),
            'groupsStudent' => [],
            'nationalCountry' => NationalCountry::country(),
            'coordinators' => $orientationOptions['coordinators'],
            'myTeachers' => $orientationOptions['myTeachers'],
            'headers_remission' => HeadersRemission::all(),
            'handbook'      => SchoolController::myschool()->handbook(),
            'observer' => StudentObserver::where('student_id', $student->id)->with('user_creator')->get(),
            'periods' => $studentGradesxGroup['periods'],
            'areasWithGrades' => $studentGradesxGroup['areasGrade']
        ]);
    }


    /* Tienen acceso Coordinacion y Docentes */
    private function view($student)
    {
        $myRole = UserController::role_auth();

        if ($myRole === RoleUser::TEACHER_ROL) {
            if ($student->isRetired() || is_null($student->enrolled)) {
                Notify::fail("El estudiante no se encuentra matriculado");
                return redirect()->back();
            }
        }

        $Y = SchoolYearController::current_year();
        $existOrientation = false;

        if ($myRole === RoleUser::TEACHER_ROL
            || $myRole === RoleUser::COORDINATION_ROL) {
            $existOrientation = (bool)Orientation::count();
        }

        $coordinators = null;
        if ($myRole === RoleUser::TEACHER_ROL) {
            $coordinators = Coordination::all();
        }

        if (!$student->isRetired() && $student->enrolled) {
            $groupStudent = GroupStudent::where('student_id', $student->id)
            ->whereHas('group', fn ($group) => $group->where('school_year_id', $Y->id)->whereNull('specialty') )
            ->first();
        } else {
            $groupStudent = \App\Models\GroupStudentRetired::where('student_id', $student->id)
            ->whereHas('group', fn ($group) => $group->where('school_year_id', $Y->id)->whereNull('specialty') )
            ->first();
        }

        $absences = null;
        $studentGradesxGroup = ['periods' => null, 'areasGrade' => null];
        if (auth()->id() === $groupStudent->group->teacher_id) {
            $absences = Attendance::withWhereHas(
                    'student',
                    fn ($s) => $s->where('student_id', $student->id)->whereIn('attend', ['N', 'L', 'J'])
                )->with('teacherSubjectGroup.subject', 'teacherSubjectGroup.teacher')
                ->orderByDesc('date')
                ->get();
        }
        if (auth()->id() === $groupStudent->teacher_id || RoleUser::COORDINATION_ROL === UserController::role_auth()) {
            $studentGradesxGroup = GradeController::studentGrades($Y, $student);
        }

        return view('logro.student.profile-view', [
            'student' => $student,
            'periods' => $studentGradesxGroup['periods'],
            'areasWithGrades' => $studentGradesxGroup['areasGrade'],
            'observer' => StudentObserver::where('student_id', $student->id)->with('user_creator')->get(),
            'existOrientation' => $existOrientation,
            'absences' => $absences,
            'coordinators' => $coordinators
        ]);
    }


    public function update(Request $request, Student $student, $wizard = false)
    {
        $userRole = UserController::role_auth();
        $required = 'nullable';
        $data_treatment = $student->data_treatment;
        $studentEmail = $student->institutional_email;

        DB::beginTransaction();

        if ('STUDENT' === $userRole) {
            $required = 'required';
            $data_treatment = $request->data_treatment;
            if ($request->docsFails > 0) {
                return redirect()->back()->withErrors(["custom" => __("documents are missing to upload")]);
            }

            self::signatures($student, $request);
        } else {
            $request->validate([
                'institutional_email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($student->id)]
            ]);

            $studentEmail = $request->institutional_email;
        }

        $request->validate([
            'firstName' => ['required', 'string', 'max:191'],
            'secondName' => ['nullable', 'string', 'max:191'],
            'firstLastName' => ['required', 'string', 'max:191'],
            'secondLastName' => ['nullable', 'string', 'max:191'],
            'telephone' => [$required, 'string', 'max:20'],
            'document_type' => ['required', Rule::exists('document_types', 'code')],
            'document' => ['required', 'string', 'max:20', Rule::unique('students', 'document')->ignore($student->id)],
            'expedition_city' => [$required, Rule::exists('cities', 'id')],
            'number_siblings' => [$required, 'numeric', 'max:200', 'min:0'],
            'country' => ['required', Rule::exists('countries', 'id')],
            'birth_city' => ['nullable', Rule::exists('cities', 'id')],
            'birthdate' => [$required, 'date', 'date_format:Y-m-d'],
            'siblings_in_institution' => [$required, 'boolean'],
            'gender' => [$required, Rule::exists('genders', 'id')],
            'rh' => [$required, Rule::exists('rhs', 'id')],
            'zone' => [$required, 'string', 'max:6'],
            'residence_city' => [$required, Rule::exists('cities', 'id')],
            'address' => [$required, 'string', 'max:100'],
            'social_stratum' => [$required, 'max:10'],
            'dwelling_type' => [$required, Rule::exists('dwelling_types', 'id')],
            'neighborhood' => [$required, 'string', 'max:100'],
            'electrical_energy' => ['nullable', 'boolean'],
            'natural_gas' => ['nullable', 'boolean'],
            'sewage_system' => ['nullable', 'boolean'],
            'aqueduct' => ['nullable', 'boolean'],
            'internet' => ['nullable', 'boolean'],
            'lives_with_father' => ['nullable', 'boolean'],
            'lives_with_mother' => ['nullable', 'boolean'],
            'lives_with_siblings' => ['nullable', 'boolean'],
            'lives_with_other_relatives' => ['nullable', 'boolean'],
            'health_manager' => [$required, Rule::exists('health_managers', 'id')],
            'school_insurance' => [$required, 'string', 'max:100'],
            'sisben' => [$required, Rule::exists('sisben', 'id')],
            'disability' => [$required, Rule::exists('disabilities', 'id')],
            'disability_certificate' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ethnic_group' => ['nullable', Rule::exists('ethnic_groups', 'id')],
            'reservation' => ['nullable', Rule::exists('reservations', 'id')],
            'type_conflic' => ['nullable', Rule::exists('types_conflict', 'id')],
            'origin_school' => ['nullable', 'string'],
            'type_origin_school' => ['nullable'],
            'icbf_protection' => ['nullable', Rule::exists('icbf_protection_measures', 'id')],
            'foundation_beneficiary' => ['nullable', 'boolean'],
            'linked_process' => ['nullable', Rule::exists('linkage_processes', 'id')],
            'religion' => ['nullable', Rule::exists('religions', 'id')],
            'economic_dependence' => ['nullable', Rule::exists('economic_dependences', 'id')],
            'data_treatment' => ['nullable', 'boolean'],
            'isRepeat' => ['nullable', 'boolean']
        ]);

        $studentUserName = $request->firstName . ' ' . $request->firstLastName;
        UserController::_update($student->id, $studentUserName, $studentEmail);

        /* DATOS PAIS DE ORIGEN */
        if (NationalCountry::country()->id != $request->country) {
            $request->birth_city = NULL;
        }

        /* COMPROBACION DE CERTIFICADO DE DISCAPACIDAD */
        $preInclusive = 0;
        if ($request->disability > 1) {

            StudentFileController::upload_disability_file($request, $student);

            if ( ! is_null($student->pre_inclusive) ) {
                $preInclusive = 1;
            }

        }

        $student->update([
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'first_last_name' => $request->firstLastName,
            'second_last_name' => $request->secondLastName,
            'document_type_code' => $request->document_type,
            'document' => $request->document,
            'institutional_email' => $studentEmail,
            'telephone' => $request->telephone,
            'expedition_city_id' => $request->expedition_city,
            'birth_city_id' => $request->birth_city,
            'country_id' => $request->country,
            'birthdate' => $request->birthdate,
            'siblings_in_institution' => $request->siblings_in_institution,
            'gender_id' => $request->gender,
            'rh_id' => $request->rh,
            'number_siblings' => $request->number_siblings,

            /* lugar de domicilio */
            'zone' => $request->zone,
            'address' => $request->address,
            'residence_city_id' => $request->residence_city,
            'social_stratum' => $request->social_stratum,
            'dwelling_type_id' => $request->dwelling_type,
            'neighborhood' => $request->neighborhood,
            'electrical_energy' => $request->electrical_energy,
            'natural_gas' => $request->natural_gas,
            'sewage_system' => $request->sewage_system,
            'aqueduct' => $request->aqueduct,
            'internet' => $request->internet,
            'lives_with_father' => $request->lives_with_father,
            'lives_with_mother' => $request->lives_with_mother,
            'lives_with_siblings' => $request->lives_with_siblings,
            'lives_with_other_relatives' => $request->lives_with_other_relatives,

            /* seguridad social */
            'health_manager_id' => $request->health_manager,
            'sisben_id' => $request->sisben,
            'school_insurance' => $request->school_insurance,
            'disability_id' => $request->disability,
            'pre_inclusive' => $preInclusive,

            /* informacion complementaria */
            'ethnic_group_id' => $request->ethnic_group,
            'reservation_id' => $request->reservation,
            'type_conflic_id' => $request->type_conflic,
            'origin_school' => $request->origin_school,
            'type_origin_school' => $request->type_origin_school,
            'ICBF_protection_measure_id' => $request->icbf_protection,
            'foundation_beneficiary' => $request->foundation_beneficiary,
            'linked_to_process_id' => $request->linked_process,
            'religion_id' => $request->religion,
            'economic_dependence_id' => $request->economic_dependence,

            /* politica de tratamiento de datos */
            'data_treatment' => $data_treatment,

            /* Status Repeat */
            'status' => !empty($request->isRepeat) ? 'repeat' : $student->status
        ]);

        DB::commit();

        if ($wizard === TRUE) {
            $student->forceFill([
                'wizard_personal_info' => TRUE
            ])->save();

            return redirect()->back();
        } else {

            Notify::success(__('Student updated!'));
            return redirect()->back();
        }
    }


    /*
     *
     *
     * INFO PHYCOSOCIAL
     *
     *
     * */
    public function inclusive_students()
    {
        $Y = SchoolYearController::current_year();

        $pendingStudents = null;
        if ( RoleUser::ORIENTATION_ROL === UserController::role_auth() ) {
            $pendingStudents = Student::where(function ($query) {
                $query->where('pre_inclusive', 1)
                        ->where(function ($student) {
                            $student->where('inclusive', 0)->orWhereNull('inclusive');
                        });
            })
            ->whereHas('groupYear', fn($gr) => $gr->whereHas('group', fn($g) => $g->where('school_year_id', $Y->id)))
            ->count();
        }


        $students = Student::
            where(function ($query) {
                $query->where('inclusive', 1)
                    ->orWhere('pre_inclusive', 1);
            })
            ->whereHas('groupYear', fn($gr) => $gr->whereHas('group', fn($g) => $g->where('school_year_id', $Y->id)))
            ->with('headquarters', 'studyTime', 'studyYear', 'group', 'disability')
            ->orderBy('students.inclusive')
            ->get();

        return view('logro.student.list-inclusive', [
            'students' => $students,
            'pendingStudents' => $pendingStudents
        ]);

    }

    public function changeToNonInclusive(Request $request)
    {
        $request->validate([
            'student' => ['required', Rule::exists('students', 'id')]
        ]);

        $student = Student::find($request->student);

        $student->update([
            'pre_inclusive' => NULL,
            'inclusive' => NULL
        ]);

        Notify::success(__('Removed from list of inclusives'));
        return back();

    }

    public function psychosocial_update(Request $request, Student $student)
    {
        $request->validate([
            'plays_sports' => ['nullable', 'boolean'],
            'freetime_activity' => ['nullable', 'string', 'max:191'],
            'allergies' => ['nullable', 'string', 'max:191'],
            'medicines' => ['nullable', 'string', 'max:191'],
            'favorite_subjects' => ['nullable', 'string', 'max:191'],
            'most_difficult_subjects' => ['nullable', 'string', 'max:191'],
            'insomnia' => ['nullable', 'boolean'],
            'colic' => ['nullable', 'boolean'],
            'biting_nails' => ['nullable', 'boolean'],
            'sleep_talk' => ['nullable', 'boolean'],
            'nightmares' => ['nullable', 'boolean'],
            'seizures' => ['nullable', 'boolean'],
            'physical_abuse' => ['nullable', 'boolean'],
            'pee_at_night' => ['nullable', 'boolean'],
            'hear_voices' => ['nullable', 'boolean'],
            'fever' => ['nullable', 'boolean'],
            'fears_phobias' => ['nullable', 'boolean'],
            'drug_consumption' => ['nullable', 'boolean'],
            'head_blows' => ['nullable', 'boolean'],
            'desire_to_die' => ['nullable', 'boolean'],
            'see_shadows' => ['nullable', 'boolean'],
            'learning_problems' => ['nullable', 'boolean'],
            'dizziness_fainting' => ['nullable', 'boolean'],
            'school_repetition' => ['nullable', 'boolean'],
            'accidents' => ['nullable', 'boolean'],
            'asthma' => ['nullable', 'boolean'],
            'suicide_attempts' => ['nullable', 'boolean'],
            'constipation' => ['nullable', 'boolean'],
            'stammering' => ['nullable', 'boolean'],
            'hands_sweating' => ['nullable', 'boolean'],
            'sleepwalking' => ['nullable', 'boolean'],
            'nervous_tics' => ['nullable', 'boolean'],
            'sexual_abuse' => ['nullable', 'boolean'],
            'unmotivated_crying' => ['nullable', 'boolean'],
            'chest_pain' => ['nullable', 'boolean'],
            'bullying' => ['nullable', 'boolean'],
            'simat' => ['nullable', 'boolean'],
            'inclusive' => ['nullable', 'boolean'],
            'medical_diagnosis' => ['nullable', 'max:5000'],
            'medical_prediagnosis' => ['nullable', 'max:5000'],
            'risks_vulnerabilities' => ['nullable', 'max:5000'],
        ]);

        $student->update([

            /* informacion psicosocial */
            'plays_sports' => $request->plays_sports,
            'freetime_activity' => $request->freetime_activity,
            'allergies' => $request->allergies,
            'medicines' => $request->medicines,
            'favorite_subjects' => $request->favorite_subjects,
            'most_difficult_subjects' => $request->most_difficult_subjects,
            'insomnia' => $request->insomnia,
            'colic' => $request->colic,
            'biting_nails' => $request->biting_nails,
            'sleep_talk' => $request->sleep_talk,
            'nightmares' => $request->nightmares,
            'seizures' => $request->seizures,
            'physical_abuse' => $request->physical_abuse,
            'pee_at_night' => $request->pee_at_night,
            'hear_voices' => $request->hear_voices,
            'fever' => $request->fever,
            'fears_phobias' => $request->fears_phobias,
            'drug_consumption' => $request->drug_consumption,
            'head_blows' => $request->head_blows,
            'desire_to_die' => $request->desire_to_die,
            'see_shadows' => $request->see_shadows,
            'learning_problems' => $request->learning_problems,
            'dizziness_fainting' => $request->dizziness_fainting,
            'school_repetition' => $request->school_repetition,
            'accidents' => $request->accidents,
            'asthma' => $request->asthma,
            'suicide_attempts' => $request->suicide_attempts,
            'constipation' => $request->constipation,
            'stammering' => $request->stammering,
            'hands_sweating' => $request->hands_sweating,
            'sleepwalking' => $request->sleepwalking,
            'nervous_tics' => $request->nervous_tics,
            'sexual_abuse' => $request->sexual_abuse,
            'unmotivated_crying' => $request->unmotivated_crying,
            'chest_pain' => $request->chest_pain,
            'bullying' => $request->bullying,

            /* evaluación psicosocial */
            'simat' => $request->simat,
            'inclusive' => $request->inclusive,
            'medical_diagnosis' => $request->medical_diagnosis,
            'medical_prediagnosis' => $request->medical_prediagnosis,
            'risks_vulnerabilities' => $request->risks_vulnerabilities,
        ]);

        Notify::success(__('Student updated!'));
        return redirect()->back();
    }

    public function piar_update(Request $request, Student $student)
    {
        $groupSubjects = rtrim($request->groupSubjects, '~');
        $groupSubjects = explode('~', $groupSubjects);

        foreach ($groupSubjects as $gs) {
            $piarStudent = Piar::where('student_id', $student->id)->where('subject_id', $gs)->first();
            if (NULL !== $piarStudent) {
                // update
                $request_annotation = $piarStudent->id . '~' . $gs . '~annotation';
                $piarStudent->update([
                    'annotation' => $request->$request_annotation
                ]);
            } else {
                // create
                $request_annotation = 'null~' . $gs . '~annotation';
                Piar::create([
                    'student_id' => $student->id,
                    'subject_id' => $gs,
                    'annotation' => $request->$request_annotation,
                    'user_id' => Auth::id()
                ]);
            }
        }

        Notify::success(__('PIAR updated!'));
        return redirect()->back();
    }

    public function create_parents_filter(Request $request)
    {
        $Y = SchoolYearController::current_year();

        $hq = $request->headquarters;
        $st = $request->studyTime;
        $sy = $request->studyYear;

        $c = Group::where('school_year_id', $Y->id);

        if (NULL !== $hq)
            $c->where('headquarters_id', $hq);

        if (NULL !== $st)
            $c->where('study_time_id', $st);

        if (NULL !== $sy)
            $c->where('study_year_id', $sy);

        return $c->count();
    }


    /*
     *
     *
     * TRANSFER
     *
     *
     * */
    public function transfer(Student $student)
    {
        $Y = SchoolYearController::current_year();

        $countGroups = Group::where('school_year_id', $Y->id)
            ->where('headquarters_id', $student->headquarters_id)
            ->where('study_time_id', $student->study_time_id)
            ->where('study_year_id', $student->study_year_id)
            ->count();

        return view('logro.student.transfer', [
            'student' => $student,
            'headquarters' => Headquarters::all(),
            'studyTime' => StudyTime::all(),
            'studyYear' => StudyYear::all(),
            'countGroups' => $countGroups
        ]);
    }

    public function transfer_store(Student $student, Request $request)
    {
        $request->validate([
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'studyTime' => ['required', Rule::exists('study_times', 'id')],
            'studyYear' => ['required', Rule::exists('study_years', 'id')],
        ]);

        if (
            $student->headquarters_id == $request->headquarters
            && $student->study_time_id == $request->studyTime
            && $student->study_year_id == $request->studyYear
        ) {

            Notify::info(__('Unchanged!'));
            return redirect()->route('students.show', $student);
        }

        $student->update([
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->studyTime,
            'study_year_id' => $request->studyYear,
            'enrolled' => NULL,
            'enrolled_date' => NULL
        ]);

        Notify::success(__('Transferred student!'));

        if (1 == $request->matriculate) {
            return redirect()->route('students.matriculate', $student->id);
        }

        return redirect()->route('students.show', $student);
    }


    /* INSTRUCTIVE */
    public function data_instructive()
    {
        $headquarters = Headquarters::all();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();

        $documentType = DocumentType::all();
        $cities = City::all();
        $genders = Gender::all();
        $rhs = Rh::all();
        $healthManager = HealthManager::all();
        $sisbenes = Sisben::all();

        return view('logro.student.data-instructive')->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,

            'documentType' => $documentType,
            // 'cities' => $cities,
            // 'genders' => $genders,
            // 'rhs' => $rhs,
            // 'healthManager' => $healthManager,
            // 'sisbenes' => $sisbenes,
        ]);
    }

    public function export_instructive()
    {
        return Excel::download(new StudentsInstructuveExport, __('instructive') . '.xlsx');
    }

    public function export_noenrolled()
    {
        $Y = SchoolYearController::current_year();

        $students = Student::singleData()
            ->with('headquarters', 'studyTime', 'studyYear', 'files')
            ->whereNull('enrolled')
            ->where('school_year_create', '<=', $Y->id)
            ->get();

        return Excel::download(new StudentsWithFiles($students), __('no-enrolled') . '.xlsx');
    }

    public function export_enrolled_view()
    {
        return view('logro.student.export.enrolled', [
            'headquarters' => Headquarters::all(),
            'studyTimes' => StudyTime::all(),
            'studyYears' => StudyYear::all(),
        ]);
    }

    public function export_enrolled_generate(Request $request)
    {
        $request->validate([
            'headquarters' => ['required'],
            'headquarters.*' => ['required', 'exists:headquarters,id'],

            'study_time' => ['required'],
            'study_time.*' => ['required', 'exists:study_times,id'],

            'study_year' => ['required'],
            'study_year.*' => ['required', 'exists:study_years,id'],
        ], [
            '*.*.exists' => __('An error has occurred'),
        ]);

        $attributes = [
            'headquarters' => $request->has('columns.headquarters'),
            'study_time' => $request->has('columns.study_time'),
            'study_year' => $request->has('columns.study_year'),
            'group' => $request->has('columns.group'),
            'document' => $request->has('columns.document'),
            'email' => $request->has('columns.email'),
            'telephone' => $request->has('columns.telephone'),
            'country' => $request->has('columns.country'),
            'bith_city' => $request->has('columns.bith_city'),
            'birthdate' => $request->has('columns.birthdate'),
            'age' => $request->has('columns.age'),
            'gender' => $request->has('columns.gender'),
            'rh' => $request->has('columns.rh'),
            'zone' => $request->has('columns.zone'),
            'residence_city' => $request->has('columns.residence_city'),
            'address' => $request->has('columns.address'),
            'social_stratum' => $request->has('columns.social_stratum'),
            'dwelling_type' => $request->has('columns.dwelling_type'),
            'neighborhood' => $request->has('columns.neighborhood'),
            'sisben' => $request->has('columns.sisben'),
            'health_manager' => $request->has('columns.health_manager'),
            'tutor' => $request->has('columns.tutor')
        ];

        return Excel::download(new StudentsEnrolledExport($attributes, $request), __('enrolled') . '.xlsx');
    }

    public function import()
    {
        return view('logro.student.import');
    }

    public function import_store(Request $request)
    {

        $request->validate([
            'file' => ['required', 'file', 'max:5000', 'mimes:xls,xlsx']
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        Notify::success(__('Loaded Excel!'));
        return redirect()->back();
    }


    /* tratamiento de firmas */
    private function signatures(Student $student, $request)
    {
        if (NULL !== $request->signature_tutor) {

            if ($request->hasFile('fileSigLoad-tutor')) {

                /* Para que la imagen no exceda los limites para el resize con DomPDF */
                $request->validate([
                    'fileSigLoad-tutor' => ['dimensions:max_width=5000,max_height=5000']
                ], [
                    'dimensions' => __('The signature must not exceed 5000px')
                ]);

                $sigPath = self::signature_image_upload($student->id, $request->file('fileSigLoad-tutor'));
            } else {
                $sigPath = self::signature_upload($student->id, $request->signature_tutor);
            }

            $student->forceFill([
                'signature_tutor' => $sigPath
            ])->save();
        }

        if (NULL !== $request->signature_student) {

            if ($request->hasFile('fileSigLoad-student')) {

                /* Para que la imagen no exceda los limites para el resize con DomPDF */
                $request->validate([
                    'fileSigLoad-student' => ['dimensions:max_width=5000,max_height=5000']
                ], [
                    'dimensions' => __('The signature must not exceed 5000px')
                ]);

                $sigPath = self::signature_image_upload($student->id, $request->file('fileSigLoad-student'));
            } else {
                $sigPath = self::signature_upload($student->id, $request->signature_student);
            }
            $student->forceFill([
                'signature_student' => $sigPath
            ])->save();
        }
    }

    private function signature_upload($student_id, $sig)
    {
        $path = "app/students/$student_id/signatures/";

        if (!File::isDirectory(public_path($path))) {
            File::makeDirectory(public_path($path), 0755, true, true);
        }

        $sigUrl = $path . Str::random(50) . '.' . 'png';

        $sig = str_replace('data:image/png;base64,', '', $sig);
        $sig = str_replace(' ', '+', $sig);

        $save = File::put(public_path($sigUrl), base64_decode($sig));
        if ($save)
            return $sigUrl;

        return null;
    }

    private function signature_image_upload($student_id, $imageSignature)
    {

        $path = $imageSignature->store('students/'.$student_id.'/signatures', 'public');
        if ($path)
            return config('filesystems.disks.public.url') . '/' . $path;

        return null;
    }

    public function signature_delete(Request $request, Student $student)
    {
        $request->validate([
            'delete_signature' => ['required', 'in:student,tutor']
        ]);

        if ($request->delete_signature === 'student') {

            if (File::isFile(public_path($student->signature_student))) {
                File::delete(public_path($student->signature_student));

                $student->forceFill([
                    'signature_student' => NULL
                ])->save();


                Notify::success(__('Signature deleted!'));
            }
        }
        if ($request->delete_signature === 'tutor') {

            if (File::isFile(public_path($student->signature_tutor))) {
                File::delete(public_path($student->signature_tutor));

                $student->forceFill([
                    'signature_tutor' => NULL
                ])->save();


                Notify::success(__('Signature deleted!'));
            }
        }

        return back();
    }


    /* PDF */
    public function pdf_matriculate(Student $student = null)
    {
        if ('PARENT' == UserController::role_auth() && $student) {

            $parentCheck = \App\Models\PersonCharge::where('email', auth()->user()->email)->where('student_id', $student->id)->count();
            if (!$parentCheck) {
                Notify::fail("No permitido");
                return back();
            }

        }

        if ('STUDENT' == UserController::role_auth())
        {
            return self::pdfMatriculateGenerate(Auth::id());
        }

        return self::pdfMatriculateGenerate($student->id);
    }

    public function pdf_certificate(Student $student = null)
    {
        if ('PARENT' == UserController::role_auth() && $student) {

            $parentCheck = \App\Models\PersonCharge::where('email', auth()->user()->email)->where('student_id', $student->id)->count();
            if (!$parentCheck) {
                Notify::fail("No permitido");
                return back();
            }

        }

        if ('STUDENT' == UserController::role_auth())
        {
            $student = Student::find(Auth::id());
        }

        if (is_null($student->enrolled)) {
            Notify::fail(__('El estudiante no ha sido matriculado'));
            return back();
        }

        return $this->pdfCertificateGenerate($student);
    }

    public function pdf_observations(Student $student = null)
    {
        if ('PARENT' == UserController::role_auth() && $student) {

            $parentCheck = \App\Models\PersonCharge::where('email', auth()->user()->email)->where('student_id', $student->id)->count();
            if (!$parentCheck) {
                Notify::fail("No permitido");
                return back();
            }

        }

        if ('STUDENT' == UserController::role_auth())
        {
            $student = Student::find(Auth::id());
        }

        if (is_null($student->enrolled)) {
            Notify::fail(__('El estudiante no ha sido matriculado'));
            return back();
        }

        return $this->pdfObservationsGenerate($student);
    }

    public function pdf_carnet(Student $student = null)
    {
        if ('PARENT' == UserController::role_auth() && $student) {

            $parentCheck = \App\Models\PersonCharge::where('email', auth()->user()->email)->where('student_id', $student->id)->count();
            if (!$parentCheck) {
                Notify::fail("No permitido");
                return back();
            }

        }

        if ('STUDENT' == UserController::role_auth())
        {
            $student = Student::find(Auth::id());
        }

        if (is_null($student->enrolled)) {
            Notify::fail(__('El estudiante no ha sido matriculado'));
            return back();
        }

        return $this->pdfCarnetGenerate($student);
    }

    public function pdf_report_grades(Student $student = null)
    {
        if ('PARENT' == UserController::role_auth() && $student) {

            $parentCheck = \App\Models\PersonCharge::where('email', auth()->user()->email)->where('student_id', $student->id)->count();
            if (!$parentCheck) {
                Notify::fail("No permitido");
                return back();
            }

        }

        if ('STUDENT' == UserController::role_auth())
        {
            $student = Student::find(Auth::id());
        }

        if ($student->isRetired()) return \App\Http\Controllers\GradeController::reportGradesStudentRetired($student);

        if (is_null($student->enrolled)) {
            Notify::fail(__('El estudiante no ha sido matriculado'));
            return back();
        }

        return $this->pdfGradeReportGenerate($student);
    }


    private function pdfMatriculateGenerate($student)
    {
        $SCHOOL = SchoolController::myschool()->getData();
        $date = Carbon::now()->format('d/m/Y');

        $student = Student::find($student);
        $tutor = PersonCharge::select('id', 'name')->where('id', $student->person_charge)->first();

        $pdf = Pdf::loadView('logro.pdf.matriculate', [
            'SCHOOL' => $SCHOOL,
            'date' => $date,
            'student' => $student,
            'tutor' => $tutor,
            'nationalCountry' => NationalCountry::country()
        ])->setPaper('letter', 'portrait')->setOption('dpi', 72);


        return $pdf->download($student->getFullName() .'.pdf');
    }

    private function pdfCertificateGenerate($student)
    {
        $SCHOOL = SchoolController::myschool()->getData();
        $date = Carbon::now();

        $opciones_ssl = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $extencion = pathinfo($SCHOOL->badge, PATHINFO_EXTENSION);
        $data = file_get_contents($SCHOOL->badge, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($data);
        $badge = [
            'base64' => $img_base_64,
            'extencion' => $extencion
        ];

        $pdf = Pdf::loadView('logro.pdf.student-certificate', [
            'SCHOOL' => $SCHOOL,
            'badge' => $badge,
            'date' => $date,
            'student' => $student
        ]);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('dpi', 72);

        return $pdf->download(__('Certificate') .' - '. $student->getFullName() .'.pdf');
    }

    private function pdfObservationsGenerate($student)
    {
        $SCHOOL = SchoolController::myschool()->getData();

        $tutor = PersonCharge::select('id', 'name')->where('id', $student->person_charge)->first();

        $matricula = Pdf::loadView('logro.pdf.matriculate', [
            'SCHOOL' => $SCHOOL,
            'date' => now()->format('d/m/Y'),
            'student' => $student,
            'tutor' => $tutor,
            'nationalCountry' => NationalCountry::country()
        ])->setPaper('letter', 'portrait')->setOption('dpi', 72)->output();

        $observer = Pdf::loadView('logro.pdf.student-observations', [
            'SCHOOL' => $SCHOOL,
            'date' => now()->format('d/m/Y'),
            'student' => $student
        ])->setPaper('letter', 'landscape')->setOption('dpi', 72)->output();

        $merge = new Merger();
        $merge->addRaw($matricula);
        $merge->addRaw($observer);

        $nameFile = 'app/' . Str::uuid() . '.pdf';

        file_put_contents($nameFile, $merge->merge());

        return response()->download(
                public_path($nameFile),
                __('Observer') .' - '. $student->getFullName() .'.pdf'
            )->deleteFileAfterSend();

    }

    private function pdfCarnetGenerate($student)
    {
        $SCHOOL = SchoolController::myschool()->getData();

        $pdf = Pdf::loadView('logro.pdf.student-carnet', [
            'SCHOOL' => $SCHOOL,
            'student' => $student
        ])->setPaper('letter', 'portrait')->setOption('dpi', 72);


        return $pdf->download('Carnet - '. $student->getFullName() .'.pdf');
    }

    private function pdfGradeReportGenerate($student)
    {
        $Y = SchoolYearController::current_year();
        $period = Period::where('school_year_id', $Y->id)
            ->where('study_time_id', $student->group->study_time_id)
            ->where('end', '<=', today()->format('Y-m-d'))
            ->orderByDesc('ordering')->first();

        if (!$period) {
            Notify::fail('Sin notas para mostrar');
            return back();
        }

        return (new GradeController)->reportForGroupByPeriod($period, $student->group, $student);
    }

    public function send_delete_code(Student $student, Request $request)
    {
        if (is_null(SchoolController::myschool()->securityEmail()))
        {
            return ['status' => false, 'message' => 'fail|' . __('No security email exists')];
        }

        $generateCodeRemoval = StudentRemovalCodeController::generate($student);

        if ($generateCodeRemoval === TRUE)
            return ['status' => true, 'message' => 'info|' . __("A code was sent to the security email")];
        else
            return ['status' => false, 'message' => 'fail|' . $generateCodeRemoval];
    }

    public function withdrawn()
    {
        $Y = SchoolYearController::current_year();

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'institutional_email',
            'document_type_code',
            'document',
            'status',
            'inclusive',
            'headquarters_id',
            'study_time_id',
            'study_year_id',
            'created_at'
        )->with('headquarters', 'studyYear', 'studyTime')
        ->where('status', 'retired')
        ->get();


        return view('logro.student.withdrawn')->with('students', $students);
    }

    public function withdraw(Student $student)
    {

        $Y = SchoolYearController::current_year();

        DB::beginTransaction();

        try {

            $student->reportBooks()->delete();
            $student->files()->delete();

            if (!is_null($student->enrolled)) {
                $student->groupYear()->whereHas('group', fn($group) => $group->where('school_year_id', $Y->id))->delete();
                $student->groupOfSpecialty()?->whereHas('group', fn($group) => $group->where('school_year_id', $Y->id))->delete();

                \App\Models\GroupStudentRetired::create([
                    'student_id' => $student->id,
                    'group_id' => $student->group_id
                ]);

                if ( !is_null($student->group_specialty_id) ) {
                    \App\Models\GroupStudentRetired::create([
                        'student_id' => $student->id,
                        'group_id' => $student->group_specialty_id
                    ]);
                }
            }

            $student->forceFill([
                'group_id' => null,
                'group_specialty_id' => null,
                'enrolled_date' => null,
                'enrolled' => null,
                'status' => 'retired',
                'signature_tutor' => null,
                'signature_student' => null,
                'wizard_documents' => null,
                'wizard_report_books' => null,
                'wizard_person_charge' => null,
                'wizard_personal_info' => null,
                'wizard_complete' => null
            ])->save();

            $student->user->forceFill([
                'avatar' => null,
                'email_verified_at' => null,
                'password' => null,
                'remember_token' => null,
                'active' => 0
            ])->save();

        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('An error has occurred'));
            return back();
        }

        DB::commit();

        if (File::isDirectory(public_path('app/students/' . $student->id . '/'))) {
            File::deleteDirectory(public_path('app/students/' . $student->id . '/'));
        }

        Notify::success('Estudiante retirado!');
        return back();
    }

    public function delete(Student $student, Request $request)
    {
        if (is_null(SchoolController::myschool()->securityEmail()))
        {
            Notify::fail(__('No security email exists'));
            return redirect()->back();
        }

        if ($student->groupStudents()->count() === 0)
        {

            $confirmRemove = StudentRemovalCode::where('student_id', $student->id)
                ->where('code', $request->code_confirm)->first();
            if ( $confirmRemove === NULL )
            {
                Notify::fail(__('Code invalid'));
                return redirect()->back();
            }


            $student->files()->delete();
            $pathStudent = public_path('app/students/'.$student->id.'/');
            if (File::isDirectory($pathStudent)) {
                File::deleteDirectory($pathStudent);
            }

            if (NULL !== $student->mother)
            {
                UserController::delete_user($student->mother->id);
            }

            if (NULL !== $student->father)
            {
                UserController::delete_user($student->father->id);
            }

            if (NULL !== $student->tutor)
            {
                UserController::delete_user($student->tutor->id);
            }

            $student->user()->delete();
            $student->delete();

            Notify::success(__('Student deleted!'));
            return redirect()->route('students.no_enrolled');
        }

        Notify::fail(__('Not allowed'));
        return redirect()->back();
    }

    public function activate(Student $student)
    {
        if (!$student->isRetired()) {
            Notify::fail(__('The student is already active'));
            return back();
        }

        $student->update([
            'status' => null
        ]);
        $student->user->forceFill([
            'email_verified_at' => now(),
            'active' => 1
        ])->save();

        Notify::success(__('Student activated!'));
        return back();
    }
}
