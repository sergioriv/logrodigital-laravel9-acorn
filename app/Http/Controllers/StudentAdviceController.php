<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Student;
use App\Models\StudentAdvice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StudentAdviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Student $student)
    {
        return view('logro.student.advices.create', ['advice' => new StudentAdvice, 'student' => $student]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'attendance' => ["required"],
            'type_advice' => ["required_if:attendance,done"],
            'evolution' => ['required_if:attendance,done', 'min:10', 'max:500'],
            'recommendations_teachers' => ['nullable', 'min:10', 'max:500'],
            'date_limite' => ['required_with:recommendations_teachers'],
            'recommendations_family' => ['nullable', 'min:10', 'max:500'],
            'entity_remit' => ['nullable'],
            'observations_for_entity' => [Rule::requiredIf(fn () => $request->entity_remit != 'Ninguna'), 'min:10', 'max:500']
        ]);

        StudentAdvice::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
            'attendance' => Str::upper($request->attendance),
            'type_advice' => Str::upper($request->type_advice),
            'evolution' => $request->evolution,
            'recommendations_teachers' => $request->recommendations_teachers,
            'date_limit_teacher' => $request->date_limite,
            'recommendations_family' => $request->recommendations_family,
            'entity_remit' => Str::upper($request->entity_remit),
            'observations_for_entity' => $request->observations_for_entity
        ]);

        Notify::success(__("Advice save!"));
        return redirect()->route('students.show', $student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAdvice $studentAdvice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAdvice $studentAdvice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAdvice $studentAdvice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAdvice $studentAdvice)
    {
        //
    }
}
