<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\Period;
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

        $this->middleware(YearCurrentMiddleware::class)->only('periods_update');
    }

    public function index()
    {
        return view('logro.studytime.index');
    }

    public function data()
    {
        $Y = SchoolYearController::current_year();
        return ['data' => StudyTime::withCount(['periods' => fn($p) => $p->where('school_year_id', $Y->id)])->get()];
    }

    public function create()
    {
        return view('logro.studytime.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_times')],
            'missing_areas_check' => ['nullable', 'boolean'],
            'missing_areas' => ['required_with:missing_areas_check', 'numeric', 'min:1', 'max:10'],
            'conceptual' => ['required', 'numeric', 'min:0', 'max:100'],
            'procedural' => ['required', 'numeric', 'min:0', 'max:100'],
            'attitudinal' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        if ( ($request->conceptual + $request->procedural + $request->attitudinal) !== 100 )
        {
            return redirect()->back()->withErrors(__("evaluation components is not equal to 100%"));
        }

        StudyTime::create([
            'name' => $request->name,
            'conceptual' => $request->conceptual,
            'procedural' => $request->procedural,
            'attitudinal' => $request->attitudinal,
            'missing_areas' => $request->missing_areas
        ]);

        Notify::success(__('Study time created!'));
        return redirect()->route('studyTime.index');
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

        Notify::success(__('Study time updated!'));
        return redirect()->route('studyTime.show', $studyTime);
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
