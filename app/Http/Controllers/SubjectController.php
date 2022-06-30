<?php

namespace App\Http\Controllers;

use App\Models\ResourceArea;
use App\Models\ResourceSubject;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:subject');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_year = $this->current_year();

        $resourceAreas = ResourceArea::get();

        $resourceSubjects = ResourceSubject::whereNot(function ($query) use ($current_year) {
            $query->whereHas('subjects', function ($subject) use ($current_year) {
                $subject->where('school_year_id', $current_year->id);
            });
        })->get();

        $subjects = Subject::with('resourceSubject')->where('school_year_id', $current_year->id)->get();
        return view('logro.subject.index')->with([
            'resourceAreas' => $resourceAreas,
            'resourceSubjects' => $resourceSubjects,
            'subjects' => $subjects,
            'year' => $current_year->name
        ]);
    }

    /* public function data()
    {
        return ['data' => Subject::where('school_year_id', $this->current_year()->id)->get()];
    } */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.subject.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->subjects as $are_subject) {
            [$area, $subject] = explode('~',$are_subject);
            if ('null' !== $area) {
                Subject::create([
                    'school_year_id' => $this->current_year()->id,
                    'resource_area_id' => $area,
                    'resource_subject_id' => $subject
                ]);
            }
        }

        return redirect()->route('subject.index')->with(
            ['notify' => 'success', 'title' => __('Areas & Subjects updated!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }


    /* Aditionals */
    private function current_year()
    {
        return SchoolYear::select('id','name')->where('available',TRUE)->first();
    }
}
