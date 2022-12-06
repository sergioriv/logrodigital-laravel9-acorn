<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\ResourceArea;
use App\Models\ResourceSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:resourceSubjects.index');
        $this->middleware('can:resourceSubjects.edit')->only('create', 'store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logro.resource.subject.index', [
            'subjects' => ResourceSubject::orderByDesc('specialty')->orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('logro.resource.subject.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descriptive_name' => ['required', 'string', Rule::unique('resource_subjects','name')],
            'public_name' => ['required', 'string'],
            'specialty' => ['nullable', 'boolean']
        ]);

        ResourceSubject::create([
            'name' => $request->descriptive_name,
            'public_name' => $request->public_name,
            'specialty' => $request->specialty ? TRUE : NULL
        ]);


        if ($request->specialty ? TRUE : NULL) {
            Notify::success(__('Specialty created!'));
        } else {
            Notify::success(__('Subject created!'));
        }

        return redirect()->route('resourceSubject.index');
    }
}
