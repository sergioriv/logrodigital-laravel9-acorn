<?php

namespace App\Http\Controllers;

use App\Exports\TeachersExport;
use App\Exports\TeachersInstructuveExport;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Imports\TeachersImport;
use App\Models\City;
use App\Models\Data\MaritalStatus;
use App\Models\Data\RoleUser;
use App\Models\Data\TypeAdministrativeAct;
use App\Models\Data\TypeAppointment;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Rules\TypeAdminActRule;
use App\Rules\TypeAppointmentRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

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
            'institutional_email' => ['required', 'email', 'max:191', Rule::unique('users','email')],
            'date_entry' => ['required', 'date'],
            'type_appointment' => ['required', new TypeAppointmentRule],
            'type_admin_act' => ['required', new TypeAdminActRule],
            'appointment_number' => ['nullable', 'max:20'],
            'date_appointment' => ['nullable', 'date'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['nullable', 'date'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['nullable', 'date'],
        ]);

        $user_name = $request->names . ' ' . $request->lastNames;
        $user = UserController::_create($user_name, $request->email, RoleUser::TEACHER);

        if (!$user) {
            Notify::fail(__('Invalid email (:email)', ['email' => $request->email]));
            return redirect()->back();
        }

        Teacher::create([
            'id' => $user->id,
            'uuid' => Str::uuid()->toString(),
            'names' => $request->names,
            'last_names' => $request->lastNames,
            'institutional_email' => $request->institutional_email,
            'date_entry' => $request->date_entry,

            'type_appointment' => $request->type_appointment,
            'type_admin_act' => $request->type_admin_act,
            'appointment_number' => $request->appointment_number,
            'date_appointment' => $request->date_appointment,
            'possession_certificate' => $request->possession_certificate,
            'date_possession_certificate' => $request->date_possession_certificate,
            'transfer_resolution' => $request->transfer_resolution,
            'date_transfer_resolution' => $request->date_transfer_resolutionm,

            'active' => TRUE
        ]);

        Notify::success(__('Teacher created!'));
        self::tab();
        return redirect()->route('myinstitution');
    }

    public function show(Teacher $teacher)
    {
        $schoolYear = SchoolYear::whereHas('teacherSubjectGroups',
            fn ($subject) => $subject->where('teacher_id', $teacher->id))
        /* $schoolYear = SchoolYear::whereHas('teacherSubjectGroups', function ($subject) use ($teacher) {
            $subject->where('teacher_id', $teacher->id);
        }) */
        ->orderByDesc('id')->get();

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
