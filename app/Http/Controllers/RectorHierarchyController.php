<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Rector;
use App\Models\RectorHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RectorHierarchyController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:RECTOR');
    }

    public function store(Request $request)
    {
        $rector_id = Auth::id();

        $countHierarchy = RectorHierarchy::where('rector_id', $rector_id)->count();
        if ($countHierarchy >= 5) {
            Notify::fail(__('Cannot carry more than 5 hierarchies'));
            return back();
        }

        $request->validate([
            'hierarchy_number' => ['required', 'max:20'],
            'hierarchy_resolution' => ['required', 'max:191'],
            'hierarchy_date' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'hierarchy_file' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $rectorUuid = (Rector::where('id', $rector_id)->first())->uuid;

        DB::beginTransaction();

        try {

            $file = $this->uploadFile($request, $rectorUuid, 'hierarchy_file');
            if (is_null($file))
                return false;

            RectorHierarchy::create([
                'rector_id' => $rector_id,
                'number' => $request->hierarchy_number,
                'resolution' => $request->hierarchy_resolution,
                'date' => $request->hierarchy_date,
                'url' => $file
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        DB::commit();

        $this->tab();
        Notify::success(__('Hierarchy saved!'));
        return back();

    }

    private function tab()
    {
        session()->flash('tab', 'hierarchies');
    }

    private function uploadFile($request, $rector, $file)
    {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('rectors/' . $rector, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }
}
