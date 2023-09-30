<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\Notify;
use App\Models\Coordination;
use App\Models\HeadersAndFooters;
use App\Models\Orientation;
use App\Models\School;
use App\Models\Secretariat;
use App\Models\SecurityCode;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    protected $school;

    function __construct(School $school = null)
    {
        $this->middleware('can:myinstitution')->except('name', 'badge', 'email', 'handbook', 'signatureRector');
        $this->middleware('can:myinstitution.edit')->only('update', 'security_email', 'sendConfirmationEmail', 'signature_rector');
        $this->middleware('can:support.access')->only('number_students_show', 'number_students_update');

        $this->school = $school;
    }

    public function show()
    {
        $S = static::myschool();

        return view('logro.school.show', [
            'studentsCount' => Student::available()->count(),
            'school' => $S->getData(),
            'daysToUpdate' => $S->daysToUpdate(),
            'teachers' => Teacher::all(),
            'secretariats' => Secretariat::all(),
            'coordinations' => Coordination::all(),
            'orientations' => Orientation::all(),
            'headers_footers' => HeadersAndFooters::first()
        ]);
    }

    public function update(Request $request)
    {
        $S = static::myschool();
        if ($S->daysToUpdate() > 0)
        {
            return redirect()->back()->withErrors(__('Not allowed'));
        }

        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'nit' => ['required', 'string', 'max:20'],
            'dane' => ['nullable', 'string', 'max:191'],
            'contact_email' => ['required', 'email', 'max:100'],
            'contact_telephone' => ['required', 'string', 'max:20'],
            'institutional_email' => ['nullable', 'string', 'max:191'],
            'handbook_coexistence' => ['nullable', 'url'],
            'badge' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);

        $S->getData()->update([
            'name' => $request->name,
            'nit' => $request->nit,
            'dane' => $request->dane,
            'contact_email' => $request->contact_email,
            'contact_telephone' => $request->contact_telephone,
            'institutional_email' => $request->institutional_email,
            'handbook_coexistence' => $request->handbook_coexistence,
        ]);

        $this->update_badge($S, $request);

        Notify::success( __('Updated info!') );
        return redirect()->back();
    }

    private function update_badge($S, $request)
    {
        if ($request->hasFile('badge'))
        {
            $path = $this->upload_badge($request);
            File::delete(public_path($S->badge()));

            $S->getData()->update([
                'badge' => $path
            ]);
        }

    }

    private function upload_badge($request)
    {
        if ($request->hasFile('badge')) {
            $path = $request->file('badge')->store('badge', 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }


    /* Static access info */
    public static function myschool()
    {
        return new static(School::find(1) ?? null);
    }

    public function getData(): School
    {
        return $this->school;
    }

    private function daysToUpdate()
    {
        // $updatedAt = static::myschool()->getData()->updated_at;


        if ($this->school->updated_at > now()->format('Y-m-d'))
            return Carbon::now()->diffInDays($this->school->updated_at);

        return 0;
    }

    public function name()
    {
        return $this->school->name ?? null;
    }
    public function badge()
    {
        return $this->school->badge ?? null;
    }
    public function nit()
    {
        return $this->school->nit ?? null;
    }
    public function dane()
    {
        return $this->school->dane ?? null;
    }
    public function email()
    {
        return $this->school->institutional_email ?? null;
    }
    public function handbook()
    {
        return $this->school->handbook_coexistence ?? null;
    }
    public function securityEmail()
    {
        return $this->school->security_email ?? null;
    }
    public function signatureRector()
    {
        return $this->school->signature_rector ?? null;
    }


    /* Update number students */
    public function number_students_show()
    {
        $myschool = static::myschool()->getData();
        return view('support.students.number_show', ['number_students' => $myschool->number_students]);
    }
    public function number_students_update(Request $request)
    {
        $request->validate([
            'students' => ['required', 'numeric']
        ]);

        $myschool = static::myschool()->getData();

        if ($myschool->number_students == $request->students){

            Notify::info( __('Unchanged!') );
            return redirect()->back();
        }

        $myschool->forceFill([
            'number_students' => $request->students
        ])->save();

        Notify::success( __('Saved!') );
        return redirect()->back();
    }

    /* Security Email */
    public function security_email(Request $request)
    {
        $S = static::myschool();

        if ($S->daysToUpdate() > 0 && $S->securityEmail() !== NULL)
        {
            return redirect()->back()->withErrors(__('Not allowed'));
        }

        $request->validate([
            'security_email' => ['required', 'email'],
            'code' => ['required']
        ]);

        Str::lower($request->email);

        if ($S->securityEmail() === $request->security_email)
        {
            Notify::fail(__("Unchanged!"));
            return redirect()->back();
        }

        $checkCode = SecurityCode::where('email', $request->security_email)
            ->where('code', $request->code)
            ->first();
        if ( $checkCode !== NULL )
        {
            $S->getData()->forceFill([
                'security_email' => $request->security_email
            ])->save();
        } else
        {
            session()->flash('tab', 'security');
            Notify::fail(__("Code invalid"));
            return redirect()->back();
        }

        session()->flash('tab', 'security');
        Notify::success(__("Security email changed!"));
        return redirect()->back();
    }

    public function sendConfirmationEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $S = static::myschool();

        if ($S->daysToUpdate() > 0 && $S->securityEmail() !== NULL)
        {
            return ['status' => false, 'message' => 'fail|' . __('Not allowed')];
        }

        if ($request->email === $S->securityEmail())
        {
            return ['status' => false, 'message' => 'fail|' . __('Unchanged!')];
        }

        $generateCodeSecurity = SecurityCodeController::generate($request->email);

        if ($generateCodeSecurity === TRUE)
            return ['status' => true, 'message' => 'info|' . __("A code was sent to the registered email")];
        else
            return ['status' => false, 'message' => 'fail|' . $generateCodeSecurity];
    }


    /* Signature Rector */
    public function signature_rector(Request $request)
    {
        $request->validate([
            'rector_name' => ['required', 'string', 'max:191'],
            'signature_rector' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);

        $S = static::myschool()->getData();

        $path = $this->uploadSignature($request, 'signature_rector', $S);
        if (is_null($path)) {
            $path = $S->signature_rector;
        }

        $S->forceFill([
            'rector_name' => $request->rector_name,
            'signature_rector' => $path
        ])->save();

        session()->flash('tab', 'signature');
        Notify::success(__("Info Rector updated!"));
        return back();
    }
    private function uploadSignature($request, $file, $S)
    {
        if ($request->hasFile($file)) {

            if (!is_null($S->$file)) {
                File::delete(public_path($S->$file));
            }

            $path = $request->file($file)->store('school/', 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }

    public function additional(Request $request)
    {
        $request->validate([
            'docs_header' => ['required', 'string', 'max:1000'],
            'footer_school_certificate' => ['nullable', 'string', 'max:2000']
        ]);


        \App\Models\HeadersAndFooters::first()->update([
            'header_docs' => $request->docs_header,
            'footer_school_certificate' => $request->footer_school_certificate
        ]);

        session()->flash('tab', 'additional');
        Notify::success(__("Additional info updated!"));
        return back();
    }
}
