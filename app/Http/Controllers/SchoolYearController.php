<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
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
        $this->middleware('can:schoolYear.select');
        $this->middleware('can:schoolYear.create')->only('create','store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
         *
         * Pendiente para agregar la cantidad de estudiantes matriculados por ciclo escolar
         *
         *  */
        $years = SchoolYear::withCount('groups')->get();
        return view('logro.schoolyear.index')->with('years', $years);
    }

    /* public function data()
    {
        return ['data' => SchoolYear::orderBy('id','DESC')->get()];
    } */

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

        SchoolYear::create([
            'name' => $request->name,
            'available' => FALSE
        ]);

        Notify::success( __('New School Year in process!') );
        return redirect()->route('schoolYear.index');
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

        $sy = self::available_year()->id == $request->school_year ? null : $request->school_year;

        User::find(Auth::id())->update([
            'school_year_id' => $sy
        ]);

        $schoolYear = SchoolYear::find($request->school_year);

        Notify::success( __(':year selected!', ['year' => $schoolYear->name]) );
        return redirect()->back();
    }

    public static function available_year()
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

    public static function next_year()
    {
        return SchoolYear::where('available', '0')->first();
    }
}
