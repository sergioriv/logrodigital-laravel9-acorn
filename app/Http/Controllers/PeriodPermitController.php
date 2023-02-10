<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyCoordinationMiddleware;
use App\Models\PeriodPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PeriodPermitController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:group.subject.period.active');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject-permit-id' => [Rule::exists('teacher_subject_groups', 'id')],
            'period-permit' => ['required', Rule::exists('periods', 'id')]
        ]);

        try {
            PeriodPermit::create([
                'teacher_subject_group_id' => $request->get('subject-permit-id'),
                'period_id' => $request->get('period-permit'),
                'user_created_id' => Auth::id()
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(__('Unexpected Error'));
        }

        Notify::success(__('Permit created!'));
        return redirect()->back();

    }
}
