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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request, StudyTime $studyTime)
    {
        $Y = SchoolYearController::current_year();

        $workloadTotal = 0;
        DB::beginTransaction();

        foreach ($request->period as $key => $period) {
            if ($period['start'] > $period['end']) {
                DB::rollBack();
                return redirect()->back()->withErrors(['custom' => __('Start date must be less than the end date of each period')]);
            }

            if (isset($period['id'])) {
                $updatePeriod = Period::where('id', $period['id'])
                    ->where('school_year_id', $Y->id)
                    ->where('study_time_id', $studyTime->id)
                    ->where('ordering', $key)->first();

                if (NULL !== $updatePeriod) {
                    $updatePeriod->update([
                        'name'  => $period['name'],
                        'start' => $period['start'],
                        'end'   => $period['end'],
                        'workload' => $period['workload'],
                        'days'  => $period['days']
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
                    'days'  => $period['days']
                ]);
            }

            $workloadTotal += $period['workload'];
        }

        if ($workloadTotal === 100) {
            DB::commit();
            Notify::success(__('Periods updated!'));
            return redirect()->route('studyTime.index');
        } else {
            DB::rollBack();
            return redirect()->back()->withErrors(__("workload not is 100%"));
        }

        DB::commit();

        Notify::success(__('Periods updated!'));
        return redirect()->route('studyTime.index');
    }
}
