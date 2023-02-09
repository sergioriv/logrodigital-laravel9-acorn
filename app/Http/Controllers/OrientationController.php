<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Orientation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrientationController extends Controller
{
    function __construct()
    {
        $this->middleware('can:orientation.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logro.orientation.create');
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

        $orientatorCreate = UserController::__create($request->name, $request->email, 5);

        if (!$orientatorCreate->getUser()) {

            DB::rollBack();
            Notify::fail( __('Something went wrong.') );
            return redirect()->back();
        }

        try {

            Orientation::create([
                'id' => $orientatorCreate->getUser()->id,
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

        if (!$orientatorCreate->sendVerification()) {

            DB::rollBack();
            Notify::fail( __('Invalid email (:email)', ['email' => $request->email]) );
            return redirect()->back();
        }

        DB::commit();


        Notify::success( __('Created orientation user!') );
        self::tab();
        return redirect()->route('myinstitution');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orientation  $orientation
     * @return \Illuminate\Http\Response
     */
    public function edit(Orientation $orientation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orientation  $orientation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orientation $orientation)
    {
        //
    }

    private function tab()
    {
        session()->flash('tab', 'orientation');
    }
}
