<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\Notify;
use App\Models\Coordination;
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
    function __construct()
    {
        $this->middleware('can:myinstitution')->except('name', 'badge', 'email', 'handbook', 'numberStudents');
        $this->middleware('can:myinstitution.edit')->only('update');
        $this->middleware('can:support.access')->only('number_students_show', 'number_students_update');
    }

    private static function myschool()
    {
        return School::find(1) ?? null;
    }

    public function show()
    {
        return view('logro.school.show', [
            'studentsCount' => Student::count(),
            'school' => $this->myschool(),
            'daysToUpdate' => self::daysToUpdate(),
            'teachers' => Teacher::all(),
            'secretariats' => Secretariat::all(),
            'coordinations' => Coordination::all(),
            'orientations' => Orientation::all()
        ]);
    }

    public function update(Request $request)
    {
        if (self::daysToUpdate() > 0)
        {
            return redirect()->back()->withErrors(__('Not allowed'));
        }

        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'nit' => ['required', 'string', 'max:20'],
            'contact_email' => ['required', 'email', 'max:100'],
            'contact_telephone' => ['required', 'string', 'max:20'],
            'institutional_email' => ['nullable', 'string', 'max:191'],
            'handbook_coexistence' => ['required', 'url'],
            'badge' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);

        static::myschool()->update([
            'name' => $request->name,
            'nit' => $request->nit,
            'contact_email' => $request->contact_email,
            'contact_telephone' => $request->contact_telephone,
            'institutional_email' => $request->institutional_email,
            'handbook_coexistence' => $request->handbook_coexistence,
        ]);

        self::update_badge($request);

        Notify::success( __('Updated info!') );
        return redirect()->back();
    }

    private function update_badge($request)
    {
        $school = static::myschool();

        if ($request->hasFile('badge'))
        {
            $path = $this->upload_badge($request);
            File::delete(public_path($school->badge));

            $school->update([
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

    private function daysToUpdate()
    {
        $updatedAt = static::myschool()->updated_at;

        if ($updatedAt > now()->format('Y-m-d'))
            return Carbon::now()->diffInDays($updatedAt);

        return 0;
    }

    public static function all()
    {
        return static::myschool();
    }
    public static function name()
    {
        return static::myschool()->name ?? null;
    }
    public static function badge()
    {
        return static::myschool()->badge ?? null;
    }
    public static function email()
    {
        return static::myschool()->institutional_email ?? null;
    }
    public static function handbook()
    {
        return static::myschool()->handbook_coexistence ?? null;
    }
    public static function securityEmail()
    {
        return static::myschool()->security_email ?? null;
    }

    /*  */
    public static function numberStudents()
    {
        return static::myschool()->number_students;
    }
    public function number_students_show()
    {
        return view('support.students.number_show', ['number_students' => static::numberStudents()]);
    }
    public function number_students_update(Request $request)
    {
        $request->validate(['students' => 'required', 'numeric']);

        $numberCurrent = self::numberStudents();

        if ($numberCurrent == $request->students)
        {
            Notify::info( __('Unchanged!') );
            return redirect()->back();
        }

        self::myschool()->forceFill(
            ['number_students' => $request->students]
        )->save();

        Notify::success( __('Saved!') );
        return redirect()->back();

    }

    /* Security Email */
    public function security_email(Request $request)
    {
        if (self::daysToUpdate() > 0 && self::securityEmail() !== NULL)
        {
            return redirect()->back()->withErrors(__('Not allowed'));
        }

        $request->validate([
            'security_email' => ['required', 'email'],
            'code' => ['required']
        ]);

        Str::lower($request->email);

        $oldSecurityEmail = self::securityEmail();
        if ($oldSecurityEmail === $request->security_email)
        {
            Notify::fail(__("Unchanged!"));
            return redirect()->back();
        }

        $checkCode = SecurityCode::where('email', $request->security_email)
            ->where('code', $request->code)
            ->first();
        if ( $checkCode !== NULL )
        {
            self::myschool()->forceFill([
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

        if (self::daysToUpdate() > 0 && self::securityEmail() !== NULL)
        {
            return ['status' => false, 'message' => 'fail|' . __('Not allowed')];
        }

        if ($request->email === self::securityEmail())
        {
            return ['status' => false, 'message' => 'fail|' . __('Unchanged!')];
        }

        $generateCodeSecurity = SecurityCodeController::generate($request->email);

        if ($generateCodeSecurity === TRUE)
            return ['status' => true, 'message' => 'info|' . __("A code was sent to the registered email")];
        else
            return ['status' => false, 'message' => 'fail|' . $generateCodeSecurity];
    }
}
