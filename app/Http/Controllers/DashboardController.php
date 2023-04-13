<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\UserController;
use App\Models\CoordinationPermit;
use App\Models\Data\RoleUser;
use App\Models\OrientationPermit;
use App\Models\Student;
use App\Models\TeacherPermit;
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

            default:
                return view('dashboard');
                break;
        }
    }

    private function dashTeacher()
    {
        return view('dashboard.teacher', [
            'alertsStudents' => UserAlertController::myAlerts(),
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

        return view('dashboard.orientation', [
            'alertsStudents' => UserAlertController::myAlerts(),
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

        return view('dashboard.coordination', [
            'teacherPermits' => TeacherPermitController::pendingPermits(),
            'coordinationPermits' => CoordinationPermitController::pendingPermits(),
            'orientationPermits' => OrientationPermitController::pendingPermits(),
            'alertsStudents' => UserAlertController::myAlerts(),
            'remitPending' => $remitPending,
            'typePermit' => TypePermitsTeacher::all()
        ]);
    }
}
