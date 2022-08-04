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
        $this->middleware('can:studyTime.index');
        $this->middleware('can:studyTime.create')->only('create','store');
        $this->middleware('can:studyTime.edit')->only('show','edit','update');
        $this->middleware('can:studyTime.periods.edit')->only('periods_update');
    }

    public function index()
    {
        return view('logro.studytime.index');
    }

    public function data()
    {
        return ['data' => StudyTime::withCount('periods')->get()];
    }

    public function create()
    {
        return view('logro.studytime.create');
    }

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

    public function edit(StudyTime $studyTime)
    {
        return view('logro.studytime.edit')->with('studyTime', $studyTime);
    }

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
