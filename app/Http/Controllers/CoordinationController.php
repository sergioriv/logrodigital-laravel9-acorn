<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Coordination;
use App\Models\Data\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CoordinationController extends Controller
{
    function __construct()
    {
        $this->middleware('can:coordination.create')->except('index');
    }

    public function index()
    {
        $this->tab();
        return redirect()->route('myinstitution');
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
            Notify::fail(__('Something went wrong.'));
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
            Notify::fail(__('Invalid email (:email)', ['email' => $request->email]));
            return redirect()->back();
        }

        DB::commit();

        return view('logro.created', [
            'role' => 'coordination',
            'title' => __('Created coordination user!'),
            'email' => $request->email,
            'password' => $coordinationCreate->getUser()->temporalPassword,
            'buttons' => [
                [
                    'title' => __('Go back'),
                    'class' => 'btn-outline-alternate',
                    'action' => route('coordination.index'),
                ], [
                    'title' => __('Create new'),
                    'class' => 'btn-primary ms-2',
                    'action' => url()->previous(),
                ]
            ]
        ]);
    }

    public function profile(Coordination $coordination)
    {
        if (RoleUser::COORDINATION_ROL === UserController::role_auth()) {
            return view('logro.coordination.profile.edit', [
                'coordination' => $coordination,
            ]);
        }

        return redirect()->back()->withErrors(__('Unauthorized!'));
    }

    public function profile_update(Coordination $coordination, Request $request)
    {
        if (auth()->id() !== $coordination->id) {
            return redirect()->back()->withErrors(__('Unauthorized!'));
        }

        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'last_names' => ['required', 'string', 'max:191'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);


        DB::beginTransaction();

        try {
            $userName = UserController::_username($request->name . ' ' . $request->last_names);

            $coordination->update([
                'name' => $request->name,
                'last_names' => $request->last_names,
                'telephone' => $request->telephone
            ]);

            $coordination->user->update([
                'name' => $userName
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            Notify::fail(__('An error has occurred'));
            return back();
        }

        DB::commit();

        if ($request->hasFile('avatar')) {
            UserController::_update_avatar($request, $coordination->user);
        }

        Notify::success(__('Updated profile!'));
        return back();
    }

    public function edit(Coordination $coordination)
    {
        //
    }

    public function update(Request $request, Coordination $coordination)
    {
        //
    }

    private function tab()
    {
        session()->flash('tab', 'coordination');
    }
}
