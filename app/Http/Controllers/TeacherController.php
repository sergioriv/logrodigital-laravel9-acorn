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
    function __construct()
    {
        $this->middleware('can:teachers.index');
        $this->middleware('can:teachers.create');
        $this->middleware('can:teachers.edit');
        $this->middleware('can:teachers.import')->only('export', 'import', 'import_store');
    }

    public function index()
    {
        return view('logro.teacher.index');
    }

    public function data()
    {
        return ['data' => Teacher::orderBy('first_name')->orderBy('father_last_name')->get()];
    }

    public function create()
    {
        return view('logro.teacher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'secondName' => ['nullable', 'string'],
            'fatherLastName' => ['required', 'string'],
            'motherLastName' => ['nullable', 'string'],
            'phone' => ['nullable', 'numeric'],
            'email' => ['required', 'email', Rule::unique('users')]
        ]);

        $user_name = $request->firstName . ' ' . $request->fatherLastName;
        $user = UserController::_create($user_name, $request->email, 6);

        if (!$user) {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Email :email invalid!', ['email' => $request->email])],
            );
        }

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
            ['notify' => 'success', 'title' => __('Teacher created!')],
        );
    }

    public function show(Teacher $teacher)
    {
        $schoolYear = SchoolYear::whereHas('teacherSubjectGroups', function ($subject) use ($teacher) {
            $subject->where('teacher_id', $teacher->id);
        })->orderByDesc('id')->get();

        return view('logro.teacher.show')->with([
            'teacher' => $teacher,
            'schoolYear' => $schoolYear
        ]);
    }

    public function edit(Teacher $teacher)
    {
        //
    }

    public function update(Request $request, Teacher $teacher)
    {
        //
    }




    public function export()
    {
        return Excel::download(new TeachersExport, __('teachers') . '.xlsx');
    }

    public function import()
    {
        return view('logro.teacher.import');
    }

    public function import_store(Request $request)
    {

        $request->validate([
            'file' => ['required', 'file', 'max:5000', 'mimes:xls,xlsx']
        ]);

        Excel::import(new TeachersImport, $request->file('file'));

        return redirect()->route('teacher.index')->with(
            ['notify' => 'success', 'title' => __('Loaded Excel!')],
        );
    }
}
