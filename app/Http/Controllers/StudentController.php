<?php

namespace App\Http\Controllers;

use App\Exports\StudentsInstructuveExport;
use App\Http\Controllers\support\UserController;
use App\Imports\StudentsImport;
use App\Models\City;
use App\Models\DocumentType;
use App\Models\EthnicGroup;
use App\Models\Gender;
use App\Models\Headquarters;
use App\Models\HealthManager;
use App\Models\Kinship;
use App\Models\OriginSchool;
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
    public function preregistration()
    {
        $students = Student::whereNull('enrolled_status')->orderByDesc('created_at')->get();
        return view('logro.student.preregistration')->with('students', $students);
    }

    public function preregistration_create()
    {
        $headquarters = Headquarters::all();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        return view("logro.student.create")->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear
        ]);
    }

    public function preregistration_store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'secondName' => ['nullable','string'],
            'fatherLastName' => ['required', 'string'],
            'motherLastName' => ['nullable','string'],
            'institutional_email' => ['required', 'email', Rule::unique('users','email')],
            'headquarters' => ['required', Rule::exists('headquarters','id')],
            'studyTime' => ['required', Rule::exists('study_times','id')],
            'studyYear' => ['required', Rule::exists('study_years','id')]
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
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->studyTime,
            'study_year_id' => $request->studyYear,
            'status' => 'new'
        ]);

        return redirect()->route('students.preregistration')->with(
            ['notify' => 'success', 'title' => __('Student created!')],
        );
    }

    public function preregistration_edit(Student $student)
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
    }

    public function preregistration_update(Student $student, Request $request)
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

        return redirect()->route('students.preregistration')->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
    }



    /*
     * REGISTRATION SECTION
     */

    public function registration()
    {
        $students = Student::where('enrolled_status','registrated')
                    ->orderBy('father_last_name')
                    ->orderBy('mother_last_name')
                    ->get();

        return view('logro.student.registration')->with('students', $students);
    }

    public function preenrolled()
    {
        return $students = Student::where('enrolled_status','pre-enrolled')
                    ->orderBy('father_last_name')
                    ->orderBy('mother_last_name')
                    ->get();

        return view('logro.student.preenrolled')->with('students', $students);
    }

    public function enrolled()
    {
        return $students = Student::where('enrolled_status','enrolled')
                    ->orderBy('father_last_name')
                    ->orderBy('mother_last_name')
                    ->get();

        return view('logro.student.index')->with('students', $students);
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
        $kinships = Kinship::all();
        $studentFileTypes = StudentFileType::get();

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
            'firstName' => ['required', 'string'],
            'secondName' => ['nullable','string'],
            'fatherLastName' => ['required', 'string'],
            'motherLastName' => ['nullable','string'],
            'telephone' => ['nullable','numeric'],
            'document_type' => ['required', Rule::exists('document_types','code')],
            'document' => ['required', Rule::unique('students','document')->ignore($student->id)],
            'expedition_city' => ['nullable',Rule::exists('cities','id')],
            'number_siblings' => ['nullable','numeric'],
            'birth_city' => ['nullable',Rule::exists('cities','id')],
            'birthdate' => ['nullable','date'],
            'gender' => ['nullable',Rule::exists('genders','id')],
            'rh' => ['nullable',Rule::exists('rhs','id')],
            'zone' => ['nullable','string'],
            'residence_city' => ['nullable',Rule::exists('cities','id')],
            'address' => ['nullable','string'],
            'social_stratum' => ['nullable'],
            'health_manager' => ['nullable',Rule::exists('health_managers','id')],
            'school_insurance' => ['nullable','string'],
            'sisben' => ['nullable',Rule::exists('sisben','id')],
            'disability' => ['nullable','string'],
            'ethnic_group' => ['nullable',Rule::exists('ethnic_groups','id')],
            'conflict_victim' => ['nullable','boolean'],
            'lunch' => ['nullable','boolean'],
            'refreshment' => ['nullable','boolean'],
            'transport' => ['nullable','boolean'],
            'origin_school_id' => ['nullable',Rule::exists('origin_schools','id')]

        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        UserController::_update($student->id, $user_name);

        $enrolled_status = $student->enrolled_status === NULL ? 'registrated' : $student->enrolled_status ;

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

            /* seguridad social */
            'health_manager_id' => $request->health_manager,
            'sisben_id' => $request->sisben,
            'disability' => $request->disability,
            'school_insurance' => $request->school_insurance,

            /* informacion complementaria */
            'ethnic_group_id' => $request->ethnic_group,
            'conflict_victim' => $request->conflict_victim,
            'lunch' => $request->lunch,
            'refreshment' => $request->refreshment,
            'transport' => $request->transport,
            'origin_school_id' => $request->origin_school_id,

            /* estados */
            'enrolled_status' => $enrolled_status

        ]);


        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
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
