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
            'date' => ['required', 'date'],
            'time' => ['required']
        ]);

        $timeAdvice = Str::substr($request->time, 0,5);

        StudentAdvice::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'attendance' => 'SCHEDULED',
            'date' => $request->date,
            'time' => $timeAdvice
        ]);

        self::tab();
        Notify::success(__("Advice created!"));
        return redirect()->route('students.show', $student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student, StudentAdvice $advice)
    {
        if ('done' === $advice->attendance)
        {
            return view('logro.student.advices.show', ['student' => $student, 'advice' => $advice]);
        }
        if ('scheduled' === $advice->attendance)
        {
            return 'scheduled';
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student, StudentAdvice $advice)
    {
        return view('logro.student.advices.edit', ['student' => $student, 'advice' => $advice]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAdvice  $studentAdvice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student, StudentAdvice $advice)
    {
        $request->validate([
            'attendance' => ["required"],
            'type_advice' => ["required_if:attendance,done"],
            'evolution' => ["required_if:attendance,done", 'min:10', 'max:500'],
            'recommendations_teachers' => ['nullable', 'min:10', 'max:500'],
            'date_limite' => ['required_with:recommendations_teachers'],
            'recommendations_family' => ['nullable', 'min:10', 'max:500'],
            'entity_remit' => ['nullable'],
            'observations_for_entity' => ['min:10', 'max:500',
                'required_unless:entity_remit,null,Ninguna']
        ]);

        $typeAdvice = null;
        if ($request->has('type_advice'))
        {
            $typeAdvice = Str::upper($request->type_advice);
        }

        $entityRemit = null;
        if ($request->has('entity_remit'))
        {
            $entityRemit = Str::upper($request->entity_remit);
        }

        $advice->update([
            'attendance' => Str::upper($request->attendance),
            'type_advice' => $typeAdvice,
            'evolution' => $request->evolution,
            'recommendations_teachers' => $request->recommendations_teachers,
            'date_limit_teacher' => $request->date_limite,
            'recommendations_family' => $request->recommendations_family,
            'entity_remit' => $entityRemit,
            'observations_for_entity' => $request->observations_for_entity
        ]);

        self::tab();
        Notify::success(__("Advice save!"));
        return redirect()->route('students.show', $student);
    }


    private function tab()
    {
        session()->flash('tab', 'advices');
    }
}
