<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Coordination;
use App\Models\CoordinationEmploymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoordinationEmploymentHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:COORDINATOR');
    }

    public function store(Request $request)
    {
        $coordination_id = Auth::id();

        $countDegrees = CoordinationEmploymentHistory::where('coordination_id', $coordination_id)->count();
        if ($countDegrees >= 5) {
            Notify::fail(__('Cannot carry more than 5 institutions'));
            return back();
        }

        $request->validate([
            'employment_institution' => ['required', 'max:191'],
            'employment_date_start' => ['required', 'date' ,'before:today'],
            'employment_date_end' => ['required', 'date' ,'before:today'],
            'employment_file' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $coordinationUuid = (Coordination::where('id', $coordination_id)->first())->uuid;

        DB::beginTransaction();

        try {

            $file = $this->uploadFile($request, $coordinationUuid, 'employment_file');
            if (is_null($file))
                return false;

            CoordinationEmploymentHistory::create([
                'coordination_id' => $coordination_id,
                'institution' => $request->employment_institution,
                'date_start' => $request->employment_date_start,
                'date_end' => $request->employment_date_end,
                'url' => $file
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        DB::commit();

        $this->tab();
        Notify::success(__('Info employment saved!'));
        return back();

    }

    private function tab()
    {
        session()->flash('tab', 'employments');
    }

    private function uploadFile($request, $coordinator, $file)
    {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('coordinators/' . $coordinator, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }
}
