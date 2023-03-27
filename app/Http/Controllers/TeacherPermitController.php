<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Teacher;
use App\Models\TeacherPermit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherPermitController extends Controller
{
    protected $teacherPermits;

    public function __construct(Collection $teacherPermits = null)
    {
        $this->middleware('hasroles:SUPPORT,SECRETARY,TEACHER')->only('store');
        $this->middleware('hasroles:COORDINATOR')->only('acceptedOrDenied');

        $this->teacherPermits = $teacherPermits;
    }

    public function store(Teacher $teacher, Request $request)
    {
        $request->validate([
            'short_description' => ['required', 'string', 'max:1000'],
            'permit_date_start' => ['required', 'date'],
            'permit_date_end' => ['required', 'date'],
        ]);

        DB::beginTransaction();

        try {

            TeacherPermit::create([
                'user_id' => auth()->id(),
                'teacher_id' => $teacher->id,
                'description' => $request->short_description,
                'start' => $request->permit_date_start,
                'end' => $request->permit_date_end
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('An error has occurred'));
        }

        DB::commit();

        static::tab();
        Notify::success(__('Permit created!'));
        return redirect()->back();
    }

    public function store_document(Request $request, Teacher $teacher)
    {
        $request->validate([
            'permit' => ['required'],
            'support_document' => ['required', 'file', 'mimes:pdf', 'max:2048']
        ]);

        $permit = TeacherPermit::where('id', $request->permit)->where('teacher_id', $teacher->id)->first();

        if ( ! $permit->status->isDenied() && is_null($permit->support_document) ) {

            $fileUrl = $this->upload_file($request, $teacher->uuid);
            if ( ! is_null($fileUrl) ) {

                $permit->forceFill([
                    'support_document' => $fileUrl
                ])->save();

                Notify::success(__('File upload!'));

            } else {

                Notify::fail(__('An error has occurred'));
            }

        }

        static::tab();
        return back();
    }

    public function acceptedOrDenied(Request $request, Teacher $teacher)
    {
        $request->validate([
            'permit' => ['required'],
            'accept_or_deny' => ['required', 'in:accept,deny']
        ]);

        $permit = TeacherPermit::where('id', $request->permit)->where('teacher_id', $teacher->id)->first();

        if ( $permit?->status->isPending() ) {

            $status = match($request->accept_or_deny) {
                'accept' => 1,
                'deny' => 2,
                default => 0
            };

            $permit->update([
                'status' => $status,
                'accept_deny_type' => UserController::myModelIs(),
                'accept_deny_id' => auth()->id()
            ]);

            if ( $status === 1 ) {
                Notify::success(__('Permit accepted!'));
            } else if ( $status === 2 ) {
                Notify::success(__('Permit denied!'));
            }

        }

        static::tab();
        return back();
    }

    private function upload_file($request, $teacher_uuid)
    {
        if ( $request->hasFile('support_document') )
        {
            $path = $request->file('support_document')->store('teachers/'.$teacher_uuid.'/permits', 'public');
            if ($path)
                return config('app.url') .'/'.  config('filesystems.disks.public.url') .'/' . $path;
            return null;
        }
        else return null;
    }

    private function tab()
    {
        session()->flash('tab', 'permits');
    }


    public static function pendingPermits($status = 0)
    {
        return new static(
            TeacherPermit::where('status', $status)->get()
        );
    }

    public function getPermits()
    {
        return $this->teacherPermits;
    }

    public function groupByTeacher()
    {
        return $this->teacherPermits->groupBy(function ($permit) {
            return $permit->teacher_id;
        });
    }
}
