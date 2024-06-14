<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Rector;
use App\Models\RectorEmploymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RectorEmploymentHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:RECTOR');
    }

    public function store(Request $request)
    {
        $rector_id = Auth::id();

        $countDegrees = RectorEmploymentHistory::where('rector_id', $rector_id)->count();
        if ($countDegrees >= 5) {
            Notify::fail(__('Cannot carry more than 5 institutions'));
            return back();
        }

        $request->validate([
            'employment_institution' => ['required', 'max:191'],
            'employment_date_start' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'employment_date_end' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'employment_file' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $rectorUuid = (Rector::where('id', $rector_id)->first())->uuid;

        DB::beginTransaction();

        try {

            $file = $this->uploadFile($request, $rectorUuid, 'employment_file');
            if (is_null($file))
                return false;

            RectorEmploymentHistory::create([
                'rector_id' => $rector_id,
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

    private function uploadFile($request, $rector, $file)
    {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('rectors/' . $rector, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }
}
