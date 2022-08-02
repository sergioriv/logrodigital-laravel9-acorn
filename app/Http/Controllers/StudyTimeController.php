<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\StudyTime;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudyTimeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:studyTime');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logro.studytime.index');
    }

    public function data()
    {
        return ['data' => StudyTime::withCount('periods')->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.studytime.create');
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
            'name' => ['required', 'string', Rule::unique('study_times')],
        ]);

        StudyTime::create([
            'name' => $request->name
        ]);

        return redirect()->route('studyTime.index')->with(
            ['notify' => 'success', 'title' => __('Study time created!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyTime  $studyTime
     * @return \Illuminate\Http\Response
     */
    public function show(StudyTime $studyTime)
    {
        $Y = SchoolYearController::current_year();

        $periods = Period::where('school_year_id', $Y->id)->where('study_time_id', $studyTime->id)->orderBy('ordering')->get();
        return view('logro.studytime.show')->with([
            'Y' => $Y,
            'studyTime' => $studyTime,
            'periods' => $periods
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudyTime  $studyTime
     * @return \Illuminate\Http\Response
     */
    public function edit(StudyTime $studyTime)
    {
        return view('logro.studytime.edit')->with('studyTime', $studyTime);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyTime  $studyTime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyTime $studyTime)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_times')->ignore($studyTime->id)]
        ]);

        $studyTime->update([
            'name' => $request->name
        ]);

        return redirect()->route('studyTime.show', $studyTime)->with(
            ['notify' => 'success', 'title' => __('Study time updated!')],
        );
    }

    public function periods_update(Request $request, StudyTime $studyTime)
    {

        $request->validate([
            'period' => ['required', 'array'],
            'period.*.*' => ['required'],
            'period.*.start' => ['date'],
            'period.*.end' => ['date'],
            'period.*.days' => ['numeric']
        ]);
        return PeriodController::update($request, $studyTime);
    }
}
