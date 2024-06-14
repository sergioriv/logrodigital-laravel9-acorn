<?php

namespace App\Http\Controllers;

use App\Exports\CoordinationsExport;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\City;
use App\Models\Coordination;
use App\Models\Data\MaritalStatus;
use App\Models\Data\RoleUser;
use App\Models\Data\TypeAdministrativeAct;
use App\Models\Data\TypeAppointment;
use App\Models\TypePermitsTeacher;
use App\Rules\MaritalStatusRule;
use App\Rules\TypeAdminActRule;
use App\Rules\TypeAppointmentRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CoordinationController extends Controller
{
    function __construct()
    {
        $this->middleware('can:coordination.create')->only('create', 'store');
        $this->middleware('can:coordination.index')->only('show','export');
        $this->middleware('hasroles:COORDINATOR')->only('profile', 'profile_update');
        $this->middleware('hasroles:SUPPORT,SECRETARY')->only('mutateUser');
    }

    public function index()
    {
        $this->tab();
        return redirect()->route('myinstitution');
    }

    public function create()
    {
        return view('logro.coordination.create', [
            'typesAppointment' => TypeAppointment::data(),
            'typesAdministrativeAct' => TypeAdministrativeAct::data()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'names' => ['required', 'string', 'max:191'],
            'lastNames' => ['required', 'string', 'max:191'],
            'institutional_email' => ['required', 'email', Rule::unique('users', 'email')],
            'date_entry' => ['required', 'date', 'date_format:Y-m-d'],
            'type_appointment' => ['required', new TypeAppointmentRule],
            'type_admin_act' => ['required', new TypeAdminActRule],
            'appointment_number' => ['nullable', 'max:20'],
            'date_appointment' => ['nullable', 'date', 'date_format:Y-m-d'],
            'file_appointment' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'file_possession_certificate' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['nullable', 'date', 'date_format:Y-m-d'],
            'file_transfer_resolution' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        DB::beginTransaction();

        $coordinatorName = $request->names . ' ' . $request->lastNames;
        $coordinationCreate = UserController::__create($coordinatorName, $request->institutional_email, RoleUser::COORDINATOR);

        if (!$coordinationCreate->getUser()) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        $coordinatorUuid = Str::uuid()->toString();

        try {

            $coordination = Coordination::create([
                'id' => $coordinationCreate->getUser()->id,
                'uuid' => $coordinatorUuid,
                'names' => $request->names,
                'last_names' => $request->lastNames,
                'institutional_email' => $request->institutional_email,
                'date_entry' => $request->date_entry,

                'type_appointment' => $request->type_appointment,
                'type_admin_act' => $request->type_admin_act,
                'appointment_number' => $request->appointment_number,
                'date_appointment' => $request->date_appointment,
                'possession_certificate' => $request->possession_certificate,
                'date_possession_certificate' => $request->date_possession_certificate,
                'transfer_resolution' => $request->transfer_resolution,
                'date_transfer_resolution' => $request->date_transfer_resolutionm,

                'active' => TRUE
            ]);

            if ($request->hasFile('file_appointment')) {
                $coordination->update([
                    'file_appointment' => $this->uploadFile($request, $coordination, 'file_appointment')
                ]);
            }
            if ($request->hasFile('file_possession_certificate')) {
                $coordination->update([
                    'file_possession_certificate' => $this->uploadFile($request, $coordination, 'file_possession_certificate')
                ]);
            }
            if ($request->hasFile('file_transfer_resolution')) {
                $coordination->update([
                    'file_transfer_resolution' => $this->uploadFile($request, $coordination, 'file_transfer_resolution')
                ]);
            }


        } catch (\Throwable $th) {

            $this->deleteDirectory($coordinatorUuid);

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        if (!$coordinationCreate->sendVerification()) {

            $this->deleteDirectory($coordinatorUuid);

            DB::rollBack();
            Notify::fail(__('Invalid email (:email)', ['email' => $request->email]));
            return redirect()->back();
        }

        DB::commit();


        return view('logro.created', [
            'role' => 'coordination',
            'title' => __('Created coordination user!'),
            'email' => $request->institutional_email,
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

    public function show(Coordination $coordination)
    {
        return view('logro.coordination.show')->with([
            'coordination' => $coordination
        ]);
    }

    public function profile(Coordination $coordination)
    {
        if (RoleUser::COORDINATION_ROL === UserController::role_auth()) {
            return view('logro.teacher.profile.edit', [
                'teacher' => $coordination,
                'cities' => City::with('department')->get(),
                'maritalStatus' => MaritalStatus::data(),
                'typePermit' => TypePermitsTeacher::all()
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
            'names' => ['required', 'string', 'max:191'],
            'lastNames' => ['required', 'string', 'max:191'],
            'document' => ['nullable', 'max:20', Rule::unique('user_coordination', 'document')->ignore($coordination->id)],
            'expedition_city' => ['nullable', Rule::exists('cities', 'id')],
            'birth_city' => ['nullable', Rule::exists('cities', 'id')],
            'birthdate' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today'],
            'residence_city' => ['nullable', Rule::exists('cities', 'id')],
            'address' => ['nullable', 'max:100'],
            'telephone' => ['nullable', 'max:30'],
            'cellphone' => ['nullable', 'max:30'],
            'institutional_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($coordination->id)],
            'marital_status' => ['nullable', new MaritalStatusRule],

            'appointment_number' => ['nullable', 'max:20'],
            'date_appointment' => ['nullable', 'date', 'date_format:Y-m-d'],
            'file_appointment' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'file_possession_certificate' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['nullable', 'date', 'date_format:Y-m-d'],
            'file_transfer_resolution' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],

            'signature' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);


        DB::beginTransaction();

        $coordinatorName = $request->names . ' ' . $request->lastNames;
        $user = UserController::_update($coordination->id, $coordinatorName, $request->institutional_email);

        if (!$user) {
            Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
            return redirect()->back();
        }

        try {
            $coordination->update([
                'names' => $request->names,
                'last_names' => $request->lastNames,
                'institutional_email' => $request->institutional_email,

                'document' => $request->document,
                'expedition_city' => $request->expedition_city,
                'birth_city' => $request->birth_city,
                'birthdate' => $request->birthdate,
                'residence_city' => $request->residence_city,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'cellphone' => $request->cellphone,
                'marital_status' => $request->marital_status,

                'appointment_number' => $request->appointment_number,
                'date_appointment' => $request->date_appointment,
                'possession_certificate' => $request->possession_certificate,
                'date_possession_certificate' => $request->date_possession_certificate,
                'transfer_resolution' => $request->transfer_resolution,
                'date_transfer_resolution' => $request->date_transfer_resolution
            ]);

            if ($request->hasFile('file_appointment')) {
                $coordination->update([
                    'file_appointment' => $this->uploadFile($request, $coordination, 'file_appointment')
                ]);
            }
            if ($request->hasFile('file_possession_certificate')) {
                $coordination->update([
                    'file_possession_certificate' => $this->uploadFile($request, $coordination, 'file_possession_certificate')
                ]);
            }
            if ($request->hasFile('file_transfer_resolution')) {
                $coordination->update([
                    'file_transfer_resolution' => $this->uploadFile($request, $coordination, 'file_transfer_resolution')
                ]);
            }
            if ($request->hasFile('signature')) {
                $coordination->update([
                    'signature' => $this->uploadFile($request, $coordination, 'signature')
                ]);
            }


        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('An error has occurred'));
            return back();
        }

        if ($request->institutional_email !== $coordination->institutional_email) {

            if (!$coordination->sendVerification()) {

                DB::rollBack();
                Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
                return redirect()->back();
            }
        }

        DB::commit();

        Notify::success(__('Updated profile!'));
        return redirect()->route('user.profile.edit');
    }

    public function export()
    {
        return Excel::download(new CoordinationsExport, __('coordinations') . '.xlsx');
    }


    private function tab()
    {
        session()->flash('tab', 'coordination');
    }

    protected function uploadFile($request, $coordinator, $file)
    {
        if ($request->hasFile($file)) {

            if (!is_null($coordinator->$file)) {
                File::delete(public_path($coordinator->$file));
            }

            $path = $request->file($file)->store('coordinators/' . $coordinator->uuid, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }

    protected function deleteDirectory($uuid)
    {
        if (is_dir(public_path('app/coordinators/' . $uuid)))
            File::deleteDirectory(public_path('app/coordinators/' . $uuid));
    }

    public function permitTab(Coordination $coordination)
    {
        session()->flash('tab', 'permits');
        return redirect()->route('coordination.show', $coordination);
    }


    /* Has Roles: SUPPORT, SECRETARY */
    public function mutateUser(Coordination $coordination)
    {
        Auth::login(\App\Models\User::find($coordination->id));
        return redirect()->route('dashboard');
    }
}
