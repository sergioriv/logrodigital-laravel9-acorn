<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Student;
use App\Models\StudentObserver;
use Illuminate\Http\Request;

class StudentObserverController extends Controller
{
    public function __construct()
    {

    }

    public function store(Request $request, Student $student)
    {
        $request->validate([
            'annotation_type' => ['required'],
            'date_annotation' => ['required', 'date', 'date_format:Y-m-d', 'before:tomorrow'],
            'situation_description' => ['required', 'string', 'max:1000'],
            'free_version_or_disclaimers' => ['nullable', 'string', 'max:1000'],
            'agreements_or_commitments' => ['nullable', 'string', 'max:1000'],
        ]);

        StudentObserver::create([
            'student_id' => $student->id,
            'annotation_type' => $request->annotation_type,
            'date' => $request->date_annotation,
            'situation_description' => $request->situation_description,
            'free_version' => $request->free_version_or_disclaimers,
            'agreements' => $request->agreements_or_commitments,
            'created_user_id' => auth()->id(),
            'created_rol' => UserController::role_auth()
        ]);

        Notify::success(__('Observation saved!'));
        $this->tab();
        return back();
    }

    private function tab()
    {
        session()->flash('tab', 'observer');
    }
}
