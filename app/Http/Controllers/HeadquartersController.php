<?php

namespace App\Http\Controllers;

use App\Exports\StudentsForHeadquartersExport;
use App\Http\Controllers\support\Notify;
use App\Models\Headquarters;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class HeadquartersController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:headquarters.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();
        $hq = Headquarters::query()
            ->orderBy('name')->get();

        return view('logro.headquarters.index', [
            'Y' => $Y,
            'headquarters' => $hq
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.headquarters.create');
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
            'name' => ['required', 'string', Rule::unique('headquarters')]
        ]);

        Headquarters::create([
            'name' => $request->name,
            'available' => TRUE
        ]);

        Notify::success( __('Headquarters created!') );
        return redirect()->route('headquarters.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function show(Headquarters $headquarters)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function edit(Headquarters $headquarters)
    {
        return view('logro.headquarters.edit')->with('headquarters', $headquarters);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Headquarters $headquarters)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('headquarters')->ignore($headquarters->id)]
        ]);

        $headquarters->update([
            'name' => $request->name
        ]);

        Notify::success( __('Headquarters updated!') );
        return redirect()->route('headquarters.index');
    }

    public function download_students(Headquarters $headquarters)
    {
        $students = Student::with('headquarters', 'studyTime', 'studyYear', 'group')
            ->where('headquarters_id', $headquarters->id)
            ->where('enrolled', 1)->get();

        return Excel::download(new StudentsForHeadquartersExport($students), 'Estudiantes - ' . $headquarters->name . '.xlsx' );
    }
}
