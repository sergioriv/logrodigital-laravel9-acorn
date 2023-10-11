<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\UserController;
use App\Models\Data\RoleUser;
use App\Models\Student;
use App\Models\TypePermitsTeacher;
use App\Models\UserAlert;

class DashboardController extends Controller
{

    public function show()
    {
        switch (UserController::role_auth()) {

            case RoleUser::TEACHER_ROL:
                return $this->dashTeacher();
                break;

            case RoleUser::COORDINATION_ROL:
                return $this->dashCoordination();
                break;

            case RoleUser::ORIENTATION_ROL:
                return $this->dashOrientation();
                break;

            case RoleUser::PARENT_ROL:
                return $this->dashParent();
                break;

            default:
                return view('dashboard');
                break;
        }
    }

    private function dashTeacher()
    {
        $alertPermits = \App\Models\AlertPermit::where('to_user_id', auth()->id())->get();
        return view('dashboard.teacher', [
            'alertsStudents' => UserAlertController::myAlerts(),
            'alertPermits' => $alertPermits,
            'typePermit' => TypePermitsTeacher::all()
        ]);
    }

    private function dashOrientation()
    {
        $Y = SchoolYearController::current_year();

        $pendingStudents = Student::where(function ($query) {
            $query->where('pre_inclusive', 1)
                    ->where(function ($student) {
                        $student->where('inclusive', 0)->orWhereNull('inclusive');
                    });
        })
        ->whereHas('groupYear', fn($gr) => $gr->whereHas('group', fn($g) => $g->where('school_year_id', $Y->id)))
        ->count();

        $alertPermits = \App\Models\AlertPermit::where('to_user_id', auth()->id())->get();

        return view('dashboard.orientation', [
            'alertsStudents' => UserAlertController::myAlerts(),
            'alertPermits' => $alertPermits,
            'pendingStudents' => $pendingStudents,
            'typePermit' => TypePermitsTeacher::all()
        ]);
    }

    private function dashCoordination()
    {
        $remitPending = UserAlert::where('user_approval_id', auth()->id())
            ->where('approval', 0)
            ->orderBy('created_at')
            ->with(['student' => fn($student) => $student->with(['group:id,name']) ])
            ->with('created_user')
            ->get()
            ->groupBy(function ($alert) {
                return $alert->student_id;
            });

        $alertPermits = \App\Models\AlertPermit::where('to_user_id', auth()->id())->get();

        return view('dashboard.coordination', [
            'teacherPermits' => TeacherPermitController::pendingPermits(),
            'coordinationPermits' => CoordinationPermitController::pendingPermits(),
            'orientationPermits' => OrientationPermitController::pendingPermits(),
            'alertsStudents' => UserAlertController::myAlerts(),
            'alertPermits' => $alertPermits,
            'remitPending' => $remitPending,
            'typePermit' => TypePermitsTeacher::all()
        ]);
    }

    private function dashParent()
    {
        $myStudents = \App\Models\Student::whereHas('personsCharge', fn($query) => $query->where('email', auth()->user()->email))
        ->select('id','first_name','second_name','first_last_name','second_last_name','group_id','enrolled')
        ->with(
            'group.headquarters:id,name',
            'group.studyTime:id,name',
            'group.studyYear:id,name')
        ->get();

        return view('dashboard.parent', [
            'myStudents' => $myStudents
        ]);
    }
}
