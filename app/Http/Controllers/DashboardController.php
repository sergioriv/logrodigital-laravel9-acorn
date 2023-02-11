<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\UserController;
use App\Models\Data\RoleUser;
use App\Models\Student;
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
            'alertsStudents' => $this->myAlertStudents()
        ]);
    }

    private function dashOrientation()
    {
        $pendingStudents = Student::where(function ($query) {
            $query->where('disability_id', '>', 1)
                    ->where(function ($student) {
                        $student->where('inclusive', 0)->orWhereNull('inclusive');
                    });
        })->count();

        return view('dashboard.orientation', [
            'alertsStudents' => $this->myAlertStudents(),
            'pendingStudents' => $pendingStudents
        ]);
    }

    private function dashCoordination()
    {
        return view('dashboard.coordination', [
            'alertsStudents' => $this->myAlertStudents()
        ]);
    }

    protected function myAlertStudents()
    {
        return UserAlert::where('for_user', auth()->id())
            ->whereNull('checked')
            ->orderByDesc('priority')
            ->orderBy('created_at')
            ->with('student')
            ->get()->groupBy(function ($alert) {
                return $alert->student_id;
            });
    }

}
