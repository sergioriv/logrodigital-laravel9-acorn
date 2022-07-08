<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission::create([ 'name' => 'headquarters.index' ]);
        // Permission::create([ 'name' => 'profile.edit' ]);

        // $role_admin = Role::create([ 'name' => 'SUPPORT' ])->syncPermissions([$support_users, $support_roles]); // 1
        // Role::create([ 'name' => 'RECTOR' ]); // 2
        // Role::create([ 'name' => 'COORDINATOR' ]); // 3
        // Role::create([ 'name' => 'SECRETARY' ]); // 4
        // Role::create([ 'name' => 'PSYCHOLOGY']); // 5
        // Role::create([ 'name' => 'TEACHER' ]); // 6
        // Role::create([ 'name' => 'STUDENT' ]); // 7
        // Role::create([ 'name' => 'PARENT' ]); // 7

        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@logro.digital',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole($role_admin->id);

    }
}
