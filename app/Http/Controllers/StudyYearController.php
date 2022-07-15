<?php

namespace App\Http\Controllers;


use App\Models\ResourceArea;
use App\Models\SchoolYear;
use App\Models\StudyYear;
use App\Models\StudyYearSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudyYearController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:studyYear');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        $studyYears = StudyYear::withCount(['studyYearSubject' => function ($subjects) use ($Y) {
                $subjects->where('school_year_id', $Y->id);
            }])
            ->withCount(['groups' => function ($groups) use ($Y) {
                $groups->where('school_year_id', $Y->id);
            }])
            ->withSum(['groups' => function ($groups) use ($Y) {
                $groups->where('school_year_id', $Y->id);
            }], 'student_quantity')
            ->get();

        return view('logro.studyyear.index')->with([
            'Y' => $Y->name,
            'studyYears' => $studyYears
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function create()
    {
        return view('support.studyyear.create');
    } */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /* public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_years')]
        ]);

        StudyYear::create([
            'name' => $request->name,
            'available' => TRUE
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year created!')],
        );
    } */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    /* public function show(StudyYear $studyYear)
    {
        //
    } */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    /* public function edit(StudyYear $studyYear)
    {
        return view('support.studyyear.edit')->with('studyYear', $studyYear);
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    /* public function update(Request $request, StudyYear $studyYear)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_years')->ignore($studyYear->id)]
        ]);

        $studyYear->update([
            'name' => $request->name
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year updated!')],
        );
    } */

    public function subjects(StudyYear $studyYear)
    {
        $Y = SchoolYearController::current_year();

        $subject_count = StudyYearSubject::where('school_year_id', $Y->id)->where('study_year_id', $studyYear->id)->count();

        if ($subject_count == 0 && NULL !== $Y->available)
        {
            /*
             * Create Study Year Subjects
             */
            $areas = ResourceArea::with(['subjects' => function ($subjects) use ($Y) {
                $subjects->where('school_year_id', $Y->id);
            }])->get();

            return view('logro.studyyear.subjects')->with([
                'studyYear' => $studyYear,
                'areas' => $areas,
            ]);

        } else
        {
            /*
             * Show Study Year Subjects
             */
            $areas = ResourceArea::with(['subjects' => function ($subjects) use ($Y, $studyYear) {
                $subjects->where('school_year_id', $Y->id)
                    ->whereHas('studyYearSubject', function ($sy) use ($studyYear) {
                        $sy->where('study_year_id', $studyYear->id);
                    });
            }])
            ->whereHas('subjects', function ($sj) use ($Y, $studyYear) {
                $sj->where('school_year_id', $Y->id)
                        ->whereHas('studyYearSubject', function ($sy) use ($studyYear) {
                            $sy->where('study_year_id', $studyYear->id);
                        });
            })
            ->get();

            return view('logro.studyyear.subjects_show')->with([
                'studyYear' => $studyYear,
                'areas' => $areas,
            ]);
        }

    }

    public function subjects_store(StudyYear $studyYear, Request $request)
    {
        $request->validate([
            'subjects' => ['required', 'array']
        ]);

        $Y = SchoolYearController::current_year();

        if (NULL !== $Y->available)
        {

            DB::beginTransaction();

            $areas = [];
            $total_course_load = 0;

            foreach ($request->subjects as $area_subject) {

                [$area, $subject] = explode('~',$area_subject);
                array_push($areas, $area);

                $hours_week = $subject.'~hours_week';
                $course_load = $subject.'~course_load';

                if (empty($request->$hours_week) || empty($request->$course_load))
                {
                    DB::rollBack();
                    return redirect()->back()->withErrors( __("empty fields") );
                }

                if ($request->$course_load > 100)
                {
                    DB::rollBack();
                    return redirect()->back()->withErrors( __("academic load must not exceed 100%") );
                }

                $total_course_load += $request->$course_load;

                StudyYearSubject::create([
                    'school_year_id' => $Y->id,
                    'study_year_id' => $studyYear->id,
                    'subject_id' => $subject,
                    'hours_week' => $request->$hours_week,
                    'course_load' => $request->$course_load
                ]);
            }

            $areas_total = count(array_unique($areas)) * 100;

            if ($total_course_load === $areas_total)
            {
                DB::commit();
                return redirect()->route('studyYear.subject.show', $studyYear)->with(
                    ['notify' => 'success', 'title' => __('Updated!')],
                );
            } else
            {
                DB::rollBack();
                return redirect()->back()->withErrors( __("check the course load") );
            }

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );
        }

    }


}
