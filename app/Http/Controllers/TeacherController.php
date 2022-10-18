<?php

namespace App\Http\Controllers;

use App\Exports\TeachersExport;
use App\Exports\TeachersInstructuveExport;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Imports\TeachersImport;
use App\Models\City;
use App\Models\Data\MaritalStatus;
use App\Models\Data\TypeAdministrativeAct;
use App\Models\Data\TypeAppointment;
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
        return view('logro.teacher.create', [
            'cities' => City::all(),
            'maritalStatus' => MaritalStatus::data(),
            'typesAppointment' => TypeAppointment::data(),
            'typesAdministrativeAct' => TypeAdministrativeAct::data()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'names' => ['required', 'string', 'max:191'],
            'lastNames' => ['required', 'string', 'max:191'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users')]
        ]);

        $user_name = $request->firstName . ' ' . $request->firstLastName;
        $user = UserController::_create($user_name, $request->email, 6);

        if (!$user) {
            Notify::fail(__('Invalid email (:email)', ['email' => $request->email]));
            return redirect()->back();
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

        Notify::success(__('Teacher created!'));
        self::tab();
        return redirect()->route('myinstitution');
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

        Notify::success(__('Loaded Excel!'));
        self::tab();
        return redirect()->route('myinstitution');
    }

    private function tab()
    {
        session()->flash('tab', 'teachers');
    }
}
