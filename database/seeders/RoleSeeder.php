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
        $support = Permission::create([ 'name' => 'support.access', ]);
        $profile = Permission::create([ 'name' => 'profile.edit', ]);

        $hq_index = Permission::create([ 'name' => 'headquarters.index', ]);

        $s_index = Permission::create([ 'name' => 'students.index', ]);
        $s_import = Permission::create([ 'name' => 'students.import', ]);
        $s_create = Permission::create([ 'name' => 'students.create', ]);
        $s_matriculate = Permission::create([ 'name' => 'students.matriculate', ]);
        $s_info = Permission::create([ 'name' => 'students.info', ]);
        $s_documents_edit = Permission::create([ 'name' => 'students.documents.edit', ]);
        $s_documents_checked = Permission::create([ 'name' => 'students.documents.checked', ]);
        $s_psychosocial = Permission::create([ 'name' => 'students.psychosocial', ]);

        $sb_index = Permission::create([ 'name' => 'subjects.index', ]);
        $sb_edit = Permission::create([ 'name' => 'subjects.edit', ]);
        $sb_subjects_index = Permission::create([ 'name' => 'resourceSubjects.index', ]);
        $sb_subjects_edit = Permission::create([ 'name' => 'resourceSubjects.edit', ]);
        $sb_areas_index = Permission::create([ 'name' => 'resourceAreas.index', ]);
        $sb_area_edit = Permission::create([ 'name' => 'resourceAreas.edit', ]);

        $t_index = Permission::create([ 'name' => 'teachers.index', ]);
        $t_create = Permission::create([ 'name' => 'teachers.create', ]);
        $t_edit = Permission::create([ 'name' => 'teachers.edit', ]);
        $t_import = Permission::create([ 'name' => 'teachers.import', ]);

        $g_index = Permission::create([ 'name' => 'groups.index', ]);
        $g_create = Permission::create([ 'name' => 'groups.create', ]);
        $g_students = Permission::create([ 'name' => 'groups.students', ]);
        $g_students_matriculate = Permission::create([ 'name' => 'groups.students.matriculate', ]);
        $g_teachers = Permission::create([ 'name' => 'groups.teachers', ]);
        $g_teachers_edit = Permission::create([ 'name' => 'groups.teachers.edit', ]);

        $st_index = Permission::create([ 'name' => 'studyTime.index', ]);
        $st_create = Permission::create([ 'name' => 'studyTime.create', ]);
        $st_edit = Permission::create([ 'name' => 'studyTime.edit', ]);
        $st_periods_edit = Permission::create([ 'name' => 'studyTime.periods.edit', ]);

        $sy_index = Permission::create([ 'name' => 'studyYear.index', ]);
        $sy_create = Permission::create([ 'name' => 'studyYear.create', ]);
        $sy_subjects = Permission::create([ 'name' => 'studyYear.subjects', ]);

        $y_select = Permission::create([ 'name' => 'schoolYear.select', ]);
        $y_create = Permission::create([ 'name' => 'schoolYear.create', ]);

        Role::find(1)->syncPermissions([
            $support, $profile, $hq_index,
            $s_index, $s_import, $s_create, $s_matriculate, $s_info, $s_documents_edit, $s_documents_checked, $s_psychosocial,
            $sb_index, $sb_edit, $sb_subjects_index, $sb_subjects_edit, $sb_areas_index, $sb_area_edit,
            $t_index, $t_create, $t_edit, $t_import,
            $g_index, $g_create, $g_students, $g_students_matriculate, $g_teachers, $g_teachers_edit,
            $st_index, $st_create, $st_edit, $st_periods_edit,
            $sy_index, $sy_create, $sy_subjects,
            $y_select, $y_create
        ]); // 1
        // Role::create([ 'name' => 'RECTOR' ]); // 2
        // Role::create([ 'name' => 'COORDINATOR' ]); // 3
        // Role::create([ 'name' => 'SECRETARY' ]); // 4
        // Role::create([ 'name' => 'ORIENTATION']); // 5
        // Role::create([ 'name' => 'TEACHER' ]); // 6
        Role::find(7)->syncPermissions([
            $profile, $s_info, $s_documents_edit
        ]); // 7
        // Role::create([ 'name' => 'PARENT' ]); // 8

        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@logro.digital',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ])->assignRole($role_admin->id);

    }
}
