<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
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
        return view('logro.resource.area.index', ['areas' =>
            ResourceArea::orderByDesc('specialty')->orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('logro.resource.area.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('resource_areas')],
            'specialty' => ['nullable', 'boolean']
        ]);

        ResourceArea::create([
            'name' => $request->name,
            'specialty' => $request->specialty ? TRUE : NULL
        ]);

        Notify::success( __('Area created!') );
        return redirect()->route('resourceArea.index');
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

        Notify::success( __('Area updated!') );
        return redirect()->route('resourceArea.index');
    }
}
