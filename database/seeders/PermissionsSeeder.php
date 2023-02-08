<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_permissions')->delete();
        Permission::query()->delete();

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();


        $SUPPORT = Role::where('name', 'SUPPORT')->first();
        $SECRETARY = Role::where('name', 'SECRETARY')->first();
        $COORDINATOR = Role::where('name', 'COORDINATOR')->first();
        $ORIENTATION = Role::where('name', 'ORIENTATION')->first();
        $TEACHER = Role::where('name', 'TEACHER')->first();
        $STUDENT = Role::where('name', 'STUDENT')->first();

        $support = Permission::create(['name' => 'support.access']);
        $profile = Permission::create(['name' => 'profile.edit']);

        /* Mi institucion */
        $institucion = Permission::create(['name' => 'myinstitution']);
        $institucion_edit = Permission::create(['name' => 'myinstitution.edit']);

        /* Secretaria */
        $secretaria = Permission::create(['name' => 'secretariat.index']);
        $secretaria_create = Permission::create(['name' => 'secretariat.create']);

        /* Coordinacion */
        $coordinacion = Permission::create(['name' => 'coordination.index']);
        $coordinacion_create = Permission::create(['name' => 'coordination.create']);

        /* Orientacion */
        $orientacion = Permission::create(['name' => 'orientation.index']);
        $orientacion_create = Permission::create(['name' => 'orientation.create']);

        /* Docentes */
        $t_index = Permission::create(['name' => 'teachers.index']);
        $t_create = Permission::create(['name' => 'teachers.create']);
        $t_import = Permission::create(['name' => 'teachers.import']);

        /* Sedes */
        $hq_index = Permission::create(['name' => 'headquarters.index']);

        /* Jornadas */
        $st_index = Permission::create(['name' => 'studyTime.index']);
        $st_create = Permission::create(['name' => 'studyTime.create']);
        $st_edit = Permission::create(['name' => 'studyTime.edit']);
        $st_periods_edit = Permission::create(['name' => 'studyTime.periods.edit']);

        /* Años de estudio */
        $sy_index = Permission::create(['name' => 'studyYear.index']);
        $sy_create = Permission::create(['name' => 'studyYear.create']);
        $sy_subjects = Permission::create(['name' => 'studyYear.subjects']);

        /* Ciclos escolares */
        $y_select = Permission::create(['name' => 'schoolYear.select']);
        $y_create = Permission::create(['name' => 'schoolYear.create']);

        /* Grupos */
        $g_index = Permission::create(['name' => 'groups.index']);
        $g_create = Permission::create(['name' => 'groups.create']);
        $g_students = Permission::create(['name' => 'groups.students']);
        $g_students_matriculate = Permission::create(['name' => 'groups.students.matriculate']);
        $g_teachers = Permission::create(['name' => 'groups.teachers']);
        $g_teachers_edit = Permission::create(['name' => 'groups.teachers.edit']);

        /* Areas & Asignaturas */
        $sb_index = Permission::create(['name' => 'subjects.index']);
        $sb_edit = Permission::create(['name' => 'subjects.edit']);
        $sb_subjects_index = Permission::create(['name' => 'resourceSubjects.index']);
        $sb_subjects_edit = Permission::create(['name' => 'resourceSubjects.edit']);
        $sb_areas_index = Permission::create(['name' => 'resourceAreas.index']);
        $sb_area_edit = Permission::create(['name' => 'resourceAreas.edit']);

        /* Estudiantes */
        $s_index = Permission::create(['name' => 'students.index']);
        $s_import = Permission::create(['name' => 'students.import']);
        $s_create = Permission::create(['name' => 'students.create']);
        $s_matriculate = Permission::create(['name' => 'students.matriculate']);
        $s_info = Permission::create(['name' => 'students.info']);
        $s_view = Permission::create(['name' => 'students.view']);
        $s_documents_edit = Permission::create(['name' => 'students.documents.edit']);
        $s_documents_checked = Permission::create(['name' => 'students.documents.checked']);
        $s_psychosocial = Permission::create(['name' => 'students.psychosocial']);
        $s_delete = Permission::create(['name' => 'students.delete']);


        /* Asignacion de permisos a los roles */
        $SUPPORT->syncPermissions([
            $support,
            $profile,
            $institucion,
            $institucion_edit,
            $secretaria,
            $secretaria_create,
            $coordinacion,
            $coordinacion_create,
            $orientacion,
            $orientacion_create,
            $t_index,
            $t_create,
            $t_import,
            $hq_index,
            $st_index,
            $st_create,
            $st_edit,
            $st_periods_edit,
            $sy_index,
            $sy_create,
            $sy_subjects,
            $y_select,
            $y_create,
            $g_index,
            $g_create,
            $g_students,
            $g_students_matriculate,
            $g_teachers,
            $g_teachers_edit,
            $sb_index,
            $sb_edit,
            $sb_subjects_index,
            $sb_subjects_edit,
            $sb_areas_index,
            $sb_area_edit,
            $s_index,
            $s_import,
            $s_create,
            $s_matriculate,
            $s_info,
            $s_documents_edit,
            $s_documents_checked,
            $s_psychosocial,
            $s_delete,
        ]);

        $SECRETARY->syncPermissions([
            $profile,
            $institucion,
            $institucion_edit,
            $secretaria,
            $secretaria_create,
            $coordinacion,
            $coordinacion_create,
            $orientacion,
            $orientacion_create,
            $t_index,
            $t_create,
            $t_import,
            $hq_index,
            $st_index,
            $st_create,
            $st_edit,
            $st_periods_edit,
            $sy_index,
            $sy_create,
            $sy_subjects,
            $y_select,
            $y_create,
            $g_index,
            $g_create,
            $g_students,
            $g_students_matriculate,
            $g_teachers,
            $g_teachers_edit,
            $sb_index,
            $s_index,
            $s_create,
            $s_matriculate,
            $s_info,
            $s_documents_edit,
            $s_documents_checked,
            $s_delete
        ]);

        $COORDINATOR->syncPermissions([
            $profile,
            $institucion,
            $secretaria,
            $coordinacion,
            $orientacion,
            $t_index,
            $hq_index,
            $st_index,
            $st_create,
            $st_edit,
            $st_periods_edit,
            $sy_index,
            $sy_create,
            $sy_subjects,
            $g_index,
            $g_students,
            $g_teachers,
            $g_teachers_edit,
            $sb_index,
            $sb_edit,
            $sb_subjects_index,
            $sb_subjects_edit,
            $sb_areas_index,
            $sb_area_edit,
            $s_index,
            $s_view
        ]);

        $TEACHER->syncPermissions([
            $profile,
            $g_students,
            $s_view
        ]);

        $ORIENTATION->syncPermissions([
            $profile,
            $g_index,
            $g_students,
            $g_teachers,
            $s_index,
            $s_info,
            $s_documents_edit,
            $s_psychosocial
        ]);

        $STUDENT->syncPermissions([
            $profile,
            $s_info,
            $s_documents_edit
        ]);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    }
}
