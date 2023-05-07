<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Orientation;
use App\Models\OrientationDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrientationDegreeController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:ORIENTATION');
    }

    public function store(Request $request)
    {
        $orientation_id = Auth::id();

        $countDegrees = OrientationDegree::where('orientation_id', $orientation_id)->count();
        if ($countDegrees >= 5) {
            Notify::fail(__('Cannot carry more than 5 degrees'));
            return back();
        }

        $request->validate([
            'degree_name' => ['required', 'max:191'],
            'degree_institution' => ['required', 'max:191'],
            'degree_date' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'degree_file' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $orientationUuid = (Orientation::where('id', $orientation_id)->first())->uuid;

        DB::beginTransaction();

        try {

            $file = $this->uploadFile($request, $orientationUuid, 'degree_file');
            if (is_null($file))
                return false;

            OrientationDegree::create([
                'orientation_id' => $orientation_id,
                'institution' => $request->degree_institution,
                'degree' => $request->degree_name,
                'date' => $request->degree_date,
                'url' => $file
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        DB::commit();

        $this->tab();
        Notify::success(__('Degree saved!'));
        return back();

    }

    private function tab()
    {
        session()->flash('tab', 'degrees');
    }

    private function uploadFile($request, $orientator, $file)
    {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('orientators/' . $orientator, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }
}
