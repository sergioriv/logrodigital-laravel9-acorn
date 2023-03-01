<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\Period;
use App\Models\StudyTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware(YearCurrentMiddleware::class)->only('update');
    }

    public function create(StudyTime $studyTime)
    {
        return view('logro.studytime.wizard-periods')->with([
            'Y' => SchoolYearController::current_year(),
            'studyTime' => $studyTime,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StudyTime $studyTime)
    {
        $request->validate([
            'period' => ['required', 'array'],
            'period.*.*' => ['required'],
            'period.*.start' => ['date'],
            'period.*.end' => ['date'],
            'period.*.workload' => ['numeric'],
            'period.*.start_grades' => ['date'],
            'period.*.end_grades' => ['date'],
        ]);

        return $this->save($request, $studyTime);
    }

    private function save($request, $studyTime)
    {
        $Y = SchoolYearController::current_year();

        $workloadTotal = 0;
        DB::beginTransaction();

        foreach ($request->period as $key => $period) {
            if ($period['start'] > $period['end']) {
                DB::rollBack();
                return redirect()->back()->withErrors(['custom' => __('Start date must be less than the end date of each period')]);
            }
            if ($period['start_grades'] > $period['end_grades']) {
                DB::rollBack();
                return redirect()->back()->withErrors(['custom' => __('The start date of grades must be less than the end date of grades for each period.')]);
            }

            if ( isset($period['id']) ) {
                $updatePeriod = Period::where('id', $period['id'])
                    ->where('study_time_id', $studyTime->id)
                    ->where('ordering', $key)->first();

                if (NULL !== $updatePeriod) {
                    $updatePeriod->update([
                        'name'  => $period['name'],
                        'start' => $period['start'],
                        'end'   => $period['end'],
                        'workload' => $period['workload'],
                        'start_grades' => $period['start_grades'],
                        'end_grades'   => $period['end_grades'],
                    ]);
                } else {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['custom' => __('Unexpected Error')]);
                }
            } else {
                Period::create([
                    'school_year_id' => $Y->id,
                    'study_time_id' => $studyTime->id,
                    'period_type_id' => 1,
                    'ordering' => $key,
                    'name'  => $period['name'],
                    'start' => $period['start'],
                    'end'   => $period['end'],
                    'workload' => $period['workload'],
                    'start_grades' => $period['start_grades'],
                    'end_grades'   => $period['end_grades'],
                ]);
            }

            $workloadTotal += $period['workload'];
        }

        if ($workloadTotal === 100) {
            DB::commit();

            $studyTime->forceFill(['active' => TRUE])->save();
            StudyTimeController::deleteNotActive();

            Notify::success(__('Study time updated!'));
            return redirect()->route('studyTime.show', [$studyTime]);
        } else {
            DB::rollBack();
            return redirect()->back()->withErrors(__("workload not is 100%"));
        }

    }

    public function edit(StudyTime $studyTime)
    {
        $Y = SchoolYearController::current_year();

        $periods = Period::where('school_year_id', $Y->id)->where('study_time_id', $studyTime->id)->orderBy('ordering')->get();
        return view('logro.studytime.periods-edit')->with([
            'Y' => $Y,
            'studyTime' => $studyTime,
            'periods' => $periods
        ]);
    }
}
