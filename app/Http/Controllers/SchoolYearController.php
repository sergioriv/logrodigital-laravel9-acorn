<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $years = SchoolYear::withCount('groups')->withSum('groups','student_quantity')->get();
        return view('logro.schoolyear.index')->with('years', $years);
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
        // SchoolYear::query()->update(['available' => FALSE]);

        SchoolYear::create([
            'name' => $request->name,
            'available' => FALSE
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

    public function choose(Request $request)
    {
        $request->validate([
            'school_year' => ['required', Rule::exists('school_years','id')]
        ]);

        $sy = $this->available_year()->id == $request->school_year ? null : $request->school_year;

        User::find(Auth::user()->id)->update([
            'school_year_id' => $sy
        ]);

        $schoolYear = SchoolYear::find($request->school_year);

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => $schoolYear->name .' '. __('selected!')],
        );
    }

    private function available_year()
    {
        return SchoolYear::select('id','name')->where('available',TRUE)->first();
    }

    public static function current_year()
    {
        $y = Auth::user()->school_year_id;
        if ( NULL === $y )
            return SchoolYear::select('id','name','available')->where('available',TRUE)->first();
        else
            return SchoolYear::select('id','name','available')->where('id',$y)->first();
    }
}
