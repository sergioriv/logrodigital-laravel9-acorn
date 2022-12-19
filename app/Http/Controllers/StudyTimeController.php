<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\Period;
use App\Models\ResourceStudyYear;
use App\Models\StudyTime;
use App\Models\StudyYear;
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
        return view('logro.studytime.index', ['studyTimes' => StudyTime::orderByDesc('created_at')->get()]);
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
            'minimum_grade' => ['required', 'numeric', 'min:0'],
            'low_performance' => ['required', 'numeric', 'min:0'],
            'basic_performance' => ['required', 'numeric', 'min:0'],
            'high_performance' => ['required', 'numeric', 'min:0'],
            'maximum_grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'decimal' => ['required', 'integer', 'min:0', 'max:2'],
            'round' => ['required', 'in:up,down']
        ]);

        $step = 0.01;
        if ( $request->decimal == 2 ) {
            $step = 0.01;
        }
        if ( $request->decimal == 1 ) {
            $step = 0.1;
        }
        if ( $request->decimal == 0 ) {
            $step = 1;
        }

        if ( ($request->conceptual + $request->procedural + $request->attitudinal) !== 100 )
            return redirect()->back()->withErrors(__("evaluation components is not equal to 100%"));

        if ($request->minimum_grade > ($request->low_performance - $step))
            return redirect()->back()->withErrors(__('Review the range of :performance', [
                        'performance' => __('low performance')
                    ]));

        if ($request->low_performance > ($request->basic_performance - ($step * 2)))
            return redirect()->back()->withErrors(__('Review the range of :performance', [
                        'performance' => __('basic performance')
                    ]));

        if ($request->basic_performance > ($request->high_performance - ($step * 2)))
            return redirect()->back()->withErrors(__('Review the range of :performance', [
                        'performance' => __('high performance')
                    ]));

        if ($request->high_performance > ($request->maximum_grade - ($step * 2)))
            return redirect()->back()->withErrors(__('Review the range of :performance', [
                        'performance' => __('superior performance')
                    ]));


        $round = 0;
        if ($request->round === 'up') {
            $round = 1;

            $request->minimum_grade = round($request->minimum_grade, $request->decimal, PHP_ROUND_HALF_UP);
            $request->low_performance = round($request->low_performance, $request->decimal, PHP_ROUND_HALF_UP);
            $request->basic_performance = round($request->basic_performance, $request->decimal, PHP_ROUND_HALF_UP);
            $request->high_performance = round($request->high_performance, $request->decimal, PHP_ROUND_HALF_UP);
            $request->maximum_grade = round($request->maximum_grade, $request->decimal, PHP_ROUND_HALF_UP);
        } else {
            $request->minimum_grade = round($request->minimum_grade, $request->decimal, PHP_ROUND_HALF_DOWN);
            $request->low_performance = round($request->low_performance, $request->decimal, PHP_ROUND_HALF_DOWN);
            $request->basic_performance = round($request->basic_performance, $request->decimal, PHP_ROUND_HALF_DOWN);
            $request->high_performance = round($request->high_performance, $request->decimal, PHP_ROUND_HALF_DOWN);
            $request->maximum_grade = round($request->maximum_grade, $request->decimal, PHP_ROUND_HALF_DOWN);
        }


        $studyTime = StudyTime::create([
            'name' => $request->name,
            'conceptual' => $request->conceptual,
            'procedural' => $request->procedural,
            'attitudinal' => $request->attitudinal,
            'missing_areas' => $request->missing_areas,
            'minimum_grade' => $request->minimum_grade,
            'low_performance' => $request->low_performance,
            'basic_performance' => $request->basic_performance,
            'high_performance' => $request->high_performance,
            'maximum_grade' => $request->maximum_grade,
            'decimal' => $request->decimal,
            'round' => $round,
            'step' => $step
        ]);

        return redirect()->route('studyTime.periods', $studyTime);
    }

    public function show(StudyTime $studyTime)
    {
        $Y = SchoolYearController::current_year();

        // $periods = Period::where('school_year_id', $Y->id)->where('study_time_id', $studyTime->id)->orderBy('ordering')->get();
        $periods = Period::where('study_time_id', $studyTime->id)->orderBy('ordering')->get();
        // $studyYears = StudyYear::where('study_time_id', $studyTime->id)->get();
        return view('logro.studytime.show')->with([
            'Y' => $Y,
            'studyTime' => $studyTime,
            'periods' => $periods
        ]);
    }

    /* public function edit(StudyTime $studyTime)
    {
        return view('logro.studytime.edit')->with('studyTime', $studyTime);
    } */

    /* public function update(Request $request, StudyTime $studyTime)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_times')->ignore($studyTime->id)]
        ]);

        $studyTime->update([
            'name' => $request->name
        ]);

        Notify::success(__('Study time updated!'));
        return redirect()->route('studyTime.show', $studyTime);
    } */

    /* public function periods_create(StudyTime $studyTime)
    {
        return view('logro.studytime.wizard-periods')->with([
            'studyTime' => $studyTime,
        ]);
    } */

    /* public function periods_store(Request $request, StudyTime $studyTime)
    {

        $request->validate([
            'period' => ['required', 'array'],
            'period.*.*' => ['required'],
            'period.*.start' => ['date'],
            'period.*.end' => ['date'],
            'period.*.workload' => ['numeric'],
            'period.*.days' => ['numeric']
        ]);
        return PeriodController::create($request, $studyTime);
    } */

    /*
     * study times that did not complete the creation process are eliminated.
     */
    public static function deleteNotActive()
    {
        $noActive = StudyTime::whereNull('active')->get();
        if ($noActive->count() > 0) {
            foreach ($noActive as $st) {
                $st->periods()->delete();
                $st->delete();
            }
        }
    }
}
