<?php

namespace App\Http\Controllers;

use App\Models\ResourceArea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:resourceAreas.index');
        $this->middleware('can:resourceAreas.edit')->only('create','store','edit','update');
    }

    public function index()
    {
        return view('logro.resource.area.index');
    }

    public function data()
    {
        return ['data' => ResourceArea::orderBy('name')->get()];
    }

    public function create()
    {
        return view('logro.resource.area.create');
    }

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

    public function show(ResourceArea $resourceArea)
    {
        //
    }

    public function edit(ResourceArea $area)
    {
        return view('logro.resource.area.edit')->with('area', $area);
    }

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
