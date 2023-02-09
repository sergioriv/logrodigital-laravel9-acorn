<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\support\UserController;
use App\Models\Data\RoleUser;
use App\Models\Student;
use App\Models\UserAlert;
use Illuminate\Support\Facades\Auth;

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
        $alerts = UserAlert::where('for_user', Auth::id())
                ->whereNull('checked')
                ->orderByDesc('priority')
                ->orderBy('created_at')
                ->get();

        return view('dashboard.teacher', ['alerts' => $alerts]);
    }

    private function dashOrientation()
    {
        $alerts = UserAlert::where('for_user', Auth::id())
                ->whereNull('checked')
                ->orderByDesc('priority')
                ->orderBy('created_at')
                ->get();

        $pendingStudents = Student::where(function ($query) {
            $query->where('disability_id', '>', 1)
                    ->where(function ($student) {
                        $student->where('inclusive', 0)->orWhereNull('inclusive');
                    });
        })->count();

        return view('dashboard.orientation', [
            'alerts' => $alerts,
            'pendingStudents' => $pendingStudents
        ]);
    }

    private function dashCoordination()
    {
        $alerts = UserAlert::where('for_user', Auth::id())
                ->whereNull('checked')
                ->orderByDesc('priority')
                ->orderBy('created_at')
                ->get();

        return view('dashboard.coordination', ['alerts' => $alerts]);
    }

}
