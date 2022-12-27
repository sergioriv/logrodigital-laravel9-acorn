<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Group;
use App\Models\Remark;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RemarkController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlyTeachersMiddleware::class)->only('store');
    }

    public function store(Request $request, Group $group)
    {
        $request->validate([
            'remark' => ['required', 'array'],
            'period' => ['required', Rule::exists('periods', 'id')],
            // 'remark.*' => ['required']
        ]);


        DB::beginTransaction();

        foreach ($request->remark as $key => $value) {
            if (!is_null($value)) {

                /* Comprobar que el estudiante este registrado en el grupo */

                $student = Student::where('code', $key)->first();

                Remark::updateOrCreate(
                    [
                        'group_id' => $group->id,
                        'period_id' => $request->period,
                        'student_id' => $student->id,
                    ],
                    [
                        'remark' => $value
                    ]
                );
            }
        }

        DB::commit();

        Notify::success(__('Saved remarks!'));
        return redirect()->route('group.show', $group->id);
    }
}
