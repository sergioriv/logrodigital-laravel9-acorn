<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Coordination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CoordinationController extends Controller
{
    function __construct()
    {
        $this->middleware('can:coordination.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.coordination.create');
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
            'email'         => ['required', 'email', 'max:191', Rule::unique('users', 'email')],
            'telephone'     => ['nullable', 'string', 'max:30']
        ]);

        DB::beginTransaction();

        $coordinationCreate = UserController::__create($request->name, $request->email, 3);

        if (!$coordinationCreate->getUser()) {

            DB::rollBack();
            Notify::fail( __('Something went wrong.') );
            return redirect()->back();
        }

        try {

            Coordination::create([
                'id' => $coordinationCreate->getUser()->id,
                'name' => $request->name,
                'last_names' => $request->last_names,
                'email' => $request->email,
                'telephone' => $request->telephone
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        if (!$coordinationCreate->sendVerification()) {

            DB::rollBack();
            Notify::fail( __('Invalid email (:email)', ['email' => $request->email]) );
            return redirect()->back();
        }

        DB::commit();

        return view('logro.created', [
            'role' => 'coordination',
            'title' => __('Created coordination user!'),
            'email' => $request->email,
            'password' => $coordinationCreate->getUser()->temporalPassword,
            'redirect' => [
                'title' => __('Go back'),
                'action' => route('myinstitution')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function edit(Coordination $coordination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coordination  $coordination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coordination $coordination)
    {
        //
    }

    private function tab()
    {
        session()->flash('tab', 'coordination');
    }
}
