<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Orientation;
use App\Models\OrientationPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrientationPermitController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:SUPPORT,SECRETARY,ORIENTATION')->only('store');
    }

    public function store(Orientation $orientation, Request $request)
    {
        $request->validate([
            'short_description' => ['required', 'string', 'max:100'],
            'permit_date_start' => ['required', 'date'],
            'permit_date_end' => ['required', 'date'],
        ]);

        DB::beginTransaction();

        try {

            OrientationPermit::create([
                'user_id' => auth()->id(),
                'orientation_id' => $orientation->id,
                'description' => $request->short_description,
                'start' => $request->permit_date_start,
                'end' => $request->permit_date_end,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('An error occurred while loading the document.'));
        }

        DB::commit();

        static::tab();
        Notify::success(__('Permit created!'));
        return redirect()->back();
    }

    public function acceptedOrDenied(Request $request, Orientation $orientation)
    {
        $request->validate([
            'permit' => ['required'],
            'accept_or_deny' => ['required', 'in:accept,deny']
        ]);

        $permit = OrientationPermit::where('id', $request->permit)->where('orientation_id', $orientation->id)->first();

        if ( $permit?->status->isPending() ) {

            $status = match($request->accept_or_deny) {
                'accept' => 1,
                'deny' => 2,
                default => 0
            };

            $permit->update([
                'status' => $status,
                'accept_deny_type' => UserController::myModelIs(),
                'accept_deny_id' => auth()->id()
            ]);

            if ( $status === 1 ) {
                Notify::success(__('Permit accepted!'));
            } else if ( $status === 2 ) {
                Notify::success(__('Permit denied!'));
            }

        }

        static::tab();
        return back();
    }

    private function upload_file($request, $orientation_uuid)
    {
        if ( $request->hasFile('support_document') )
        {
            $path = $request->file('support_document')->store('orientators/'.$orientation_uuid.'/permits', 'public');
            return config('filesystems.disks.public.url') .'/' . $path;
        }
        else return null;
    }

    private function tab()
    {
        session()->flash('tab', 'permits');
    }
}
