<?php

namespace App\Http\Controllers;

use App\Exports\TeachersExport;
use App\Http\Controllers\support\UserController;
use App\Imports\TeachersImport;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logro.teacher.index');
    }

    public function data()
    {
        return ['data' => Teacher::orderBy('first_name')->orderBy('father_last_name')->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'secondName' => ['string'],
            'fatherLastName' => ['required','string'],
            'motherLastName' => ['string'],
            'phone' => ['numeric'],
            'email' => ['required', 'email', Rule::unique('users')]
        ]);

        $teacher_name = $request->firstName . ' ' . $request->fatherLastName;
        $user = UserController::_create($teacher_name, $request->email, 6);

        Teacher::create([
            'id' => $user->id,
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'father_last_name' => $request->fatherLastName,
            'mother_last_name' => $request->motherLastName,
            'telephone' => $request->phone,
            'institutional_email' => $request->email
        ]);

        return redirect()->route('teacher.index')->with(
            ['notify' => 'success', 'title' => __('Teacher updated!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        $schoolYear = SchoolYear::whereHas('teacherSubjectGroups', function ($subject) use ($teacher) {
            $subject->where('teacher_id',$teacher->id);
        })->orderByDesc('id')->get();

        return view('logro.teacher.show')->with([
            'teacher' => $teacher,
            'schoolYear' => $schoolYear
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }



    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new TeachersExport, __('teachers').'.xlsx');
    }

    public function import()
    {
        return view('logro.teacher.import');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import_store(Request $request)
    {

        $request->validate([
            'file' => ['required','file','max:5000','mimes:xls,xlsx']
        ]);

        Excel::import(new TeachersImport,$request->file('file'));

        return redirect()->route('teacher.index')->with(
            ['notify' => 'success', 'title' => __('Loaded Excel!')],
        );
    }
}
