<?php

namespace App\Http\Controllers;

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

        return redirect()->route('resourceSubject.index')->with(
            ['notify' => 'success', 'title' => __('Subject created!')],
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResourceSubject  $resourceSubject
     * @return \Illuminate\Http\Response
     */
    /* public function show(ResourceSubject $resourceSubject)
    {
        //
    } */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResourceSubject  $resourceSubject
     * @return \Illuminate\Http\Response
     */
    /* public function edit(ResourceSubject $subject)
    {
        return view('logro.resource.subject.edit')->with('subject', $subject);
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResourceSubject  $resourceSubject
     * @return \Illuminate\Http\Response
     */
    /* public function update(Request $request, ResourceSubject $subject)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('resource_subjects')->ignore($subject->id)]
        ]);

        $subject->update([
            'name' => $request->name
        ]);

        return redirect()->route('resourceSubject.index')->with(
            ['notify' => 'success', 'title' => __('Subject updated!')],
        );
    } */
}
