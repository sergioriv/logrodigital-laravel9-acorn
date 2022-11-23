<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\Data\RoleUser;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Headquarters;
use App\Models\Period;
use App\Models\ResourceArea;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    function __construct()
    {
        $this->middleware('can:groups.index')->only('index');
        $this->middleware('can:groups.create')->only('create', 'store', 'edit', 'update');
        // $this->middleware('can:groups.students');
        $this->middleware('can:groups.students.matriculate')->only('matriculate', 'matriculate_update');
        // $this->middleware('can:groups.teachers');
        $this->middleware('can:groups.teachers.edit')->only('teacher_edit', 'teacher_update');

        $this->middleware(YearCurrentMiddleware::class)->except('index', 'filter', 'show');
    }

    public function index()
    {
        $Y = SchoolYearController::current_year();

        $groups = Group::with('headquarters', 'studyTime', 'studyYear', 'teacher')->where('school_year_id', $Y->id)
            ->orderBy('headquarters_id')
            ->orderBy('study_time_id')
            ->orderBy('study_year_id')
            ->get();

        $headquarters = Headquarters::all();
        $studyTimes = StudyTime::all();
        $studyYears = StudyYear::all();

        return view('logro.group.index')->with([
            'Y' => $Y,
            'groups' => $groups,
            'headquarters' => $headquarters,
            'studyTimes' => $studyTimes,
            'studyYears' => $studyYears
        ]);
    }

    public function filter(Request $request)
    {
        $Y = SchoolYearController::current_year();

        $hq = $request->headquarters;
        $st = $request->studyTime;
        $sy = $request->studyYear;
        $name = $request->name;

        $groups = Group::with('headquarters', 'studyTime', 'studyYear', 'teacher')->where('school_year_id', $Y->id);

        if (NULL !== $hq)
            $groups->where('headquarters_id', $hq);

        if (NULL !== $st)
            $groups->where('study_time_id', $st);

        if (NULL !== $sy)
            $groups->where('study_year_id', $sy);

        if (NULL !== $name)
            $groups->where('name', 'like', '%' . $name . '%');

        $groups->orderBy('headquarters_id')
            ->orderBy('study_time_id')
            ->orderBy('study_year_id');

        return $groups->get();
    }

    public function create()
    {

        $headquarters = Headquarters::where('available', TRUE)->get();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        $teachers = Teacher::select('uuid', 'names', 'last_names')->get();

        return view('logro.group.create')->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'teachers' => $teachers
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'study_time' => ['required', Rule::exists('study_times', 'id')],
            'study_year' => ['required', Rule::exists('study_years', 'id')],
            'group_director' => ['nullable', Rule::exists('teachers', 'uuid')],
            'name' => ['required', 'string']
        ]);

        $Y = SchoolYearController::current_year();

        $uuidTeacher = Teacher::select('id')->find($request->group_director);

        Group::create([
            'school_year_id' => $Y->id,
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->study_time,
            'study_year_id' => $request->study_year,
            'teacher_id' => $uuidTeacher->id,
            'name' => $request->name,
        ]);

        Notify::success(__('Group created!'));
        return redirect()->route('group.index');
    }

    public function show(Group $group)
    {

        /*
         * Para que el Rol TEACHER no pueda acceder a ningun grupo, solo tiene acceso a las asignaturas
         *  */
        if (UserController::role_auth() === RoleUser::TEACHER_ROL) {
            return redirect()->route('teacher.my.subjects')->withErrors(__('Unauthorized'));
        }


        $Y = SchoolYearController::current_year();

        $sy = $group->study_year_id;

        $studentsGroup = Student::where('group_id', $group->id)
            ->orderBy('first_last_name')
            ->orderBy('second_last_name');
        $areas = $this->subjects_teacher($Y->id, $sy, $group->id);

        return view('logro.group.show')->with([
            'Y' => $Y,
            'group' => $group,
            'count_studentsNoEnrolled' => $this->countStudentsNoEnrolled($Y, $group),
            'studentsGroup' => $studentsGroup->get(),
            'areas' => $areas
        ]);
    }

    public function edit(Group $group)
    {
        $headquarters = Headquarters::where('available', TRUE)->get();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        $teachers = Teacher::select('id', 'uuid', 'names', 'last_names')->get();

        return view('logro.group.edit')->with([
            'group' => $group,
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'teachers' => $teachers
        ]);
    }

    public function update(Group $group, Request $request)
    {
        $request->validate([
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'study_time' => ['required', Rule::exists('study_times', 'id')],
            'study_year' => ['required', Rule::exists('study_years', 'id')],
            'group_director' => ['nullable', Rule::exists('teachers', 'uuid')],
            'name' => ['required', 'string']
        ]);

        $uuidTeacher = Teacher::select('id')->find($request->group_director);

        $group->update([
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->study_time,
            'study_year_id' => $request->study_year,
            'teacher_id' => $uuidTeacher->id,
            'name' => $request->name,
        ]);

        Notify::success(__('Group updated!'));
        return redirect()->route('group.show', $group);
    }

    public function matriculate(Group $group)
    {
        $Y = SchoolYearController::current_year();

        $studentsNoEnrolled = Student::select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'document_type_code',
            'document',
            'inclusive',
            'status'
        )->with('headquarters', 'studyTime', 'studyYear')
            ->where('school_year_create', '<=', $Y->id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->whereNull('enrolled')
            ->orderBy('first_last_name')
            ->orderBy('second_last_name')
            ->get();

        if (0 === count($studentsNoEnrolled)) {
            Notify::fail(__('No students to enroll'));
            return redirect()->back();
        }

        return view('logro.group.matriculate')->with([
            'group' => $group,
            'studentsNoEnrolled' => $studentsNoEnrolled
        ]);
    }

    public function matriculate_update(Group $group, Request $request)
    {
        $request->validate([
            'students' => ['required', 'array']
        ]);

        foreach ($request->students as $student) {
            $studentNoNull = Student::where('id', $student)
                ->where('headquarters_id', $group->headquarters_id)
                ->where('study_time_id', $group->study_time_id)
                ->where('study_year_id', $group->study_year_id)
                ->whereNull('enrolled')->first();

            if (NULL !== $studentNoNull) {
                GroupStudent::create([
                    'group_id' => $group->id,
                    'student_id' => $student
                ]);

                $group->update([
                    'student_quantity' => ++$group->student_quantity
                ]);

                $studentNoNull->update([
                    'group_id' => $group->id,
                    'enrolled_date' => now(),
                    'enrolled' => TRUE
                ]);

                /* Send mail to Email Person Charge */
                SmtpMail::sendEmailEnrollmentNotification($studentNoNull, $group);
            } else {
                return redirect()->back()->withErrors(__("Unexpected Error"));
            }
        }

        Notify::success(__('Students matriculate!'));
        return redirect()->route('group.show', $group);
    }

    public function teacher_edit(Group $group)
    {
        $Y = SchoolYearController::current_year();

        $sy = $group->study_year_id;

        $teachers = Teacher::where('active', TRUE)->get();

        $areas = $this->subjects_teacher($Y->id, $sy, $group->id);

        return view('logro.group.teachers_edit')->with([
            'group' => $group,
            'areas' => $areas,
            'teachers' => $teachers
        ]);
    }

    public function teacher_update(Group $group, Request $request)
    {
        $Y = SchoolYearController::current_year();

        foreach ($request->teachers as $teacher_subject) {
            if (NULL !== $teacher_subject) {

                [$subject, $teacher] = explode('~', $teacher_subject);

                $uuidTeacher = Teacher::select('id')->find($teacher);

                TeacherSubjectGroup::updateOrCreate(
                    [
                        'school_year_id' => $Y->id,
                        'group_id' => $group->id,
                        'subject_id' => $subject
                    ],
                    [
                        'teacher_id' => $uuidTeacher->id
                    ]
                );
            }
        }

        Notify::success(__('Group updated!'));
        return redirect()->route('group.show', $group);
    }

    /* public function teacher_update(Group $group, Request $request)
    {
        $Y = SchoolYearController::current_year();

        foreach ($request->teachers as $teacher_subject) {
            if (NULL !== $teacher_subject) {

                [$create, $subject, $teacher] = explode('~', $teacher_subject);

                $uuidTeacher = Teacher::select('id')->find($teacher);

                if ('null' === $create) {
                    TeacherSubjectGroup::create([
                        'school_year_id' => $Y->id,
                        'teacher_id' => $uuidTeacher->id,
                        'subject_id' => $subject,
                        'group_id' => $group->id
                    ]);
                } else {
                    echo 'update | ';
                    $teacherGroup = TeacherSubjectGroup::find($create);
                    $teacherGroup->update([
                        'teacher_id' => $uuidTeacher->id
                    ]);
                }
            }
        }

        Notify::success( __('Group updated!') );
        return redirect()->route('group.show', $group);
    } */

    private function subjects_teacher($Y_id, $sy_id, $g_id)
    {
        $fn_sy = fn ($sy) =>
        $sy->where('school_year_id', $Y_id)
            ->where('study_year_id', $sy_id);

        $fn_tsg = fn ($tsg) =>
        $tsg->where('school_year_id', $Y_id)
            ->where('group_id', $g_id);

        $fn_sb = fn ($s) =>
        $s->where('school_year_id', $Y_id)

            ->whereHas('studyYearSubject', $fn_sy)
            ->with(['studyYearSubject' => $fn_sy])

            ->with(['teacherSubjectGroups' => $fn_tsg]);

        return ResourceArea::with(['subjects' => $fn_sb])
            ->whereHas('subjects', $fn_sb)
            ->get();
    }


    /* ADICIONALES */
    private function countStudentsNoEnrolled($Y, $group)
    {
        return $count_studentsNoEnrolled = Student::where('school_year_create', '<=', $Y->id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->whereNull('enrolled')
            ->count();
    }
}
