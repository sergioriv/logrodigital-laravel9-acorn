<?php

namespace App\Http\Controllers;

use App\Models\ResourceArea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceAreaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:resourceArea');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logro.resource.area.index');
    }

    public function data()
    {
        return ['data' => ResourceArea::orderBy('name')->get()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.resource.area.create');
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
            'name' => ['required', 'string', Rule::unique('resource_areas')],
        ]);

        ResourceArea::create([
            'name' => $request->name
        ]);

        return redirect()->route('resourceArea.index')->with(
            ['notify' => 'success', 'title' => __('Area created!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResourceArea  $resourceArea
     * @return \Illuminate\Http\Response
     */
    public function show(ResourceArea $resourceArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResourceArea  $resourceArea
     * @return \Illuminate\Http\Response
     */
    public function edit(ResourceArea $area)
    {
        return view('logro.resource.area.edit')->with('area', $area);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResourceArea  $resourceArea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResourceArea $area)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('resource_areas')->ignore($area->id)]
        ]);

        $area->update([
            'name' => $request->name
        ]);

        return redirect()->route('resourceArea.index')->with(
            ['notify' => 'success', 'title' => __('Area updated!')],
        );
    }
}
