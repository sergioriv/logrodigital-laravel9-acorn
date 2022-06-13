<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
Use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

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

    public static function _create($name, $email, $role, $avatar)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'avatar' => $avatar,
        ])->assignRole($role);

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

    public static function _update($user_id, $name, $email, $avatar)
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
            $path = $request->file('avatar')->store('public/avatar');
            return Storage::url($path);
        }
        else return null;
    }

    public static function role_auth()
    {
        return User::find(Auth::user()->id)->getRoleNames()[0];
    }




    /**
     * adicionales, por borrar
     */

    public function insert_roles ()
    {

        for ($i=2; $i <= User::count(); $i++) {
            $user = User::find($i);
            $user->roles()->sync(2);
        }
        return "Usuarios creados " . User::count();
    }

    public function destroy_users ()
    {
        $eliminados = [];
        for ($i=2; $i <= User::count(); $i++) {
            array_push($eliminados, $i);
        }

        User::destroy($eliminados);
    }


}
