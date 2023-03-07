<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\UserController;
use App\Models\Data\RoleUser;
use App\Models\Student;
use App\Models\TeacherPermit;
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
        $Y = SchoolYearController::current_year();

        $pendingStudents = Student::where(function ($query) {
            $query->where('disability_id', '>', 1)
                    ->where(function ($student) {
                        $student->where('inclusive', 0)->orWhereNull('inclusive');
                    });
        })
        ->whereHas('groupYear', fn($gr) => $gr->whereHas('group', fn($g) => $g->where('school_year_id', $Y->id)))
        ->count();

        return view('dashboard.orientation', [
            'alertsStudents' => $this->myAlertStudents(),
            'pendingStudents' => $pendingStudents
        ]);
    }

    private function dashCoordination()
    {
        $teacherPermits = TeacherPermit::where('status', 0)->get()->groupBy(function ($permit) {
            return $permit->teacher_id;
        });

        return view('dashboard.coordination', [
            'teacherPermits' => $teacherPermits,
            'alertsStudents' => $this->myAlertStudents()
        ]);
    }

    protected function myAlertStudents()
    {
        return UserAlert::whereJsonContains('for_users', auth()->id())
            ->whereNot(fn ($not) => $not->whereJsonContains('checked', auth()->id()) )
            ->orderByDesc('priority')
            ->orderBy('created_at')
            ->with(['student' => fn($student) => $student->with(['group:id,name']) ])
            ->with('created_user')
            ->get()->groupBy(function ($alert) {
                return $alert->student_id;
            });

    }

}
