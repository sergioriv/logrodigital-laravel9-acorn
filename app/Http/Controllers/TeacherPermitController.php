<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Teacher;
use App\Models\TeacherPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPermitController extends Controller
{
    public function store(Teacher $teacher, Request $request)
    {
        $request->validate([
            'short_description' => ['required', 'string', 'max:100'],
            'permit_date_start' => ['required', 'date'],
            'permit_date_end' => ['required', 'date'],
            'support_document' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $pathDocument = $this->upload_file($request, $teacher->uuid);
        if ( $pathDocument ) {

            TeacherPermit::create([
                'user_id' => Auth::user()->id,
                'teacher_id' => $teacher->id,
                'description' => $request->short_description,
                'start' => $request->permit_date_start,
                'end' => $request->permit_date_end,
                'url' => config('app.url') .'/'. $pathDocument,
                'url_absolute' => $pathDocument,
            ]);

            static::tab();
            Notify::success(__('Permit created!'));
            return redirect()->back();

        } else {
            return redirect()->back()->withErrors(__('An error occurred while loading the document.'));
        }
    }

    private function upload_file($request, $teacher_uuid)
    {
        if ( $request->hasFile('support_document') )
        {
            $path = $request->file('support_document')->store('teachers/'.$teacher_uuid.'/permits', 'public');
            return config('filesystems.disks.public.url') .'/' . $path;
        }
        else return null;
    }

    private function tab()
    {
        session()->flash('tab', 'permits');
    }
}
