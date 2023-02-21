<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AccessSupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:SUPPORT');
        $this->middleware('can:support.access');
    }


    public function support($action, $id = null)
    {
        switch ($action) {

            case 'mutate':
                self::mutate($id);
                break;

            case 'add-voting':
                self::addVoting();
                break;

            case 'permissions-reset':
                self::resetPermissions();
                break;

            case 'myroles':
                self::myRolesIS();
                break;

        }
    }

    protected function mutate($id)
    {
        Auth::login(User::find($id));
        return redirect()->route('dashboard');
    }

    protected function addVoting()
    {
        if ( ! Role::where('name', 'VOTING_COORDINATOR')->first()) {

            Role::create([
                'name' => 'VOTING_COORDINATOR'
            ]);
        }

        auth()->user()->assignRole(9);

        dd('Rol VOTING_COORDINATOR creado');
    }

    protected function resetPermissions()
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        dd('permisos reiniciados');
    }

    protected function myRolesIs()
    {
        dd( auth()->user()->getRoleNames() );
    }
}
