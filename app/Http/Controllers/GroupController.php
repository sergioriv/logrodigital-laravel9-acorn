<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Headquarters;
use App\Models\ResourceArea;
use App\Models\SchoolYear;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        $groups = Group::with('headquarters', 'studyTime', 'studyYear', 'teacher')->where('school_year_id', $Y->id)->get();

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

        return $groups->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $Y = SchoolYearController::current_year();

        $sy = $group->study_year_id;

        $areas = $this->subjects_teacher($Y->id, $sy, $group->id);

        return view('logro.group.show')->with([
            'Y' => $Y,
            'group' => $group,
            'areas' => $areas
        ]);
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
