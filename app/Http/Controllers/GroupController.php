<?php

namespace App\Http\Controllers;

use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Headquarters;
use App\Models\ResourceArea;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    function __construct()
    {
        $this->middleware('can:groups.index');
        $this->middleware('can:groups.create')->only('create','store');
        // $this->middleware('can:groups.students');
        $this->middleware('can:groups.students.matriculate')->only('matriculate','matriculate_update');
        // $this->middleware('can:groups.teachers');
        $this->middleware('can:groups.teachers.edit')->only('teacher_edit','teacher_update');

        $this->middleware(YearCurrentMiddleware::class)->except('index','filter','show');
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

    public function filter(Request $request){
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

        if(NULL !== $name)
            $groups->where('name', 'like', '%' . $name . '%');

        $groups->orderBy('headquarters_id')
            ->orderBy('study_time_id')
            ->orderBy('study_year_id');

        return $groups->get();
    }

    public function create()
    {
        $Y = SchoolYearController::current_year();

        if( NULL !== $Y->available )
        {
            $headquarters = Headquarters::where('available', TRUE)->get();
            $studyTime = StudyTime::all();
            $studyYear = StudyYear::where('available', TRUE)->get();
            $teachers = Teacher::select('id','first_name','father_last_name')->get();

            return view('logro.group.create')->with([
                'headquarters' => $headquarters,
                'studyTime' => $studyTime,
                'studyYear' => $studyYear,
                'teachers' => $teachers
            ]);


        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('It is not possible to create a group for ') . $Y->name],
            );
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'headquarters' => ['required', 'numeric', Rule::exists('headquarters','id')],
            'study_time' => ['required', 'numeric', Rule::exists('study_times','id')],
            'study_year' => ['required', 'numeric', Rule::exists('study_years','id')],
            'teacher' => ['required', 'numeric', Rule::exists('teachers','id')],
            'name' => ['required', 'string']
        ]);

        $Y = SchoolYearController::current_year();

        if( NULL !== $Y->available )
        {

            Group::create([
                'school_year_id' => $Y->id,
                'headquarters_id' => $request->headquarters,
                'study_time_id' => $request->study_time,
                'study_year_id' => $request->study_year,
                'teacher_id' => $request->teacher,
                'name' => $request->name,
            ]);

            return redirect()->route('group.index')->with(
                ['notify' => 'success', 'title' => __('Group created!')],
            );

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('It is not possible to create a group for ') . $Y->name],
            );
        }
    }

    public function show(Group $group)
    {
        $Y = SchoolYearController::current_year();

        $sy = $group->study_year_id;

        $studentsGroup = Student::where('group_id', $group->id)
            ->orderBy('father_last_name')
            ->orderBy('mother_last_name');
        $areas = $this->subjects_teacher($Y->id, $sy, $group->id);

        return view('logro.group.show')->with([
            'Y' => $Y,
            'group' => $group,
            'studentsGroup' => $studentsGroup->get(),
            'areas' => $areas
        ]);
    }

    public function matriculate(Group $group)
    {
        $Y = SchoolYearController::current_year();

        /* $fn_g = fn($g) => $g->where('school_year_id', $Y->id);

        $fn_gs = fn($gs) =>
                $gs->with(['group' => $fn_g ])
                ->whereHas('group', $fn_g ); */

        $studentsNoEnrolled = Student::select(
            'id',
            'first_name',
            'second_name',
            'father_last_name',
            'mother_last_name',
            'inclusive','status'
            )->with('headquarters','studyTime','studyYear')
            ->where('school_year_create', '<=', $Y->id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->whereNull('enrolled')
            ->orderBy('father_last_name')
            ->orderBy('mother_last_name');
            /* ->whereNot(fn($q) =>
                $q->whereHas('groupYear', $fn_gs)
                    ->with(['groupYear' => $fn_gs]) */
            // );


        return view('logro.group.matriculate')->with([
            'group' => $group,
            'studentsNoEnrolled' => $studentsNoEnrolled->get()
        ]);
    }

    public function matriculate_update(Group $group, Request $request)
    {
        $request->validate([
            'students' => ['required','array']
        ]);

        foreach ($request->students as $student)
        {
            $studentNoNull = Student::where('id', $student)
                ->where('headquarters_id', $group->headquarters_id)
                ->where('study_time_id', $group->study_time_id)
                ->where('study_year_id', $group->study_year_id)
                ->whereNull('enrolled')->first();

            if ( NULL !== $studentNoNull )
            {
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

            } else
            {
                return redirect()->back()->withErrors( __("Unexpected Error") );
            }
        }

        return redirect()->route('group.show', $group)->with(
            ['notify' => 'success', 'title' => __('Students matriculate!')],
        );
    }

    public function teacher_edit(Group $group)
    {
        $Y = SchoolYearController::current_year();

        if( NULL !== $Y->available )
        {

            $sy = $group->study_year_id;

            $teachers = Teacher::where('active', TRUE)->get();

            $areas = $this->subjects_teacher($Y->id, $sy, $group->id);

            return view('logro.group.teachers_edit')->with([
                'group' => $group,
                'areas' => $areas,
                'teachers' => $teachers
            ]);

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );
        }
    }

    public function teacher_update(Group $group, Request $request)
    {
        $Y = SchoolYearController::current_year();

        if( NULL !== $Y->available )
        {

            foreach ($request->teachers as $teacher_subject) {
                if(NULL !== $teacher_subject)
                {

                    [$create, $subject, $teacher] = explode('~',$teacher_subject);

                    if ('null' === $create) {
                        TeacherSubjectGroup::create([
                            'school_year_id' => $Y->id,
                            'teacher_id' => $teacher,
                            'subject_id' => $subject,
                            'group_id' => $group->id
                        ]);
                    } else
                    {
                        echo 'update | ';
                        $teacherGroup = TeacherSubjectGroup::find($create);
                        $teacherGroup->update([
                            'teacher_id' => $teacher
                        ]);
                    }
                }

            }

            return redirect()->route('group.show', $group)->with(
                ['notify' => 'success', 'title' => __('Group updated!')],
            );

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );
        }
    }

    private function subjects_teacher($Y_id, $sy_id, $g_id)
    {
        $fn_sy = fn($sy) =>
                $sy->where('school_year_id', $Y_id)
                ->where('study_year_id', $sy_id);

        $fn_tsg = fn($tsg) =>
                $tsg->where('school_year_id', $Y_id)
                ->where('group_id', $g_id);

        $fn_sb = fn($s) =>
                $s->where('school_year_id', $Y_id)

                ->whereHas('studyYearSubject', $fn_sy)
                ->with(['studyYearSubject' => $fn_sy])

                ->with(['teacherSubjectGroups' => $fn_tsg]);

        return ResourceArea::with(['subjects' => $fn_sb])
                    ->whereHas('subjects', $fn_sb)
                    ->get();
    }

}
