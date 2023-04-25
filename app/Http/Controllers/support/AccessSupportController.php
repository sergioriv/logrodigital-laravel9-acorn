<?php

namespace App\Http\Controllers\support;

use App\Exports\TeachersCountData;
use App\Exports\TeachersWithNoAttendance;
use App\Http\Controllers\Controller;
use App\Models\AttendanceStudent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

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

            case 'fix-tutor':
                self::fixTutor();
                break;

            case 'myroles':
                self::myRolesIS();
                break;

            case 'attendance-teacher':
                return self::asistenciaDocentes();
                break;

            case 'data-teacher':
                return self::datosDocentes();
                break;

            case 'test-attendance':
                return self::testAttendance();
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
        if (!Role::where('name', 'VOTING_COORDINATOR')->first()) {

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
        dd(auth()->user()->getRoleNames());
    }

    protected function fixTutor()
    {
        $students = \App\Models\Student::whereNotNull('person_charge')->get();

        foreach ($students as $student) {

            $personCharge = \App\Models\PersonCharge::select('id')->where('student_id', $student->id)->where('kinship_id', $student->person_charge)->first();

            $student->update([
                'person_charge' => $personCharge->id
            ]);
        }

        dd($students->pluck('person_charge', 'id'));
    }

    protected function asistenciaDocentes()
    {

        $title = 'Docentes sin toma de asistencia';

        $teachers = \App\Models\Teacher::whereNot(function ($not) {

            $not->whereHas(
                'teacherSubjectGroups',
                fn ($tsg) => $tsg->whereHas('attendances')
            );
        })
            ->orderBy('last_names')
            ->orderBy('names')
            ->get();


        return Excel::download(new TeachersWithNoAttendance($title, $teachers), $title . '.xlsx');
    }

    protected function datosDocentes()
    {

        $title = 'Cantidad documentos de Docentes';

        $teachers = \App\Models\Teacher::withCount('hierarchies', 'degrees', 'employments')
            ->orderBy('last_names')
            ->orderBy('names')
            ->get();

        return Excel::download(new TeachersCountData($title, $teachers), $title . '.xlsx');
    }
}
