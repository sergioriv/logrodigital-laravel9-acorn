<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Orientation;
use App\Models\OrientationPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrientationPermitController extends Controller
{
    public function store(Orientation $orientation, Request $request)
    {
        $request->validate([
            'short_description' => ['required', 'string', 'max:100'],
            'permit_date_start' => ['required', 'date'],
            'permit_date_end' => ['required', 'date'],
            'support_document' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $pathDocument = $this->upload_file($request, $orientation->uuid);
        if ( $pathDocument ) {

            OrientationPermit::create([
                'user_id' => Auth::id(),
                'orientation_id' => $orientation->id,
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
