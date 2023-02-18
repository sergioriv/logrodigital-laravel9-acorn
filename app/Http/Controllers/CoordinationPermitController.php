<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Coordination;
use App\Models\CoordinationPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinationPermitController extends Controller
{
    public function store(Coordination $coordination, Request $request)
    {
        $request->validate([
            'short_description' => ['required', 'string', 'max:100'],
            'permit_date_start' => ['required', 'date'],
            'permit_date_end' => ['required', 'date'],
            'support_document' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $pathDocument = $this->upload_file($request, $coordination->uuid);
        if ( $pathDocument ) {

            CoordinationPermit::create([
                'user_id' => Auth::id(),
                'coordination_id' => $coordination->id,
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

    private function upload_file($request, $coordination_uuid)
    {
        if ( $request->hasFile('support_document') )
        {
            $path = $request->file('support_document')->store('coordinators/'.$coordination_uuid.'/permits', 'public');
            return config('filesystems.disks.public.url') .'/' . $path;
        }
        else return null;
    }

    private function tab()
    {
        session()->flash('tab', 'permits');
    }
}
