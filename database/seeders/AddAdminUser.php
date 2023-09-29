<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@logro.digital',
            'email_verified_at' => now(),
            'active' => TRUE,
        ]);

        $SUPPORT = Role::where('name', 'SUPPORT')->first();

        $user->assignRole($SUPPORT->id);
    }
}
