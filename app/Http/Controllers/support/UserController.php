<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\ProviderUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:support.access');
    }

    public function index()
    {
        return view('support.users.index');
    }

    public function data()
    {
        return ['data' => User::with('roles')->orderBy('created_at', 'DESC')->get()];
    }

    /* public function create($name, $email, $role)
    {

        return $this->_create($name, $email, $role);

    } */

    public static function _create($name, $email, $role)
    {



        /* tratamiento para el username */
        $name = static::_username($name);

        $provider = null;
        if (NULL != $email)
        {
            /* convertir email in lower */
            $email = Str::lower($email);
            $provider = ProviderUser::provider_validate($email);
        }

        $user = User::create([
            'provider' => $provider,
            'name' => $name,
            'email' => $email,
        ])->assignRole($role);

        if ($role === 7)
            $user->forceFill(['email_verified_at' => now()])->save();
        elseif (NULL !== $email) {

            /* evita enviar más de un mail de verificación al mismo correo */
            $sendmail = true;
            $countEmail = User::where('email', $email)->count();
            if ( $countEmail == 1 )
            {
                // $sendmail = SmtpMail::sendEmailVerificationNotification($user);
            }

            /* si el mail de verificación rebota, el usuario es eliminado
             * se retorna false para la creación del usuario
             * */
            if (!$sendmail) {
                $user->delete();
                return false;
            }
        }


        event(new Registered($user));

        return $user;
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

        /* tratamiento para el username */
        $name = static::_username($name);

        $user->update([
            'name' => $name,
        ]);

        if ($email != null) {

            /* convertir email in lower */
            $email = Str::lower($email);

            $user->update([
                'email' => $email,
            ]);
        }

        if ($avatar != null) {
            $user->update([
                'avatar' => $avatar
            ]);
        }
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
        return User::find(Auth::user()->id)->getRoleNames()[0];
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
    private static function _username($name)
    {
        $name = Str::limit($name, 15, null);
        $name = Str::words($name, 2, null);
        return $name;
    }
}
