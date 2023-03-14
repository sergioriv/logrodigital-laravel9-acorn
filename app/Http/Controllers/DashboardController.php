<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\UserController;
use App\Models\CoordinationPermit;
use App\Models\Data\RoleUser;
use App\Models\OrientationPermit;
use App\Models\Student;
use App\Models\TeacherPermit;

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
            'alertsStudents' => UserAlertController::myAlerts()
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
            'pendingStudents' => $pendingStudents
        ]);
    }

    private function dashCoordination()
    {

        return view('dashboard.coordination', [
            'teacherPermits' => TeacherPermitController::pendingPermits(),
            'coordinationPermits' => CoordinationPermitController::pendingPermits(),
            'orientationPermits' => OrientationPermitController::pendingPermits(),
            'alertsStudents' => UserAlertController::myAlerts()
        ]);
    }
}
