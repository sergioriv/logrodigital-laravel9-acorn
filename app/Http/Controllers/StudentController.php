<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\UserController;
use App\Models\Headquarters;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            // 'secondName' => ['string'],
            'fatherLastName' => ['required', 'string'],
            // 'motherLastName' => ['string'],
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
            'study_year_id' => $request->studyYear
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
            'secondName' => ['string'],
            'fatherLastName' => ['required', 'string'],
            'motherLastName' => ['string'],
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
        return $students = Student::where('enrolled_status','registrated')->get();
        return view('logro.student.registration');
    }

    public function preenrolled()
    {
        return $students = Student::where('enrolled_status','pre-enrolled')->get();
        return view('logro.student.preenrolled');
    }

    public function enrolled()
    {
        return $students = Student::where('enrolled_status','enrolled')->get();
        return view('logro.student.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
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
        return "update";
    }

}
