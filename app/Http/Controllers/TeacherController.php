<?php

namespace App\Http\Controllers;

use App\Exports\TeachersExport;
use App\Exports\TeachersInstructuveExport;
use App\Http\Controllers\support\UserController;
use App\Imports\TeachersImport;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    function __construct()
    {
        $this->middleware('can:teachers.create');
        $this->middleware('can:teachers.edit');
        $this->middleware('can:teachers.import')->only('export', 'import', 'import_store');
    }

    public function create()
    {
        return view('logro.teacher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:191'],
            'secondName' => ['nullable', 'string', 'max:191'],
            'firstLastName' => ['required', 'string', 'max:191'],
            'secondLastName' => ['nullable', 'string', 'max:191'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users')]
        ]);

        $user_name = $request->firstName . ' ' . $request->firstLastName;
        $user = UserController::_create($user_name, $request->email, 6);

        if (!$user) {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Invalid email (:email)', ['email' => $request->email])],
            );
        }

        Teacher::create([
            'id' => $user->id,
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'first_last_name' => $request->firstLastName,
            'second_last_name' => $request->secondLastName,
            'telephone' => $request->phone,
            'institutional_email' => $request->email
        ]);

        self::tab();
        return redirect()->route('myinstitution')->with(
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

    public function export_instructive()
    {
        return Excel::download(new TeachersInstructuveExport, __('instruction for teachers') . '.xlsx');
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

        self::tab();
        return redirect()->route('myinstitution')->with(
            ['notify' => 'success', 'title' => __('Loaded Excel!')],
        );
    }

    private function tab()
    {
        session()->flash('tab', 'teachers');
    }
}
