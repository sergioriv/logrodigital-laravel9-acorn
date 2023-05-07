<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupDirectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:SUPPORT,COORDINATOR,SECRETARY');
    }

    public function index()
    {
        $Y = SchoolYearController::current_year();
        $groups = Group::where('school_year_id', $Y->id)
            ->whereNull('specialty')
            ->with('teacher:id,uuid,names,last_names')
            ->orderBy('headquarters_id')
            ->orderBy('study_time_id')
            ->orderBy('study_year_id')
            ->get();

        return view('logro.group.director.index', [
            'groups' => $groups
        ]);
    }

    public function edit(Group $group_director)
    {
        $teachers = Teacher::select('id', 'uuid', 'names', 'last_names')
        ->where('active', TRUE)->get()
        ->map(function ($teacherMap) use ($group_director) {
            return [
                'id' => $teacherMap->id,
                'uuid' => $teacherMap->uuid,
                'names' => $teacherMap->getFullName(),
                'isDirector' => $group_director->teacher_id === $teacherMap->id
            ];
        });

        return view('logro.group.director.edit', [
            'groupID' => $group_director->id,
            'teachers' => $teachers
        ])->render();
    }

    public function update(Request $request, Group $group_director)
    {
        $validate = $request->validate([
            'new_director' => ['required', Rule::exists('teachers', 'uuid')->where('active', TRUE)]
        ]);

        $teacher = Teacher::select('id')->whereUuid($validate['new_director'])->first();

        try {
            $group_director->update([
                'teacher_id' => $teacher->id
            ]);
        } catch (\Throwable $th) {
            Notify::fail(__('An error has occurred') );
            return back();
        }

        Notify::success(__('Saved!'));
        return back();
    }
}
