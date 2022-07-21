<?php

namespace App\Http\Controllers;

use App\Exports\StudentsInstructuveExport;
use App\Http\Controllers\support\UserController;
use App\Imports\StudentsImport;
use App\Models\City;
use App\Models\Disability;
use App\Models\DocumentType;
use App\Models\DwellingType;
use App\Models\EconomicDependence;
use App\Models\EthnicGroup;
use App\Models\Gender;
use App\Models\Headquarters;
use App\Models\HealthManager;
use App\Models\IcbfProtectionMeasure;
use App\Models\Kinship;
use App\Models\LinkageProcess;
use App\Models\OriginSchool;
use App\Models\Religion;
use App\Models\Rh;
use App\Models\Sisben;
use App\Models\Student;
use App\Models\StudentFileType;
use App\Models\StudyTime;
use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /*
     * PRE-REGISTRATION SECTION
     */
    public function no_enrolled()
    {
        $Y = SchoolYearController::current_year();

        $fn_g = fn($g) => $g->where('school_year_id', $Y->id);

        $fn_gs = fn($gs) =>
                $gs->with(['group' => $fn_g ])
                ->whereHas('group', $fn_g );

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'father_last_name',
            'mother_last_name',
            'institutional_email',
            'headquarters_id',
            'study_time_id',
            'study_year_id'
            )
                ->whereNot(fn($q) =>
                    $q->whereHas('groupYear', $fn_gs)
                        ->with(['groupYear' => $fn_gs])
                )
                ->with('headquarters','studyTime','studyYear')
                ->orderBy('father_last_name')
                ->orderBy('mother_last_name')
                ->get();

        // return $students;

        return view('logro.student.noenrolled')->with('students', $students);
    }

    public function create()
    {
        $documentType = DocumentType::all();
        $headquarters = Headquarters::all();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        $cities = City::all();
        return view("logro.student.create")->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'cities' => $cities,
            'documentType' => $documentType
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'secondName' => ['nullable','string'],
            'fatherLastName' => ['required', 'string'],
            'motherLastName' => ['nullable','string'],
            'document_type' => ['required', Rule::exists('document_types','code')],
            'document' => ['required', Rule::unique('students','document')],
            'institutional_email' => ['required', 'email', Rule::unique('users','email')],
            'headquarters' => ['required', Rule::exists('headquarters','id')],
            'studyTime' => ['required', Rule::exists('study_times','id')],
            'studyYear' => ['required', Rule::exists('study_years','id')],
            'birth_city' => ['nullable',Rule::exists('cities','id')],
            'birthdate' => ['nullable','date'],
        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        $user = UserController::_create($user_name, $request->institutional_email, 7);

        Student::create([
            'id' => $user->id,
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'father_last_name' => $request->fatherLastName,
            'mother_last_name' => $request->motherLastName,
            'institutional_email' => $request->institutional_email,
            'document_type_code' => $request->document_type,
            'document' => $request->document,
            'birth_city_id' => $request->birth_city,
            'birthdate' => $request->birthdate,
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->studyTime,
            'study_year_id' => $request->studyYear,
            'status' => 'new'
        ]);

        return redirect()->route('students.no_enrolled')->with(
            ['notify' => 'success', 'title' => __('Student created!')],
        );
    }

    /* public function preregistration_edit(Student $student)
    {
        $headquarters = Headquarters::all();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        return view('logro.student.preregistration-edit')->with([
            'student' => $student,
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear
        ]);
    } */

    /* public function preregistration_update(Student $student, Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'secondName' => ['nullable','string'],
            'fatherLastName' => ['required', 'string'],
            'motherLastName' => ['nullable','string'],
            'institutional_email' => ['required', 'email', Rule::unique('users','email')->ignore($student->id)],
            'headquarters' => ['required', Rule::exists('headquarters','id')],
            'studyTime' => ['required', Rule::exists('study_times','id')],
            'studyYear' => ['required', Rule::exists('study_years','id')]
        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        UserController::_update($student->id, $user_name, $request->institutional_email);

        $student->update([
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'father_last_name' => $request->fatherLastName,
            'mother_last_name' => $request->motherLastName,
            'institutional_email' => $request->institutional_email,
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->studyTime,
            'study_year_id' => $request->studyYear
        ]);

        return redirect()->route('students.no_enrolled')->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
    } */



    /*
     * ENROLLED SECTION
     */
    public function enrolled()
    {
        $Y = SchoolYearController::current_year();

        $fn_g = fn($g) => $g->where('school_year_id', $Y->id);

        $fn_gs = fn($gs) =>
                $gs->with(['group' => $fn_g ])
                ->whereHas('group', $fn_g );

        $students = Student::select(
            'id',
            'first_name',
            'second_name',
            'father_last_name',
            'mother_last_name',
            'institutional_email'
            )
                ->whereHas('groupYear', $fn_gs)
                ->with(['groupYear' => $fn_gs])
                ->orderBy('father_last_name')
                ->orderBy('mother_last_name')
                ->get();


        return view('logro.student.enrolled')->with('students', $students);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $documentType = DocumentType::all();
        $cities = City::all();
        $genders = Gender::all();
        $rhs = Rh::all();
        $healthManager = HealthManager::all();
        $sisbenes = Sisben::all();
        $ethnicGroups = EthnicGroup::all();
        $originSchools = OriginSchool::all();
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
        ])->get();

        return view('logro.student.profile')->with([
            'student' => $student,
            'documentType' => $documentType,
            'cities' => $cities,
            'genders' => $genders,
            'rhs' => $rhs,
            'healthManager' => $healthManager,
            'sisbenes' => $sisbenes,
            'ethnicGroups' => $ethnicGroups,
            'originSchools' => $originSchools,
            'dwellingTypes' => $dwellingTypes,
            'disabilities' => $disabilities,
            'icbfProtections' => $icbfProtections,
            'linkageProcesses' => $linkageProcesses,
            'religions' => $religions,
            'economicDependences' => $economicDependences,
            'kinships' => $kinships,
            'studentFileTypes' => $studentFileTypes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:191'],
            'secondName' => ['nullable','string', 'max:191'],
            'fatherLastName' => ['required', 'string', 'max:191'],
            'motherLastName' => ['nullable','string', 'max:191'],
            'telephone' => ['nullable','string', 'max:20'],
            'document_type' => ['required', Rule::exists('document_types','code')],
            'document' => ['required', 'max:20', Rule::unique('students','document')->ignore($student->id)],
            'expedition_city' => ['nullable',Rule::exists('cities','id')],
            'number_siblings' => ['nullable','numeric', 'max:200'],
            'birth_city' => ['nullable',Rule::exists('cities','id')],
            'birthdate' => ['nullable','date'],
            'gender' => ['nullable',Rule::exists('genders','id')],
            'rh' => ['nullable',Rule::exists('rhs','id')],
            'zone' => ['nullable','string', 'max:6'],
            'residence_city' => ['nullable',Rule::exists('cities','id')],
            'address' => ['nullable','string', 'max:100'],
            'social_stratum' => ['nullable', 'max:10'],
            'dwelling_type' => ['nullable',Rule::exists('dwelling_types','id')],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'electrical_energy' => ['nullable', 'boolean'],
            'natural_gas' => ['nullable', 'boolean'],
            'sewage_system' => ['nullable', 'boolean'],
            'aqueduct' => ['nullable', 'boolean'],
            'internet' => ['nullable', 'boolean'],
            'lives_with_father' => ['nullable', 'boolean'],
            'lives_with_mother' => ['nullable', 'boolean'],
            'lives_with_siblings' => ['nullable', 'boolean'],
            'lives_with_other_relatives' => ['nullable', 'boolean'],
            'health_manager' => ['nullable',Rule::exists('health_managers','id')],
            'school_insurance' => ['nullable','string', 'max:100'],
            'sisben' => ['nullable',Rule::exists('sisben','id')],
            'disability' => ['nullable',Rule::exists('disabilities','id')],
            'ethnic_group' => ['nullable',Rule::exists('ethnic_groups','id')],
            'conflict_victim' => ['nullable','boolean'],
            'origin_school_id' => ['nullable',Rule::exists('origin_schools','id')],
            'icbf_protection' => ['nullable',Rule::exists('icbf_protection_measures','id')],
            'foundation_beneficiary' => ['nullable','boolean'],
            'linked_process' => ['nullable',Rule::exists('linkage_processes','id')],
            'religion' => ['nullable',Rule::exists('religions','id')],
            'economic_dependence' => ['nullable',Rule::exists('economic_dependences','id')],
            'plays_sports' => ['nullable','boolean'],
            'freetime_activity' => ['nullable','string', 'max:191'],
            'allergies' => ['nullable','string', 'max:191'],
            'medicines' => ['nullable','string', 'max:191'],
            'favorite_subjects' => ['nullable','string', 'max:191'],
            'most_difficult_subjects' => ['nullable','string', 'max:191'],
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

        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        UserController::_update($student->id, $user_name);

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
            'disability_id' => $request->disability,
            'school_insurance' => $request->school_insurance,

            /* informacion complementaria */
            'ethnic_group_id' => $request->ethnic_group,
            'conflict_victim' => $request->conflict_victim,
            'origin_school_id' => $request->origin_school_id,
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

        ]);


        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
    }



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
        $ethnicGroups = EthnicGroup::all();
        $originSchools = OriginSchool::all();

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
            // 'ethnicGroups' => $ethnicGroups,
            // 'originSchools' => $originSchools
        ]);
    }

    public function export_instructive()
    {
        return Excel::download(new StudentsInstructuveExport, __('instructive').'.xlsx');
    }

    public function import()
    {
        return view('logro.student.import');
    }

    public function import_store(Request $request)
    {

        $request->validate([
            'file' => ['required','file','max:5000','mimes:xls,xlsx']
        ]);

        Excel::import(new StudentsImport,$request->file('file'));

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Loaded Excel!')],
        );
    }
}
