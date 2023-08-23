<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\City;
use App\Models\Data\MaritalStatus;
use App\Models\Data\RoleUser;
use App\Models\Data\TypeAdministrativeAct;
use App\Models\Data\TypeAppointment;
use App\Models\Orientation;
use App\Models\TypePermitsTeacher;
use App\Rules\MaritalStatusRule;
use App\Rules\TypeAdminActRule;
use App\Rules\TypeAppointmentRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class OrientationController extends Controller
{
    function __construct()
    {
        $this->middleware('can:orientation.create')->only('create', 'store');
        $this->middleware('can:teachers.index')->only('show');
        $this->middleware('hasroles:ORIENTATION')->only('profile', 'profile_update');
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
        return view('logro.orientation.create', [
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
            'date_appointment' => ['nullable', 'required_with:appointment_number', 'date', 'date_format:Y-m-d'],
            'file_appointment' => ['nullable', 'required_with:appointment_number', 'file', 'mimes:pdf', 'max:2048'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['nullable', 'required_with:possession_certificate', 'date', 'date_format:Y-m-d'],
            'file_possession_certificate' => ['nullable', 'required_with:possession_certificate', 'file', 'mimes:pdf', 'max:2048'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['nullable', 'required_with:transfer_resolution', 'date', 'date_format:Y-m-d'],
            'file_transfer_resolution' => ['nullable', 'required_with:transfer_resolution', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        DB::beginTransaction();

        $orientatorName = $request->names . ' ' . $request->lastNames;
        $orientationCreate = UserController::__create($orientatorName, $request->institutional_email, RoleUser::ORIENTATION);

        if (!$orientationCreate->getUser()) {

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        $orientatorUuid = Str::uuid()->toString();

        try {

            $orientation = Orientation::create([
                'id' => $orientationCreate->getUser()->id,
                'uuid' => $orientatorUuid,
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
                $orientation->update([
                    'file_appointment' => $this->uploadFile($request, $orientation, 'file_appointment')
                ]);
            }
            if ($request->hasFile('file_possession_certificate')) {
                $orientation->update([
                    'file_possession_certificate' => $this->uploadFile($request, $orientation, 'file_possession_certificate')
                ]);
            }
            if ($request->hasFile('file_transfer_resolution')) {
                $orientation->update([
                    'file_transfer_resolution' => $this->uploadFile($request, $orientation, 'file_transfer_resolution')
                ]);
            }


        } catch (\Throwable $th) {

            $this->deleteDirectory($orientatorUuid);

            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return redirect()->back();
        }

        if (!$orientationCreate->sendVerification()) {

            $this->deleteDirectory($orientatorUuid);

            DB::rollBack();
            Notify::fail(__('Invalid email (:email)', ['email' => $request->email]));
            return redirect()->back();
        }

        DB::commit();


        return view('logro.created', [
            'role' => 'orientation',
            'title' => __('Created orientation user!'),
            'email' => $request->institutional_email,
            'password' => $orientationCreate->getUser()->temporalPassword,
            'buttons' => [
                [
                    'title' => __('Go back'),
                    'class' => 'btn-outline-alternate',
                    'action' => route('orientation.index'),
                ], [
                    'title' => __('Create new'),
                    'class' => 'btn-primary ms-2',
                    'action' => url()->previous(),
                ]
            ]
        ]);
    }

    public function show(Orientation $orientation)
    {
        return view('logro.orientation.show')->with([
            'orientation' => $orientation
        ]);
    }

    public function profile(Orientation $orientation)
    {
        if (RoleUser::ORIENTATION_ROL === UserController::role_auth()) {
            return view('logro.teacher.profile.edit', [
                'teacher' => $orientation,
                'cities' => City::with('department')->get(),
                'maritalStatus' => MaritalStatus::data(),
                'typePermit' => TypePermitsTeacher::all()
            ]);
        }

        return redirect()->back()->withErrors(__('Unauthorized!'));
    }

    public function profile_update(Orientation $orientation, Request $request)
    {
        if (auth()->id() !== $orientation->id) {
            return redirect()->back()->withErrors(__('Unauthorized!'));
        }

        $request->validate([
            'names' => ['required', 'string', 'max:191'],
            'lastNames' => ['required', 'string', 'max:191'],
            'document' => ['nullable', 'max:20', Rule::unique('user_coordination', 'document')->ignore($orientation->id)],
            'expedition_city' => ['nullable', Rule::exists('cities', 'id')],
            'birth_city' => ['nullable', Rule::exists('cities', 'id')],
            'birthdate' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today'],
            'residence_city' => ['nullable', Rule::exists('cities', 'id')],
            'address' => ['nullable', 'max:100'],
            'telephone' => ['nullable', 'max:30'],
            'cellphone' => ['nullable', 'max:30'],
            'institutional_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($orientation->id)],
            'marital_status' => ['nullable', new MaritalStatusRule],

            'appointment_number' => ['nullable', 'max:20'],
            'date_appointment' => ['nullable', 'required_with:appointment_number', 'date', 'date_format:Y-m-d'],
            'file_appointment' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'possession_certificate' => ['nullable', 'max:20'],
            'date_possession_certificate' => ['nullable', 'required_with:possession_certificate', 'date', 'date_format:Y-m-d'],
            'file_possession_certificate' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transfer_resolution' => ['nullable', 'max:20'],
            'date_transfer_resolution' => ['nullable', 'required_with:transfer_resolution', 'date', 'date_format:Y-m-d'],
            'file_transfer_resolution' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],

            'signature' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);


        DB::beginTransaction();

        $orientatorName = $request->names . ' ' . $request->lastNames;
        $user = UserController::_update($orientation->id, $orientatorName, $request->institutional_email);

        if (!$user) {
            Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
            return redirect()->back();
        }

        try {
            $orientation->update([
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
                $orientation->update([
                    'file_appointment' => $this->uploadFile($request, $orientation, 'file_appointment')
                ]);
            }
            if ($request->hasFile('file_possession_certificate')) {
                $orientation->update([
                    'file_possession_certificate' => $this->uploadFile($request, $orientation, 'file_possession_certificate')
                ]);
            }
            if ($request->hasFile('file_transfer_resolution')) {
                $orientation->update([
                    'file_transfer_resolution' => $this->uploadFile($request, $orientation, 'file_transfer_resolution')
                ]);
            }
            if ($request->hasFile('signature')) {
                $orientation->update([
                    'signature' => $this->uploadFile($request, $orientation, 'signature')
                ]);
            }


        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('An error has occurred'));
            return back();
        }

        if ($request->institutional_email !== $orientation->institutional_email) {

            if (!$orientation->sendVerification()) {

                DB::rollBack();
                Notify::fail(__('Invalid email (:email)', ['email' => $request->institutional_email]));
                return redirect()->back();
            }
        }

        DB::commit();

        Notify::success(__('Updated profile!'));
        return redirect()->route('user.profile.edit');
    }

    private function tab()
    {
        session()->flash('tab', 'orientation');
    }

    protected function uploadFile($request, $coordinator, $file)
    {
        if ($request->hasFile($file)) {

            if (!is_null($coordinator->$file)) {
                File::delete(public_path($coordinator->$file));
            }

            $path = $request->file($file)->store('orientators/' . $coordinator->uuid, 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }

    protected function deleteDirectory($uuid)
    {
        if (is_dir(public_path('app/orientators/' . $uuid)))
            File::deleteDirectory(public_path('app/orientators/' . $uuid));
    }

    public function permitTab(Orientation $orientation)
    {
        session()->flash('tab', 'permits');
        return redirect()->route('orientation.show', $orientation);
    }
}
