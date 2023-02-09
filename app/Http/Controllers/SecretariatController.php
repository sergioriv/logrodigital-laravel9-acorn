<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SecretariatController extends Controller
{
    function __construct()
    {
        $this->middleware('can:secretariat.create')->except('index');
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
            'email'         => ['required', 'email', 'max:191', Rule::unique('users', 'email')],
            'telephone'     => ['nullable', 'string', 'max:20']
        ]);

        DB::beginTransaction();

        $secreatariatCreate = UserController::__create($request->name, $request->email, 4);

        if (!$secreatariatCreate->getUser()) {

            DB::rollBack();
            Notify::fail( __('Something went wrong.') );
            return redirect()->back();
        }

        try {

            Secretariat::create([
                'id' => $secreatariatCreate->getUser()->id,
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

        if (!$secreatariatCreate->sendVerification()) {

            DB::rollBack();
            Notify::fail( __('Invalid email (:email)', ['email' => $request->email]) );
            return redirect()->back();
        }

        DB::commit();

        return view('logro.created', [
            'role' => 'secreatariat',
            'title' => __('Created secretariat user!'),
            'email' => $request->email,
            'password' => $secreatariatCreate->getUser()->temporalPassword,
            'redirect' => [
                'title' => __('Go back'),
                'action' => route('secreatariat.index')
            ]
        ]);
    }


    private function tab()
    {
        session()->flash('tab', 'secretariat');
    }
}
