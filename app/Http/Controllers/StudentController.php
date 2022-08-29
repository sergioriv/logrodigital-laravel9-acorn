<?php

namespace App\Http\Controllers;

use App\Exports\StudentsInstructuveExport;
use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\UserController;
use App\Http\Controllers\support\WAController;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Imports\StudentsImport;
use App\Models\City;
use App\Models\Country;
use App\Models\Disability;
use App\Models\DocumentType;
use App\Models\DwellingType;
use App\Models\EconomicDependence;
use App\Models\EthnicGroup;
use App\Models\Gender;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Headquarters;
use App\Models\HealthManager;
use App\Models\IcbfProtectionMeasure;
use App\Models\Kinship;
use App\Models\LinkageProcess;
use App\Models\PersonCharge;
use App\Models\Piar;
use App\Models\Religion;
use App\Models\Rh;
use App\Models\School;
use App\Models\Sisben;
use App\Models\Student;
use App\Models\StudentFile;
use App\Models\StudentFileType;
use App\Models\StudyTime;
use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('can:students.index')->except('show', 'update','wizard_documents_request','wizard_person_charge_request','wizard_personal_info_request','wizard_complete_request');
        $this->middleware('can:students.import')->only('data_instructive', 'export_instructive', 'import', 'import_store');
        $this->middleware('can:students.create')->only('create');
        $this->middleware('can:students.matriculate')->only('matriculate', 'matriculate_update', 'create_parents_filter');
        $this->middleware('can:students.info')->only('show', 'update');
        $this->middleware('can:students.psychosocial')->only('psychosocial_update', 'piar_update');
        $this->middleware(YearCurrentMiddleware::class)->only('matriculate', 'matriculate_update');
    }

    /*
     * PRE-REGISTRATION SECTION
     */
    public function no_enrolled()
    {
        $Y = SchoolYearController::current_year();

        $fn_g = fn ($g) => $g->where('school_year_id', $Y->id);

        $fn_gs = fn ($gs) =>
        $gs->with(['group' => $fn_g])
            ->whereHas('group', $fn_g);

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'father_last_name',
            'mother_last_name',
            'institutional_email',
            'status',
            'inclusive',
            'headquarters_id',
            'study_time_id',
            'study_year_id'
        )->with('headquarters', 'studyTime', 'studyYear')
            ->where('school_year_create', '<=', $Y->id)
            ->whereNot(
                fn ($q) =>
                $q->whereHas('groupYear', $fn_gs)
                    ->with(['groupYear' => $fn_gs])
            );

        if (0 === $Y->available) {
            $students->whereNull('enrolled');
        }


        $students->orderBy('father_last_name')
            ->orderBy('mother_last_name');

        return view('logro.student.noenrolled')->with('students', $students->get());
    }

    public function create()
    {
        $Y = SchoolYearController::current_year();

        $documentType = DocumentType::orderBy('foreigner')->get();
        $headquarters = Headquarters::all();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        $countGroups = Group::where('school_year_id', $Y->id)->count();
        $cities = City::all();
        $countries = Country::all();
        return view("logro.student.create")->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'cities' => $cities,
            'countries' => $countries,
            'documentType' => $documentType,
            'countGroups' => $countGroups
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:191'],
            'secondName' => ['nullable', 'string', 'max:191'],
            'fatherLastName' => ['required', 'string', 'max:191'],
            'motherLastName' => ['nullable', 'string', 'max:191'],
            'document_type' => ['required', Rule::exists('document_types', 'code')],
            'document' => ['required', 'max:20', Rule::unique('students', 'document')],
            'institutional_email' => ['required', 'max:191', 'email', Rule::unique('users', 'email')],
            'telephone' => ['nullable', 'string', 'max:20'],
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'studyTime' => ['required', Rule::exists('study_times', 'id')],
            'studyYear' => ['required', Rule::exists('study_years', 'id')],
            'birth_city' => ['nullable', Rule::exists('cities', 'id')],
            'country' => ['nullable', Rule::exists('countries', 'id')],
            'birthdate' => ['nullable', 'date'],
        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        $user = UserController::_create($user_name, $request->institutional_email, 7);

        $Y = SchoolYearController::current_year();

        /* DATOS PAIS DE ORIGEN */
        $docType = DocumentType::find($request->document_type);
        $expedition_city = NULL;
        if (1 == $docType->foreigner) {
            $request->birth_city = NULL;
            $expedition_city = 149;
        } else {
            $request->country = NULL;
        }

        Student::create([
            'id' => $user->id,
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'father_last_name' => $request->fatherLastName,
            'mother_last_name' => $request->motherLastName,
            'institutional_email' => $request->institutional_email,
            'telephone' => $request->telephone,
            'document_type_code' => $request->document_type,
            'document' => $request->document,
            'birth_city_id' => $request->birth_city,
            'country_id' => $request->country,
            'birthdate' => $request->birthdate,
            'expedition_city_id' => $expedition_city,
            'school_year_create' => $Y->id,
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->studyTime,
            'study_year_id' => $request->studyYear,
            'status' => 'new',
            'data_treatment' => TRUE
        ]);

        if (1 == $request->matriculate) {
            return redirect()->route('students.matriculate', $user->id)->with(
                ['notify' => 'success', 'title' => __('Student created!')],
            );
        }

        return redirect()->route('students.no_enrolled')->with(
            ['notify' => 'success', 'title' => __('Student created!')],
        );
    }

    /*
     * WIZARD START
     */
    public function wizard_documents(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {
            // $student = Student::find(Auth::user()->id);
            $studentFileTypes = StudentFileType::with([
                'studentFile' => fn ($files) => $files->where('student_id', $student->id)
            ]);
            if (NULL === $student->disability_id || 1 === $student->disability_id)
                $studentFileTypes->where('inclusive', 0);

            return view('logro.student.wizard-documents')->with([
                'student' => $student,
                'studentFileTypes' => $studentFileTypes->get()
            ]);
        } else
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
    }
    public function wizard_documents_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail( Auth::user()->id );
            if ($request->docsFails > 0) {
                return redirect()->back()->withErrors(["custom" => __("documents are missing to upload")]);
            }

            $student->forceFill([
                'wizard_documents' => TRUE
            ])->save();

            return redirect()->back()->with('student', $student);
        } else {
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
        }
    }
    public function wizard_person_charge(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {
            // $student = Student::find(Auth::user()->id);
            $cities = City::all();
            $kinships = Kinship::all();

            return view('logro.student.wizard-person-charge')->with([
                'student' => $student,
                'cities' => $cities,
                'kinships' => $kinships
            ]);
        } else
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
    }
    public function wizard_person_charge_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail( Auth::user()->id );

            $person_charge = new PersonChargeController;
            return $person_charge->update($student, $request, TRUE);

        } else {
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
        }
    }
    public function wizard_personal_info(Student $student)
    {
        if ('STUDENT' === UserController::role_auth()) {
            // $student = Student::find(Auth::user()->id);

            $documentType = DocumentType::orderBy('foreigner')->get();
            $cities = City::all();
            $countries = Country::all();
            $genders = Gender::all();
            $rhs = Rh::all();
            $healthManager = HealthManager::all();
            $sisbenes = Sisben::all();
            $dwellingTypes = DwellingType::all();
            $disabilities = Disability::all();

            return view('logro.student.wizard-personal-info')->with([
                'student' => $student,
                'documentType' => $documentType,
                'cities' => $cities,
                'countries' => $countries,
                'genders' => $genders,
                'rhs' => $rhs,
                'healthManager' => $healthManager,
                'sisbenes' => $sisbenes,
                'dwellingTypes' => $dwellingTypes,
                'disabilities' => $disabilities
            ]);
        } else
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
    }
    public function wizard_personal_info_request(Request $request)
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail( Auth::user()->id );

            return self::update($request, $student, TRUE);

        } else {
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
        }
    }
    public function wizard_complete()
    {
        return view('logro.student.wizard-complete');
    }
    public function wizard_complete_request()
    {
        if ('STUDENT' === UserController::role_auth()) {

            $student = Student::findOrFail( Auth::user()->id );

            $student->forceFill([
                'wizard_complete' => TRUE
            ])->save();

            return self::show($student);

        } else {
            return redirect()->route('dashboard')->with(
                ['notify' => 'fail', 'title' => __('Unauthorized!')],
            );
        }
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

        $fn_g = fn ($g) => $g->where('school_year_id', $Y->id);

        $fn_gs = fn ($gs) =>
        $gs->with(['group' => $fn_g])
            ->whereHas('group', $fn_g);

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'father_last_name',
            'mother_last_name',
            'institutional_email',
            'status',
            'inclusive'
        )
            ->whereHas('groupYear', $fn_gs)
            ->with(['groupYear' => $fn_gs])
            ->orderBy('father_last_name')
            ->orderBy('mother_last_name')
            ->get();


        return view('logro.student.enrolled')->with('students', $students);
    }

    public function matriculate($student_id)
    {
        $Y = SchoolYearController::current_year();

        $student = Student::select(
            'id',
            'first_name',
            'second_name',
            'father_last_name',
            'mother_last_name',
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

        $groups = Group::where('school_year_id', $Y->id)
            ->where('headquarters_id', $student->headquarters_id)
            ->where('study_time_id', $student->study_time_id)
            ->where('study_year_id', $student->study_year_id)
            ->withCount(['groupStudents' => fn ($GS) => $GS->where('student_id', $student->id)])
            ->get();

        if (0 === count($groups))
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('No groups')],
            );

        return view('logro.student.matriculate')->with([
            'student' => $student,
            'groups' => $groups
        ]);
    }

    public function matriculate_update(Request $request, Student $student)
    {
        $request->validate([
            'group' => ['required', Rule::exists('groups', 'id')]
        ]);

        $group = Group::find($request->group);

        if (
            $group->headquarters_id === $student->headquarters_id
            && $group->study_time_id === $student->study_time_id
            && $group->study_year_id === $student->study_year_id
        ) {

            if ($student->group_id != $request->group) {

                $groupStudentExist = GroupStudent::where('group_id', $student->group_id)->where('student_id', $student->id)->first();

                if (NULL === $groupStudentExist) {
                    GroupStudent::create([
                        'group_id' => $request->group,
                        'student_id' => $student->id
                    ]);

                    $group->update([
                        'student_quantity' => ++$group->student_quantity
                    ]);

                    $student->update([
                        'group_id' => $group->id,
                        'enrolled_date' => now(),
                        'enrolled' => TRUE
                    ]);

                    /* Send message WhatsApp */
                    // self::send_msg($student, $group);

                    /* Send mail to Email Person Charge */
                    SmtpMail::sendEmailEnrollmentNotification($student, $group);

                    return redirect()->route('students.show', $student)->with(
                        ['notify' => 'success', 'title' => __('Student matriculate!')],
                    );
                } else {
                    $groupStudentExist->update([
                        'group_id' => $request->group
                    ]);

                    $leaveGroup = Group::find($student->group_id);
                    $leaveGroup->update([
                        'student_quantity' => --$leaveGroup->student_quantity
                    ]);

                    $newGroup = Group::find($request->group);
                    $newGroup->update([
                        'student_quantity' => ++$newGroup->student_quantity
                    ]);

                    $student->update([
                        'group_id' => $group->id,
                        'enrolled_date' => now(),
                        'enrolled' => TRUE
                    ]);

                    return redirect()->route('students.show', $student)->with(
                        ['notify' => 'success', 'title' => __('Changed group!')],
                    );
                }

                return redirect()->route('students.enrolled');
            } else {
                return redirect()->route('students.show', $student)->with(
                    ['notify' => 'info', 'title' => __('Unchanged!')],
                );
            }
        } else {
            return redirect()->back()->withErrors(__("Unexpected Error"));
        }
    }

    public function show(Student $student)
    {
        $Y = SchoolYearController::current_year();
        $YAvailable = SchoolYearController::available_year();

        /* Group x Subjects [teacher, piar] START */
        if (1 === $student->inclusive) {
            $groupsStudent = Group::whereHas(
                'groupStudents',
                fn ($groupStudents) => $groupStudents->where('student_id', $student->id)
            )->with([
                'studyYear' =>
                fn ($groupSY) => $groupSY->with([
                    'studyYearSubject' =>
                    fn ($groupSYS) => $groupSYS->with([
                        'subject' =>
                        fn ($groupSJ) => $groupSJ->with('teacherSubjectGroups')->with([
                            'piarOne' =>
                            fn ($studentPiar) => $studentPiar->where('student_id', $student->id)
                        ])
                    ])
                ])
            ])->orderByDesc('id')->get();
        } else {
            $groupsStudent = [];
        }
        /* Group x Subjects [teacher, piar] END */

        $documentType = DocumentType::orderBy('foreigner')->get();
        $cities = City::all();
        $countries = Country::all();
        $genders = Gender::all();
        $rhs = Rh::all();
        $healthManager = HealthManager::all();
        $sisbenes = Sisben::all();
        $ethnicGroups = EthnicGroup::all();
        $dwellingTypes = DwellingType::all();
        $disabilities = Disability::all();
        $icbfProtections = IcbfProtectionMeasure::all();
        $linkageProcesses = LinkageProcess::all();
        $religions = Religion::all();
        $economicDependences = EconomicDependence::all();

        $kinships = Kinship::all();
        $studentFileTypes = StudentFileType::with([
            'studentFile' => function ($files) use ($student) {
                $files->where('student_id', $student->id);
            }
        ]);
        if (NULL === $student->disability_id || 1 === $student->disability_id) {
            $studentFileTypes->where('inclusive', 0);
        }

        return view('logro.student.profile')->with([
            'Y' => $Y,
            'YAvailable' => $YAvailable->id,
            'student' => $student,
            'documentType' => $documentType,
            'cities' => $cities,
            'countries' => $countries,
            'genders' => $genders,
            'rhs' => $rhs,
            'healthManager' => $healthManager,
            'sisbenes' => $sisbenes,
            'ethnicGroups' => $ethnicGroups,
            'dwellingTypes' => $dwellingTypes,
            'disabilities' => $disabilities,
            'icbfProtections' => $icbfProtections,
            'linkageProcesses' => $linkageProcesses,
            'religions' => $religions,
            'economicDependences' => $economicDependences,
            'kinships' => $kinships,
            'studentFileTypes' => $studentFileTypes->get(),
            'groupsStudent' => $groupsStudent
        ]);
    }

    public function update(Request $request, Student $student, $wizard = false)
    {
        $required = 'nullable';
        $data_treatment = $student->data_treatment;
        if ('STUDENT' === UserController::role_auth()) {
            $required = 'required';
            $data_treatment = $request->data_treatment;
            if ($request->docsFails > 0) {
                return redirect()->back()->withErrors(["custom" => __("documents are missing to upload")]);
            }

            self::signatures($student, $request->signature_tutor, $request->signature_student);
        }
        $request->validate([
            'firstName' => ['required', 'string', 'max:191'],
            'secondName' => ['nullable', 'string', 'max:191'],
            'fatherLastName' => ['required', 'string', 'max:191'],
            'motherLastName' => ['nullable', 'string', 'max:191'],
            'telephone' => [$required, 'string', 'max:20'],
            'document_type' => ['required', Rule::exists('document_types', 'code')],
            'document' => ['required', 'string', 'max:20', Rule::unique('students', 'document')->ignore($student->id)],
            'expedition_city' => [$required, Rule::exists('cities', 'id')],
            'number_siblings' => [$required, 'numeric', 'max:200', 'min:0'],
            'birth_city' => [$required, Rule::exists('cities', 'id')],
            'country' => ['nullable', Rule::exists('countries', 'id')],
            'birthdate' => [$required, 'date'],
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
            'data_treatment' => ['nullable', 'boolean']
        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        UserController::_update($student->id, $user_name);

        /* DATOS PAIS DE ORIGEN */
        $docType = DocumentType::find($request->document_type);
        if (1 == $docType->foreigner) {
            $request->birth_city = NULL;
        } else {
            $request->country = NULL;
        }

        /* COMPROBACION DE DCERTIFICADO DE DISCAPACIDAD */
        if ($request->hasFile('disability_certificate') && $request->disability > 1) {
            $disability_file = self::upload_disability_certificate($request, $student);
            if (FALSE === $disability_file) {
                $request->disability = NULL;
            }
        }

        $student->update([
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'father_last_name' => $request->fatherLastName,
            'mother_last_name' => $request->motherLastName,
            'document_type_code' => $request->document_type,
            'document' => $request->document,
            'telephone' => $request->telephone,
            'expedition_city_id' => $request->expedition_city,
            'birth_city_id' => $request->birth_city,
            'country_id' => $request->country,
            'birthdate' => $request->birthdate,
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

            /* politica de tratamiento de datos */
            'data_treatment' => $data_treatment
        ]);

        if ( $wizard === TRUE )
        {
            $student->forceFill([
                'wizard_personal_info' => TRUE
            ])->save();

            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with(
                ['notify' => 'success', 'title' => __('Student updated!')],
            );
        }
    }

    public function psychosocial_update(Request $request, Student $student)
    {
        $request->validate([
            'ethnic_group' => ['nullable', Rule::exists('ethnic_groups', 'id')],
            'conflict_victim' => ['nullable', 'boolean'],
            'origin_school' => ['nullable', 'string'],
            'icbf_protection' => ['nullable', Rule::exists('icbf_protection_measures', 'id')],
            'foundation_beneficiary' => ['nullable', 'boolean'],
            'linked_process' => ['nullable', Rule::exists('linkage_processes', 'id')],
            'religion' => ['nullable', Rule::exists('religions', 'id')],
            'economic_dependence' => ['nullable', Rule::exists('economic_dependences', 'id')],
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
            'see_strange_things' => ['nullable', 'boolean'],
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
            'simat' => ['nullable', 'boolean'],
            'inclusive' => ['nullable', 'boolean'],
            'psyc_evaluation' => ['nullable', 'string'],
            'psyc_recommendations' => ['nullable', 'string'],
            'psyc_student_family' => ['nullable', 'string']
        ]);

        $student->update([
            /* informacion complementaria */
            'ethnic_group_id' => $request->ethnic_group,
            'conflict_victim' => $request->conflict_victim,
            'origin_school' => $request->origin_school,
            'ICBF_protection_measure_id' => $request->icbf_protection,
            'foundation_beneficiary' => $request->foundation_beneficiary,
            'linked_to_process_id' => $request->linked_process,
            'religion_id' => $request->religion,
            'economic_dependence_id' => $request->economic_dependence,

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
            'see_strange_things' => $request->see_strange_things,
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

            /* evaluación psicosocial */
            'simat' => $request->simat,
            'inclusive' => $request->inclusive,
            'psyc_evaluation' => $request->psyc_evaluation,
            'psyc_recommendations' => $request->psyc_recommendations,
            'psyc_student_family' => $request->psyc_student_family
        ]);

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
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
                    'user_id' => Auth::user()->id
                ]);
            }
        }

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('PIAR updated!')],
        );
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

    private function upload_disability_certificate($request, $student)
    {
        $request->file_type = StudentFileType::select('id')->where('inclusive', 1)->first()->id; //certificado de discapacidad
        $path_file = StudentFileController::upload_file($request, 'disability_certificate', $student->id);

        $student_file = StudentFile::where('student_id', $student->id)
            ->where('student_file_type_id', $request->file_type)
            ->first();

        if ($student_file === NULL) {
            StudentFile::create([
                'student_id' => $student->id,
                'student_file_type_id' => $request->file_type,
                'url' => config('app.url') . '/' . $path_file,
                'url_absolute' => $path_file,
                'checked' => NULL,
                'creation_user_id' => Auth::user()->id
            ]);
            return true;
        } else {

            if ($request->hasFile('disability_certificate'))
                File::delete(public_path($student_file->url_absolute));

            $renewed = $student_file->approval_date === NULL ? FALSE : TRUE;
            $student_file->update([
                'url' => config('app.url') . '/' . $path_file,
                'url_absolute' => $path_file,
                'renewed' => $renewed,
                'checked' => NULL,
                'creation_user_id' => Auth::user()->id

            ]);
            return true;
        }

        return false;
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

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Loaded Excel!')],
        );
    }


    /* tratamiento de firmas */
    private function signatures(Student $student, $sigTutor, $sigStudent)
    {
        if (NULL !== $sigTutor) {
            $sigPath = self::signature_upload($student->id, $sigTutor);
            $student->forceFill([
                'signature_tutor' => $sigPath
            ])->save();
        }

        if (NULL !== $sigStudent) {
            $sigPath = self::signature_upload($student->id, $sigStudent);
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

        File::put(public_path($sigUrl), base64_decode($sig));
        return $sigUrl;
    }

    private function send_msg($student, $group)
    {
        if ($student->person_charge !== NULL) {
            $tutor = PersonCharge::select('id', 'cellphone')->where('student_id', $student->id)->where('kinship_id', $student->person_charge)->first();

            if ($tutor->cellphone !== NULL && Str::length($tutor->cellphone) == 10) {
                $school = School::find(1);

                $msg = "El estudiante, " .
                    $student->getFullName() .
                    ", ha sido matriculado en el grupo *" . $group->studyYear->name . ": " . $group->name . "*" .
                    " del colegio, *" . $school->name . "*";

                $message = new WAController($msg, $tutor->cellphone);
                $message->send();
            }
        }
    }
}
