<?php

namespace App\Http\Controllers;

use App\Exports\GroupStudentListGuide;
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
use App\Models\Descriptor;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use App\Models\TypePermitsTeacher;
use App\Rules\MaritalStatusRule;
use App\Rules\TypeAdminActRule;
use App\Rules\TypeAppointmentRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\FuncCall;

class TeacherController extends Controller
{
    function __construct()
    {
        $this->middleware('can:teachers.create')->only('create', 'store');
        $this->middleware('can:teachers.index')->only('show');
        $this->middleware('can:teachers.import')->only('export', 'export_instructive', 'import', 'import_store');
        $this->middleware(OnlyTeachersMiddleware::class)->only('profile', 'profile_update', 'mysubjects', 'mysubjects_show', 'subjects', 'myDirectorGroup', 'attendanceLimitWeek');
    }

    public function index()
    {
        $this->tab();
        return redirect()->route('myinstitution');
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
            'date_entry' => ['required', 'date', 'date_format:Y-m-d'],
            'type_appointment' => ['required', new TypeAppointmentRule],
            'type_admin_act' => ['required', new TypeAdminActRule],
            'appointment_number' => ['nullable', 'max:20'],
            'date_appointment' => ['required_with:appointment_number'],
            'file_appointment' => ['required_with:appointment_number', 'file', 'mimes:pdf', 'max:2048'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['required_with:possession_certificate'],
            'file_possession_certificate' => ['required_with:possession_certificate', 'file', 'mimes:pdf', 'max:2048'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['required_with:transfer_resolution'],
            'file_transfer_resolution' => ['required_with:transfer_resolution', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        DB::beginTransaction();

        $techaerName = $request->names . ' ' . $request->lastNames;
        $teacherCreate = UserController::__create($techaerName, $request->institutional_email, RoleUser::TEACHER);

        if (!$teacherCreate->getUser()) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        $teacherUuid = Str::uuid()->toString();

        try {



            $teacher = Teacher::create([
                'id' => $teacherCreate->getUser()->id,
                'uuid' => $teacherUuid,
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

            if ($request->hasFile('file_appointment')) {
                $teacher->update([
                    'file_appointment' => $this->uploadFile($request, $teacher, 'file_appointment')
                ]);
            }
            if ($request->hasFile('file_possession_certificate')) {
                $teacher->update([
                    'file_possession_certificate' => $this->uploadFile($request, $teacher, 'file_possession_certificate')
                ]);
            }
            if ($request->hasFile('file_transfer_resolution')) {
                $teacher->update([
                    'file_transfer_resolution' => $this->uploadFile($request, $teacher, 'file_transfer_resolution')
                ]);
            }
        } catch (\Throwable $th) {

            $this->deleteDirectory($teacherUuid);

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        if (!$teacherCreate->sendVerification()) {

            $this->deleteDirectory($teacherUuid);

            DB::rollBack();
            Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
            return redirect()->back();
        }

        DB::commit();


        return view('logro.created', [
            'role' => 'teacher',
            'title' => __('Teacher created!'),
            'email' => $request->institutional_email,
            'password' => $teacherCreate->getUser()->temporalPassword,
            'buttons' => [
                [
                    'title' => __('Go back'),
                    'class' => 'btn-outline-alternate',
                    'action' => route('teacher.index'),
                ], [
                    'title' => __('Create new'),
                    'class' => 'btn-primary ms-2',
                    'action' => url()->previous(),
                ]
            ]
        ]);
    }

    /*
     *
     *
     *
     *
     * por mejorar
     *
     *
     * */
    public function show(Teacher $teacher)
    {
        $schoolYear = SchoolYear::withWhereHas(
            'teacherSubjectGroups',
            fn ($subject) => $subject->where('teacher_id', $teacher->id)->with('subject', 'group')
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
                'cities' => City::with('department')->get(),
                'maritalStatus' => MaritalStatus::data(),
                'typePermit' => TypePermitsTeacher::all()
            ]);
        }

        return redirect()->back()->withErrors(__('Unauthorized!'));
    }

    public function profile_update(Teacher $teacher, Request $request)
    {
        if (auth()->id() !== $teacher->id) {
            return redirect()->back()->withErrors(__('Unauthorized!'));
        }

        $request->validate([
            'names' => ['required', 'string', 'max:191'],
            'lastNames' => ['required', 'string', 'max:191'],
            'document' => ['nullable', 'max:20', Rule::unique('teachers', 'document')->ignore($teacher->id)],
            'expedition_city' => ['nullable', Rule::exists('cities', 'id')],
            'birth_city' => ['nullable', Rule::exists('cities', 'id')],
            'birthdate' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today'],
            'residence_city' => ['nullable', Rule::exists('cities', 'id')],
            'address' => ['nullable', 'max:100'],
            'telephone' => ['nullable', 'max:30'],
            'cellphone' => ['nullable', 'max:30'],
            'institutional_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->id)],
            'marital_status' => ['nullable', new MaritalStatusRule],

            'appointment_number' => ['nullable', 'max:20'],
            'date_appointment' => ['required_with:appointment_number'],
            'file_appointment' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['required_with:possession_certificate'],
            'file_possession_certificate' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['required_with:transfer_resolution'],
            'file_transfer_resolution' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],

            'signature' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::beginTransaction();

        $techaerName = $request->names . ' ' . $request->lastNames;
        $user = UserController::_update($teacher->id, $techaerName, $request->institutional_email);

        if (!$user) {
            Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
            return redirect()->back();
        }

        try {

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
                'date_transfer_resolution' => $request->date_transfer_resolution
            ]);

            if ($request->hasFile('file_appointment')) {
                $teacher->update([
                    'file_appointment' => $this->uploadFile($request, $teacher, 'file_appointment')
                ]);
            }
            if ($request->hasFile('file_possession_certificate')) {
                $teacher->update([
                    'file_possession_certificate' => $this->uploadFile($request, $teacher, 'file_possession_certificate')
                ]);
            }
            if ($request->hasFile('file_transfer_resolution')) {
                $teacher->update([
                    'file_transfer_resolution' => $this->uploadFile($request, $teacher, 'file_transfer_resolution')
                ]);
            }
            if ($request->hasFile('signature')) {
                $teacher->update([
                    'signature' => $this->uploadFile($request, $teacher, 'signature')
                ]);
            }


        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        if ($request->institutional_email !== $teacher->institutional_email) {

            if (!$teacher->sendVerification()) {

                DB::rollBack();
                Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
                return redirect()->back();
            }
        }

        DB::commit();

        Notify::success(__('Updated profile!'));
        return redirect()->route('user.profile.edit');
    }

    public function mysubjects()
    {
        $subjects = $this->subjects()
            ->with([
                'group' =>
                fn ($g) => $g->withCount('groupStudents as student_quantity')
                    ->with('headquarters', 'studyTime', 'studyYear', 'teacher')
            ]);

        return view('logro.teacher.subjects.index', [
            'directGroup' => $this->myDirectorGroup()->get(),
            'subjects' => $subjects->get()
        ]);
    }

    public function mysubjects_show(TeacherSubjectGroup $subject)
    {
        /*
         * Para que el Rol TEACHER solo pueda acceder a sus asignaturas de el año actual
         *  */
        if ($subject->teacher_id !== Auth::id()) {
            return redirect()->route('teacher.my.subjects')->withErrors(__('Unauthorized!'));
        }

        $Y = SchoolYearController::current_year();
        $studyYear = $subject->group->studyYear;
        // $studyTime = $subject->group->studyTime;

        $studentsGroup = Student::singleData()
            ->whereHas(
                'groupYear', fn ($gr) => $gr->where('group_id', $subject->group_id)
            )->withCount([
                'attendanceStudent' =>
                fn ($attS) => $attS->whereIn('attend', ['N', 'J', 'L'])
                    ->whereHas(
                        'attendance', fn ($att) => $att->where('teacher_subject_group_id', $subject->id)
                    )
            ])->with([
                'studentDescriptors' => fn ($des) => $des->where('teacher_subject_group_id', $subject->id)->with('descriptor')
            ])->get();

        // if ( $studyYear->useGrades() ) {
        //     ->when($studyYear->useGrades(), function ($query) use ($subject) {
        //         $query->with([
        //             'grades' => fn($grades) => $grades->where('teacher_subject_group_id', $subject->id)->with('period:id,workload')
        //         ]);
        //     })
        //     $studentsGroup->map(function($student, $key) use ($studyTime) {
        //         return $student->setAttribute('finalGrade', GradeController::calculateGradeWithEvaluationComponents($student->grades, $studyTime));
        //     });
        // }


        $periods = Period::where('school_year_id', $Y->id)
            ->where('study_time_id', $subject->group->study_time_id)
            ->withCount(['permits as permit' => fn ($p) => $p->where('teacher_subject_group_id', $subject->id)])
            ->orderBy('ordering')->get();


        $attendances = Attendance::where('teacher_subject_group_id', $subject->id)
            ->withCount('absences')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();


        $descriptors = null;
        $descriptorsInclusive = null;
        if ( $studyYear->useDescriptors() ) {
            $descriptors = Descriptor::where('resource_subject_id', $subject->subject->resource_subject_id)
                ->where('resource_study_year_id', $studyYear->resource_study_year_id)
                ->where(function ($query) {
                    $query->whereNull('inclusive')->orWhere('inclusive', '0');
                })
                ->orderBy('content')
                ->orderBy('created_at')
                ->get();

            $descriptorsInclusive = Descriptor::where('resource_subject_id', $subject->subject->resource_subject_id)
                ->where('resource_study_year_id', $studyYear->resource_study_year_id)
                ->where('inclusive', 1)
                ->orderBy('content')
                ->orderBy('created_at')
                ->get();
        }


        return view('logro.teacher.subjects.show', [
            'Y' => $Y,
            'studyYear' => $studyYear,
            'subject' => $subject,
            'studentsGroup' => $studentsGroup,
            'periods' => $periods,
            'attendances' => $attendances,
            'descriptors' => $descriptors,
            'descriptorsInclusive' => $descriptorsInclusive
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

    public static function myDirectorGroup()
    {
        $Y = SchoolYearController::available_year();
        return Group::where('school_year_id', $Y->id)
            ->where('teacher_id', Auth::id())
            ->with('headquarters', 'studyTime', 'studyYear', 'teacher')
            ->withCount('groupStudents as student_quantity');
    }



    public function attendanceLimitWeek(TeacherSubjectGroup $subject, Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');

        return ['data' => $this->remainingAttendanceWeek($subject, $date)];
    }

    /*
     * @param $tsg TeacherSubjetGroup
     * @param $date format Y-m-d
     * @return int
     * */
    public function remainingAttendanceWeek($tsg, $date)
    {
        if ($tsg->teacher_id === auth()->id()) {

            $Y = SchoolYearController::current_year();

            $hoursWeek = AcademicWorkload::where('school_year_id', $Y->id)
                ->where('study_year_id', $tsg->group->study_year_id)
                ->where('subject_id', $tsg->subject->id)
                ->first()->hours_week;

            $attendancesWeek = Attendance::where('teacher_subject_group_id', $tsg->id)
                ->whereBetween('date', [
                    Carbon::parse($date)->startOfWeek()->format('Y-m-d H:i:s'),
                    Carbon::parse($date)->endOfWeek()->format('Y-m-d H:i:s')
                ])->count();

            return ['active' => (bool)($hoursWeek - $attendancesWeek), 'content' => $this->alertAttendanceWekkHtml($hoursWeek - $attendancesWeek)];
        }

        return false;
    }

    private function alertAttendanceWekkHtml($count)
    {
        if ( ! $count ) {
            return '<div class="alert alert-danger" role="alert">'. __("No assistance is available for that week.") .'</div>';
        }

        return '<div class="alert alert-info" role="alert">' . __("You have :COUNT assistance shots available for that week.", ['COUNT' => $count]) . '</div>';
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


    /*
     *
     *
     *  */
    public function download_guide_group(Teacher $teacher)
    {
        $currentYear = SchoolYearController::current_year();

        $TSG = $teacher->teacherSubjectGroups->where('school_year_id', $currentYear->id);

        /* Dirección para guardar los reportes generados */
        $pathUuid = Str::uuid();
        $pathReport = "reports/" . $pathUuid . "/";

        if (!File::isDirectory(public_path('app/' . $pathReport))) {
            File::makeDirectory(public_path('app/' . $pathReport), 0755, true, true);
        }

        foreach ($TSG as $teacherSubjectGroup) {
            Excel::store(
                new GroupStudentListGuide($teacherSubjectGroup),
                $pathReport . __('auxiliary template') . '_' . $teacherSubjectGroup->subject->resourceSubject->name . '_' . $teacherSubjectGroup->group->headquarters->name . '_' . $teacherSubjectGroup->group->studyTime->name . '_' . $teacherSubjectGroup->group->name . '_' . $teacherSubjectGroup?->teacher?->getFullName() . '.xlsx',
                'public'
            );
        }

        /* Generate Zip and Download */
        return (new ZipController($pathUuid))->downloadAllGuideGroups(Str::slug($teacher?->getFullName()));
    }
    /*
     *
     *
     *  */

    private function tab()
    {
        session()->flash('tab', 'teachers');
    }

    protected function uploadFile($request, $teacher, $file)
    {
        if ($request->hasFile($file)) {

            if (!is_null($teacher->$file)) {
                File::delete(public_path($teacher->$file));
            }

            $path = $request->file($file)->store('teachers/' . $teacher->uuid, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }

    protected function deleteDirectory($uuid)
    {
        if (is_dir(public_path('app/teachers/' . $uuid)))
            File::deleteDirectory(public_path('app/teachers/' . $uuid));
    }

    public function permitTab(Teacher $teacher)
    {
        session()->flash('tab', 'permits');
        return redirect()->route('teacher.show', $teacher);
    }
}
