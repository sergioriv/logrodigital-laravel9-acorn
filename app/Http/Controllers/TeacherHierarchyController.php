<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Teacher;
use App\Models\TeacherHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TeacherHierarchyController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlyTeachersMiddleware::class);
    }

    public function store(Request $request)
    {
        $teacher_id = Auth::id();

        $countHierarchy = TeacherHierarchy::where('teacher_id', $teacher_id)->count();
        if ($countHierarchy >= 5) {
            Notify::fail(__('Cannot carry more than 5 hierarchies'));
            return back();
        }

        $request->validate([
            'hierarchy_number' => ['required', 'max:20'],
            'hierarchy_resolution' => ['required', 'max:191'],
            'hierarchy_date' => ['required', 'date' ,'before:today'],
            'hierarchy_file' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $teacherUuid = (Teacher::where('id', $teacher_id)->first())->uuid;

        DB::beginTransaction();

        try {

            $file = $this->uploadFile($request, $teacherUuid, 'hierarchy_file');
            if (is_null($file))
                return false;

            TeacherHierarchy::create([
                'teacher_id' => $teacher_id,
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

    private function uploadFile($request, $teacher, $file)
    {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('teachers/' . $teacher, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }
}
