<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Headquarters;
use App\Models\SchoolYear;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::with('headquarters', 'studyTime', 'studyYear', 'teacher')->where('school_year_id', $this->current_year()->id)->get();
        return view('logro.group.index')->with('groups', $groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $headquarters = Headquarters::where('available', TRUE)->get();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::where('available', TRUE)->get();
        $teachers = Teacher::select('id','first_name','father_last_name')->get();
        return view('logro.group.create')->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'teachers' => $teachers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'headquarters' => ['required', 'numeric', Rule::exists('headquarters','id')],
            'study_time' => ['required', 'numeric', Rule::exists('study_times','id')],
            'study_year' => ['required', 'numeric', Rule::exists('study_years','id')],
            'teacher' => ['required', 'numeric', Rule::exists('teachers','id')],
            'name' => ['required', 'string']
        ]);

        Group::create([
            'school_year_id' => $this->current_year()->id,
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->study_time,
            'study_year_id' => $request->study_year,
            'teacher_id' => $request->teacher,
            'name' => $request->name,
        ]);

        return redirect()->route('group.index')->with(
            ['notify' => 'success', 'title' => __('Group created!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }


    /* Aditionals */
    private function current_year()
    {
        return SchoolYear::select('id','name')->where('available',TRUE)->first();
    }
}
