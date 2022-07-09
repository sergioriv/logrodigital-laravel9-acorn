<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:support.users');
    }

    public function index()
    {
        return view('support.users.index');
    }

    public function data()
    {
        return ['data' => User::with('roles')->orderBy('created_at','DESC')->get()];
    }

    /* public function create($name, $email, $role)
    {

        return $this->_create($name, $email, $role);

    } */

    public static function _create($name, $email, $role)
    {

        $provider = ProviderUser::provider_validate($email);

        $user = User::create([
            'provider' => $provider,
            'name' => $name,
            'email' => $email,
        ])->assignRole($role);

        if ( $role === 7 )
            $user->forceFill(['email_verified_at' => now()])->save();
        else
            $user->sendEmailVerificationNotification();


        event(new Registered($user));

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect()->route('support.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('support.users.edit')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $request->validate([
            'role' => 'required'
        ]);

        $user->roles()->sync($request->role);

        return redirect()->route('support.users.index')->with([
            'notify' => 'success',
            'title' => 'Updated user!',
        ]);
    }

    public static function _update($user_id, $name, $email=NULL, $avatar=NULL)
    {
        $user = User::findOrFail($user_id);
        $user->update([
            'name' => $name,
        ]);

        if($email != null){
            $user->update([
                'email' => $email,
            ]);
        }

        if($avatar != null){
            $user->update([
                'avatar' => $avatar
            ]);
        }
    }

    public static function upload_avatar($request)
    {
        if ( $request->hasFile('avatar') )
        {
            $path = $request->file('avatar')->store('avatar','public');
            return config('filesystems.disks.public.url') .'/' . $path;
            // return config('app.url') .'/'. config('filesystems.disks.public.url') .'/' . $path;
        }
        else return null;
    }

    public static function role_auth()
    {
        return User::find(Auth::user()->id)->getRoleNames()[0];
    }

    public static function profile_update(Request $request, User $support)
    {

        $avatar = UserController::upload_avatar($request);

        if ( $request->hasFile('avatar') )
            File::delete(public_path($support->avatar));

        $support->update([
            'avatar' => $avatar
        ]);

    }
}
