<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SecretariatController extends Controller
{
    function __construct()
    {
        // $this->middleware('can:secretariat.index');
        $this->middleware('can:secretariat.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.secretariat.create');
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
            'name'          => ['required', 'string', 'max:191'],
            'last_names'    => ['required', 'string', 'max:191'],
            'email'         => ['required', 'email', 'max:191', Rule::unique('users')],
            'telephone'     => ['nullable', 'string', 'max:20']
        ]);

        $user = UserController::_create($request->name, $request->email, 4);

        if (!$user) {
            Notify::fail( __('Invalid email (:email)', ['email' => $request->email]) );
            return redirect()->back();
        }

        Secretariat::create([
            'id' => $user->id,
            'name' => $request->name,
            'last_names' => $request->last_names,
            'email' => $request->email,
            'telephone' => $request->telephone
        ]);

        Notify::success( __('Created secretariat user!') );
        self::tab();
        return redirect()->route('myinstitution');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Secretariat  $secretariat
     * @return \Illuminate\Http\Response
     */
    public function edit(Secretariat $secretariat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Secretariat  $secretariat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secretariat $secretariat)
    {
        //
    }

    private function tab()
    {
        session()->flash('tab', 'secretariat');
    }
}
