<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Data\RoleUser;
use App\Models\Student;
use App\Models\StudentObserver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StudentObserverController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:students.observer');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'students_observer' => ['required', 'array'],
            'annotation_type' => ['required'],
            'date_observation' => ['required', 'date', 'date_format:Y-m-d', 'before:tomorrow'],
            'situation_description' => ['required', 'string', 'max:5000']
        ]);

        $Y = SchoolYearController::current_year();
        $insert = [];
        foreach ($request->students_observer as $student) {

            array_push(
                $insert,
                [
                    'id' => Str::uuid()->toString(),
                    'student_id' => $student,
                    'school_year_id' => $Y->id,
                    'annotation_type' => $request->annotation_type,
                    'date' => $request->date_observation,
                    'situation_description' => $request->situation_description,
                    'created_user_id' => auth()->id(),
                    'created_rol' => UserController::myModelIs()
                ]
            );
        }

        try {

            StudentObserver::insert($insert);

            Notify::success(__('Observation saved!'));

        } catch (\Throwable $th) {

            Notify::fail(__('An error has occurred'));
        }

        return back();
    }

    public function store(Request $request, Student $student)
    {
        $request->validate([
            'annotation_type' => ['required'],
            'date_observation' => ['required', 'date', 'date_format:Y-m-d', 'before:tomorrow'],
            'situation_description' => ['required', 'string', 'max:5000']
        ]);

        $Y = SchoolYearController::current_year();

        StudentObserver::create([
            'student_id' => $student->id,
            'school_year_id' => $Y->id,
            'annotation_type' => $request->annotation_type,
            'date' => $request->date_observation,
            'situation_description' => $request->situation_description,
            'created_user_id' => auth()->id(),
            'created_rol' => UserController::myModelIs()
        ]);

        Notify::success(__('Observation saved!'));
        $this->tab();
        return back();
    }

    public function disclaimers(Request $request)
    {
        $request->validate([
            'observer' => ['required', Rule::exists('student_observer', 'id')],
            'free_version_or_disclaimers' => ['required', 'string', 'max:5000'],
            'agreements_or_commitments' => ['nullable', 'string', 'max:5000'],
            'accepts_or_rejects' => ['nullable'],
            'file_observation' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048']
        ]);

        $observer = StudentObserver::find($request->observer);

        if ( (
                RoleUser::COORDINATION_ROL !== UserController::role_auth()
                && auth()->id() !== $observer->created_user_id
            )
            || ! is_null($observer->free_version)
        ) {
            Notify::fail(__('Not allowed'));
            $this->tab();
            return back();
        }

        $path_file = $this->upload_file($request, $observer->student_id);

        $observer->update([
            'free_version' => $request->free_version_or_disclaimers,
            'agreements' => $request->agreements_or_commitments,
            'accept' => $this->requestAccept($request->accepts_or_rejects),
            'file_support' => $path_file
        ]);

        Notify::success(__('Updated observation!'));
        $this->tab();
        return back();
    }

    private static function upload_file($request, $student_id)
    {
        if ($request->hasFile('file_observation')) {
            $path = $request->file('file_observation')->store('students/' . $student_id . '/observer', 'public');
            return $path ? config('filesystems.disks.public.url') . '/' . $path : null;
        } else return null;
    }

    private function requestAccept($request)
    {
        return match ($request) {
            'accept' => 1,
            'reject' => 0,
            default => null
        };
    }

    public function delete(Request $request, Student $student)
    {
        if ( !$request->has('observationForDelete') ) return back();

        $observation = StudentObserver::where('student_id', $student->id)->where('id', $request->get('observationForDelete'))->first();
        if ( !$observation ) {
            Notify::success(__('Not allowed'));
            $this->tab();
            return back();
        }

        if ( (
            RoleUser::COORDINATION_ROL !== UserController::role_auth()
            && auth()->id() !== $observation->created_user_id
        )
            || ! is_null($observation->free_version)
        ) {
            Notify::fail(__('Not allowed'));
            $this->tab();
            return back();
        }

        $observation->delete();

        Notify::success(__('Deleted observation!'));
        $this->tab();
        return back();
    }

    private function tab()
    {
        session()->flash('tab', 'observer');
    }
}
