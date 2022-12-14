<?php

namespace App\Http\Controllers;

use App\Exports\TeachersExport;
use App\Exports\TeachersInstructuveExport;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Imports\TeachersImport;
use App\Models\Attendance;
use App\Models\City;
use App\Models\Data\MaritalStatus;
use App\Models\Data\RoleUser;
use App\Models\Data\TypeAdministrativeAct;
use App\Models\Data\TypeAppointment;
use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\AcademicWorkload;
use App\Models\GroupStudent;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use App\Rules\MaritalStatusRule;
use App\Rules\TypeAdminActRule;
use App\Rules\TypeAppointmentRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    function __construct()
    {
        $this->middleware('can:teachers.create')->only('create', 'store');
        $this->middleware('can:teachers.index')->only('show');
        $this->middleware('can:teachers.import')->only('export', 'export_instructive', 'import', 'import_store');
        $this->middleware(OnlyTeachersMiddleware::class)->only('profile', 'profile_update', 'mysubjects', 'mysubjects_show', 'subjects');
    }

    public function create()
    {
        return view('logro.teacher.create', [
            'typesAppointment' => TypeAppointment::data(),
            'typesAdministrativeAct' => TypeAdministrativeAct::data()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'names' => ['required', 'string', 'max:191'],
            'lastNames' => ['required', 'string', 'max:191'],
            'institutional_email' => ['required', 'email', Rule::unique('users', 'email')],
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

        DB::beginTransaction();

        $techaerName = $request->names . ' ' . $request->lastNames;
        $teacherCreate = UserController::__create($techaerName, $request->institutional_email, RoleUser::TEACHER);

        if (!$teacherCreate->getUser()) {

            DB::rollBack();
            Notify::fail( __('Something went wrong.') );
            return redirect()->back();
        }

        try {

            Teacher::create([
                'id' => $teacherCreate->getUser()->id,
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

        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        if (!$teacherCreate->sendVerification()) {

            DB::rollBack();
            Notify::fail( __('Invalid email (:email)', ['email' => $request->institutional_email]) );
            return redirect()->back();
        }

        DB::commit();


        Notify::success(__('Teacher created!'));
        self::tab();
        return redirect()->route('myinstitution');
    }

    public function show(Teacher $teacher)
    {
        $schoolYear = SchoolYear::whereHas(
            'teacherSubjectGroups',
            fn ($subject) => $subject->where('teacher_id', $teacher->id)
        )
            ->orderByDesc('id')->get();

        return view('logro.teacher.show')->with([
            'teacher' => $teacher,
            'schoolYear' => $schoolYear
        ]);
    }

    public function profile(Teacher $teacher)
    {
        if (RoleUser::TEACHER_ROL === UserController::role_auth()) {
            return view('logro.teacher.profile.edit', [
                'teacher' => $teacher,
                'cities' => City::all(),
                'maritalStatus' => MaritalStatus::data(),
            ]);
        } else {
            return redirect()->back()->withErrors(__('Unauthorized'));
        }
    }

    public function profile_update(Teacher $teacher, Request $request)
    {
        if (RoleUser::TEACHER_ROL === UserController::role_auth()) {

            $request->validate([
                'names' => ['required', 'string', 'max:191'],
                'lastNames' => ['required', 'string', 'max:191'],
                'document' => ['nullable', 'max:20', Rule::unique('teachers', 'document')],
                'expedition_city' => ['nullable', Rule::exists('cities', 'id')],
                'birth_city' => ['nullable', Rule::exists('cities', 'id')],
                'birthdate' => ['nullable', 'date', 'before:today'],
                'residence_city' => ['nullable', Rule::exists('cities', 'id')],
                'address' => ['nullable', 'max:100'],
                'telephone' => ['nullable', 'max:30'],
                'cellphone' => ['nullable', 'max:30'],
                'institutional_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->id)],
                'marital_status' => ['nullable', new MaritalStatusRule],

                'appointment_number' => ['nullable', 'max:20'],
                'date_appointment' => ['nullable', 'date', 'before:today'],
                'possession_certificate' => ['nullable', 'max:20'],
                'date_possession_certificate' => ['nullable', 'date', 'before:today'],
                'transfer_resolution' => ['nullable', 'max:20'],
                'date_transfer_resolution' => ['nullable', 'date', 'before:today'],

                'hierarchy_grade' => ['nullable', 'max:20'],
                'resolution_hierarchy' => ['nullable', 'max:20'],
                'date_resolution_hierarchy' => ['nullable', 'date', 'before:today'],

                'last_diploma' => ['nullable', 'max:191'],
                'institution_last_diploma' => ['nullable', 'max:191'],
                'date_last_diploma' => ['nullable', 'date', 'before:today']
            ]);

            $techaerName = $request->names . ' ' . $request->lastNames;
            $user = UserController::_update($teacher->id, $techaerName, $request->institutional_email);

            if (!$user) {
                Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
                return redirect()->back();
            }

            $teacher->update([
                'names' => $request->names,
                'last_names' => $request->lastNames,
                'institutional_email' => $request->institutional_email,

                'document' => $request->document,
                'expedition_city' => $request->expedition_city,
                'birth_city' => $request->birth_city,
                'birthdate' => $request->birthdate,
                'residence_city' => $request->residence_city,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'cellphone' => $request->cellphone,
                'marital_status' => $request->marital_status,

                'appointment_number' => $request->appointment_number,
                'date_appointment' => $request->date_appointment,
                'possession_certificate' => $request->possession_certificate,
                'date_possession_certificate' => $request->date_possession_certificate,
                'transfer_resolution' => $request->transfer_resolution,
                'date_transfer_resolution' => $request->date_transfer_resolution,

                'hierarchy_grade' => $request->hierarchy_grade,
                'resolution_hierarchy' => $request->resolution_hierarchy,
                'date_resolution_hierarchy' => $request->date_resolution_hierarchy,

                'last_diploma' => $request->last_diploma,
                'institution_last_diploma' => $request->institution_last_diploma,
                'date_last_diploma' => $request->date_last_diploma
            ]);

            Notify::success(__('Updated profile!'));
            return redirect()->route('user.profile.edit');
        } else {
            return redirect()->back()->withErrors(__('Unauthorized'));
        }
    }

    public function mysubjects()
    {
        $subjects = $this->subjects()
            ->with(['group' =>
                fn($g) => $g->withCount('groupStudents as student_quantity')
                            ->with('headquarters', 'studyTime', 'studyYear', 'teacher')
            ]);


        return view('logro.teacher.subjects.index', ['subjects' => $subjects->get()]);
    }

    public function mysubjects_show(TeacherSubjectGroup $subject)
    {

        /*
         * Para que el Rol TEACHER solo pueda acceder a sus asignaturas de el año actual
         *  */
        if ($subject->teacher_id !== Auth::id()) {
            return redirect()->route('teacher.my.subjects')->withErrors(__('Unauthorized'));
        }

        $Y = SchoolYearController::current_year();

        $studentsGroup = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', $subject->group_id))
            ->withCount([
                'attendanceStudent' =>
                    fn($attS) => $attS->whereIn('attend', ['N', 'JUSTIFIED'])
                        ->whereHas('attendance',
                        fn($att) => $att->where('teacher_subject_group_id', $subject->id)
                    )
                ])
            ->get();


        $periods = Period::where('study_time_id', $subject->group->study_time_id)
                    ->withCount(['permits as permit' => fn ($p) => $p->teacher_subject_group_id = $subject->id])
                    ->orderBy('ordering')->get();


        $weeklyLoad = AcademicWorkload::where('school_year_id', $Y->id)
                        ->where('study_year_id', $subject->group->study_year_id)
                        ->where('subject_id', $subject->subject->id)
                        ->first();

        $attendancesWeek = Attendance::where('teacher_subject_group_id', $subject->id)
                        ->whereBetween('created_at', [
                            Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'),
                            Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')
                        ])->count();



        $attendances = Attendance::where('teacher_subject_group_id', $subject->id)
            ->withCount('absences')
            ->get();

        return view('logro.teacher.subjects.show', [
            'Y' => $Y,
            'subject' => $subject,
            'studentsGroup' => $studentsGroup,
            'periods' => $periods,
            'attendanceAvailable' => $weeklyLoad->hours_week - $attendancesWeek,
            'attendances' => $attendances
        ]);
    }


    /*
     *
     * Extrae las materias del User Teacher
     *
     *  */
    public static function subjects()
    {
        $Y = SchoolYearController::available_year();
        $teacher_id = Auth::id();

        $subjects = TeacherSubjectGroup::where('school_year_id', $Y->id)
            ->where('teacher_id', $teacher_id);

        return $subjects;
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
