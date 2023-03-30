<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Orientation;
use App\Models\OrientationPermit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrientationPermitController extends Controller
{
    protected $orientationPermits;

    public function __construct(Collection $orientationPermits = null)
    {
        $this->middleware('hasroles:SUPPORT,SECRETARY,ORIENTATION')->only('store');

        $this->orientationPermits = $orientationPermits;
    }

    public function store(Orientation $orientation, Request $request)
    {
        $request->validate([
            'type_permit' => ['required', Rule::exists('type_permits_teachers', 'id')],
            'short_description' => ['required', 'string', 'max:100'],
            'permit_date_start' => ['required', 'date'],
            'permit_date_end' => ['required', 'date'],
        ]);

        DB::beginTransaction();

        try {

            OrientationPermit::create([
                'user_id' => auth()->id(),
                'orientation_id' => $orientation->id,
                'type_permit_id' => $request->type_permit,
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

    public function store_document(Request $request, Orientation $orientation)
    {
        $request->validate([
            'permit' => ['required'],
            'support_document' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $permit = OrientationPermit::where('id', $request->permit)->where('orientation_id', $orientation->id)->first();

        if ( ! $permit->status->isDenied() && is_null($permit->support_document) ) {

            $fileUrl = $this->upload_file($request, $orientation->uuid);
            if ( ! is_null($fileUrl) ) {

                $permit->forceFill([
                    'support_document' => $fileUrl
                ])->save();

                Notify::success(__('File upload!'));

            } else {

                Notify::fail(__('An error has occurred'));
            }

        }

        static::tab();
        return back();
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
            if ($path)
                return config('app.url') .'/'.  config('filesystems.disks.public.url') .'/' . $path;
            return null;
        }
        else return null;
    }

    private function tab()
    {
        session()->flash('tab', 'permits');
    }


    public static function pendingPermits($status = 0)
    {
        return new static(
            OrientationPermit::where('status', 0)->get()
        );
    }

    public function getPermits()
    {
        return $this->orientationPermits;
    }

    public function groupByOrientator()
    {
        return $this->orientationPermits->groupBy(function ($permit) {
            return $permit->orientation_id;
        });
    }
}
