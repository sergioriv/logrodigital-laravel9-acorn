<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Student;
use App\Models\StudentObserver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentObserverController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:students.observer');
    }

    public function store(Request $request, Student $student)
    {
        $request->validate([
            'annotation_type' => ['required'],
            'date_observation' => ['required', 'date', 'date_format:Y-m-d', 'before:tomorrow'],
            'situation_description' => ['required', 'string', 'max:5000']
        ]);

        StudentObserver::create([
            'student_id' => $student->id,
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
            'accepts_or_rejects' => ['nullable']
        ]);


        $observer = StudentObserver::find($request->observer);

        if ( auth()->id() !== $observer->created_user_id || ! is_null($observer->free_version) ) {
            Notify::fail(__('Not allowed'));
            $this->tab();
            return back();
        }

        $observer->update([
            'free_version' => $request->free_version_or_disclaimers,
            'agreements' => $request->agreements_or_commitments,
            'accept' => $this->requestAccept($request->accepts_or_rejects)
        ]);

        Notify::success(__('Updated observation!'));
        $this->tab();
        return back();
    }

    private function requestAccept($request)
    {
        return match ($request) {
            'accept' => 1,
            'reject' => 0,
            default => null
        };
    }

    private function tab()
    {
        session()->flash('tab', 'observer');
    }
}
