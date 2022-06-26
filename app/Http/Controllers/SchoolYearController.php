<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolYearController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:schoolYear');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logro.schoolyear.index');
    }

    public function data()
    {
        return ['data' => SchoolYear::orderBy('id','DESC')->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.schoolyear.create');
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
            'name' => ['required', 'string', Rule::unique('school_years')],
        ]);

        /* school years available = false */
        SchoolYear::query()->update(['available' => FALSE]);

        SchoolYear::create([
            'name' => $request->name,
            'available' => TRUE
        ]);

        return redirect()->route('schoolYear.index')->with(
            ['notify' => 'success', 'title' => __('New School Year in process!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolYear $schoolYear)
    {
        //
    }
}
