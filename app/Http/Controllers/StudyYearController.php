<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\ResourceArea;
use App\Models\SchoolYear;
use App\Models\StudyYear;
use App\Models\StudyYearSubject;
use Illuminate\Http\Request;
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
        $studyYears = StudyYear::withCount(['studyYearSubject' => function ($subjects) {
                $subjects->where('school_year_id', $this->current_year()->id);
            }])
            ->withCount(['groups' => function ($groups) {
                $groups->where('school_year_id', $this->current_year()->id);
            }])
            ->with(['groups' => function ($groups) {
                $groups->where('school_year_id', $this->current_year()->id)->withCount('groupStudents');
            }])
            ->get();

        // $subjects = StudyYearSubject::where('school_year_id', $this->current_year()->id)->get();

        // return $groups = Group::where('school_year_id', $this->current_year()->id)->withCount('groupStudents')->get();

        return view('logro.studyyear.index')->with([
            'year' => $this->current_year()->name,
            'studyYears' => $studyYears
            // 'subjects' => $subjects,
            // 'groups' => $groups
        ]);
    }

    /* public function data()
    {
        $studyYear = StudyYear::withCount('groups')->withCount('studyYearSubject')->get();

        return ['data' => $studyYear];
    } */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('support.studyyear.create');
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
            'name' => ['required', 'string', Rule::unique('study_years')]
        ]);

        StudyYear::create([
            'name' => $request->name,
            'available' => TRUE
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year created!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    public function show(StudyYear $studyYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    public function edit(StudyYear $studyYear)
    {
        return view('support.studyyear.edit')->with('studyYear', $studyYear);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyYear $studyYear)
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
    }

    public function subjects(StudyYear $studyYear)
    {
        $subject_count = StudyYearSubject::where('school_year_id', $this->current_year()->id)->where('study_year_id', $studyYear->id)->count();

        if ($subject_count == 0)
        {
            /*
             * Create Study Year Subjects
             */
            $areas = ResourceArea::with(['subjects' => function ($subjects) {
                $subjects->where('school_year_id', $this->current_year()->id);
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
            $areas = ResourceArea::with('subjects')->whereHas('subjects', function ($sj) use ($studyYear) {
                $sj->where('school_year_id', $this->current_year()->id)
                        ->whereHas('studyYearSubject', function ($sy) use ($studyYear) {
                            $sy->where('study_year_id', $studyYear->id);
                        });
            })->get();

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


        foreach ($request->subjects as $subject) {
            StudyYearSubject::create([
                'school_year_id' => $this->current_year()->id,
                'study_year_id' => $studyYear->id,
                'subject_id' => $subject
            ]);
        }

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year updated!')],
        );

    }


    /* Aditionals */
    private function current_year()
    {
        return SchoolYear::select('id','name')->where('available',TRUE)->first();
    }
}
