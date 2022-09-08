<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\ResourceSubject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:resourceSubjects.index');
        $this->middleware('can:resourceSubjects.edit')->only('create','store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logro.resource.subject.index');
    }

    public function data()
    {
        return ['data' => ResourceSubject::orderBy('name')->get()];
    }

    public function create()
    {
        return view('logro.resource.subject.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('resource_subjects')],
        ]);

        ResourceSubject::create([
            'name' => $request->name
        ]);

        Notify::success( __('Subject created!') );
        return redirect()->route('resourceSubject.index');
    }

}
