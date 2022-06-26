<?php

namespace App\Http\Controllers;

use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudyYearController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:studyYear');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('support.studyyear.index');
    }

    public function data()
    {
        return ['data' => StudyYear::get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('support.studyyear.create');
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
            'name' => ['required', 'string', Rule::unique('study_years')]
        ]);

        StudyYear::create([
            'name' => $request->name,
            'available' => TRUE
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year created!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    public function show(StudyYear $studyYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    public function edit(StudyYear $studyYear)
    {
        return view('support.studyyear.edit')->with('studyYear', $studyYear);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyYear $studyYear)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_years')->ignore($studyYear->id)]
        ]);

        $studyYear->update([
            'name' => $request->name
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year updated!')],
        );
    }
}
