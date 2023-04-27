<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\CoordinationPermit;
use App\Models\Data\RoleUser;
use App\Models\OrientationPermit;
use App\Models\TeacherPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PermitController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:TEACHER,COORDINATOR,ORIENTATION');
    }

    public function store(Request $request)
    {
        return match(UserController::role_auth()) {
            RoleUser::TEACHER_ROL => $this->storeTeacher($request),
            RoleUser::COORDINATION_ROL => $this->storeCoordination($request),
            RoleUser::ORIENTATION_ROL => $this->storeOrientation($request)
        };
        return back();
    }

    private function storeTeacher($request)
    {
        $request->validate([
            'type_permit' => ['required', Rule::exists('type_permits_teachers', 'id')],
            'short_description' => ['required', 'string', 'max:1000'],
            'permit_date_start' => ['required', 'date', 'date_format:Y-m-d'],
            'permit_date_end' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        DB::beginTransaction();

        try {

            TeacherPermit::create([
                'user_id' => auth()->id(),
                'teacher_id' => auth()->id(),
                'type_permit_id' => $request->type_permit,
                'description' => $request->short_description,
                'start' => $request->permit_date_start,
                'end' => $request->permit_date_end
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('An error has occurred'));
        }

        DB::commit();

        static::tab();
        Notify::success(__('Permit created!'));
        return redirect()->back();
    }

    private function storeCoordination($request)
    {
        $request->validate([
            'type_permit' => ['required', Rule::exists('type_permits_teachers', 'id')],
            'short_description' => ['required', 'string', 'max:1000'],
            'permit_date_start' => ['required', 'date', 'date_format:Y-m-d'],
            'permit_date_end' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        DB::beginTransaction();

        try {

            CoordinationPermit::create([
                'user_id' => auth()->id(),
                'coordination_id' => auth()->id(),
                'type_permit_id' => $request->type_permit,
                'description' => $request->short_description,
                'start' => $request->permit_date_start,
                'end' => $request->permit_date_end
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('An error has occurred'));
        }

        DB::commit();

        static::tab();
        Notify::success(__('Permit created!'));
        return redirect()->back();
    }

    private function storeOrientation($request)
    {
        $request->validate([
            'type_permit' => ['required', Rule::exists('type_permits_teachers', 'id')],
            'short_description' => ['required', 'string', 'max:1000'],
            'permit_date_start' => ['required', 'date', 'date_format:Y-m-d'],
            'permit_date_end' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        DB::beginTransaction();

        try {

            OrientationPermit::create([
                'user_id' => auth()->id(),
                'orientation_id' => auth()->id(),
                'type_permit_id' => $request->type_permit,
                'description' => $request->short_description,
                'start' => $request->permit_date_start,
                'end' => $request->permit_date_end
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('An error has occurred'));
        }

        DB::commit();

        static::tab();
        Notify::success(__('Permit created!'));
        return redirect()->back();
    }

    private function tab()
    {
        session()->flash('tab', 'permits');
    }
}
