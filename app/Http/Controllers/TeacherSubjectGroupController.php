<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;

class TeacherSubjectGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Teacher $teacher)
    {
        return view('logro.teacher-subject.create')->with('teacher', $teacher);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeacherSubjectGroup  $teacherSubjectGroup
     * @return \Illuminate\Http\Response
     */
    public function show(TeacherSubjectGroup $teacherSubjectGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeacherSubjectGroup  $teacherSubjectGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(TeacherSubjectGroup $teacherSubjectGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeacherSubjectGroup  $teacherSubjectGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeacherSubjectGroup $teacherSubjectGroup)
    {
        //
    }



    /* Aditionals */
    private function current_year()
    {
        return SchoolYear::select('id','name')->where('available',TRUE)->first();
    }
}
