<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\ProviderUser;
use App\Models\Coordination;
use App\Models\Data\RoleUser;
use App\Models\Orientation;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user = null)
    {
        $this->middleware('can:support.access');

        $this->user = $user;
    }

    public function index()
    {
        return view('support.users.index');
    }

    public function data()
    {
        return ['data' => User::with('roles')->orderBy('created_at', 'DESC')->get()];
    }

    public static function __create(string $name, string|null $email, int $role)
    {

        /* tratamiento para el username */
        $name = static::_username($name);

        $provider = null;
        if (NULL != $email) {
            /* convertir email in lower */
            $email = Str::lower($email);
            $provider = ProviderUser::provider_validate($email);
        }

        $password = Str::random(6);

        $user = new User;
        $user->forceFill([
            'provider' => $provider,
            'name' => $name,
            'email' => $email,
            'password' => $role === RoleUser::PARENT ? null : Hash::make($password),
            'change_password' => $role === RoleUser::PARENT ? 1 : 0
        ])->save();

        $user->assignRole($role);

        if ($role !== RoleUser::PARENT) {
            $user->temporalPassword = $password;
        }


        event(new Registered($user));

        return new static($user);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function sendVerification()
    {

        if (!is_null($this->user->email)) {

            /* evita enviar más de un mail de verificación al mismo correo */
            $sendmail = true;

            if (config('app.env') === 'production') {
                $countEmail = User::where('email', $this->user->email)->count();
                if ($countEmail == 1) {
                    $sendmail = SmtpMail::init()->sendEmailVerificationNotification($this->user);
                }
            }

            if ($sendmail)
                event(new Registered($this->user));

            /*
             * si el mail de verificación rebota, se retorna false y hará un rollback
             */
            return $sendmail;
        }

        return true;
    }

    public function show(User $user)
    {
        return redirect()->route('support.users.edit', $user);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('support.users.edit')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'role' => 'required'
        ]);

        $user->roles()->sync($request->role);

        Notify::success(__('Updated user!'));
        return redirect()->route('support.users.index');
    }

    public static function _update($user_id, $name, $email = NULL, $avatar = NULL)
    {
        $user = User::findOrFail($user_id);


        if ($email != null) {

            /* convertir email in lower */
            $email = Str::lower($email);

            if ($user->email !== $email) {

                $sendmail = true;
                if (config('app.env') === 'production') {
                    $sendmail = SmtpMail::init()->sendEmailVerificationNotification($user);

                    /* comprueba que el correo fué enviado y permite la actualización del correo */
                    if (!$sendmail) {
                        return false;
                    }
                }

                $user->forceFill([
                    'email' => $email,
                    'email_verified_at' => null,
                    'password' => null,
                    'remember_token' => null
                ])->save();
            }
        }

        /* tratamiento para el username */
        $name = static::_username($name);
        $user->update([
            'name' => $name,
        ]);


        return true;
    }

    public static function _update_avatar(Request $request, User $user)
    {
        $path = self::upload_avatar($request);

        if ($request->hasFile('avatar'))
            File::delete(public_path($user->avatar));

        $user->update([
            'avatar' => $path
        ]);
    }

    public static function upload_avatar($request)
    {
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatar', 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
            // return config('app.url') .'/'. config('filesystems.disks.public.url') .'/' . $path;
        } else return null;
    }

    public static function role_auth()
    {
        return Auth::user()->getRoleNames()[0];
    }

    public static function myModelIs(): string
    {
        return match (static::role_auth()) {
            'SUPPORT' => 'App\Models\User',
            'TEACHER' => 'App\Models\Teacher',
            'COORDINATOR' => 'App\Models\Coordination',
            'ORIENTATION' => 'App\Models\Orientation',
            'SECRETARY' => 'App\Models\Secretariat'
        };
    }

    public static function myName()
    {
        $name = null;
        $id = Auth::id();
        switch (static::role_auth()) {
            case RoleUser::TEACHER_ROL:
                $name = (Teacher::where('id', $id)->first())->getFullName();
                break;

            case RoleUser::ORIENTATION_ROL:
                $name = (Orientation::where('id', $id)->first())->getFullName();
                break;

            case RoleUser::COORDINATION_ROL:
                $name = (Coordination::where('id', $id)->first())->getFullName();
                break;
        }

        return $name;
    }

    public static function profile_update(Request $request, User $user)
    {

        $avatar = UserController::upload_avatar($request);

        if ($request->hasFile('avatar') && NULL !== $user->avatar)
            File::delete(public_path($user->avatar));

        $user->update([
            'avatar' => $avatar
        ]);
    }

    public static function delete_user($user_id)
    {
        $user = User::find($user_id);
        if (NULL !== $user) {

            if (NULL !== $user->avatar)
                File::delete(public_path($user->avatar));

            $user->delete();
        } else {
            return redirect()->back()->withErrors("custom", __("Unexpected Error"));
        }
    }

    /* Tratamiento de datos */
    public static function _username($name)
    {
        $name = Str::limit($name, 15, null);
        $name = Str::words($name, 2, null);
        return $name;
    }
}
