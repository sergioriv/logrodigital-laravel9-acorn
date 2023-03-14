<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoordinationPermitController;
use App\Http\Controllers\OrientationPermitController;
use App\Http\Controllers\support\UserController;
use App\Http\Controllers\TeacherPermitController;
use App\Http\Controllers\UserAlertController;
use App\Models\Data\RoleUser;

class UserAlertsController extends Controller
{
    public function alertsCount()
    {
        $alerts = match(UserController::role_auth()){
            RoleUser::ORIENTATION_ROL => $this->forOrientation(),
            RoleUser::COORDINATION_ROL => $this->forCoordination(),
            RoleUser::TEACHER_ROL => $this->forTeachers(),
            default => null
        };


        return response()->json($alerts);
    }

    private function forTeachers()
    {
        return UserAlertController::myAlerts()->count();
    }

    private function forOrientation()
    {
        return UserAlertController::myAlerts()->count();
    }

    private function forCoordination()
    {
        $count = 0;

        /*
         * Alertas generadas para el usuario
         */
        $count += UserAlertController::myAlerts()->count();

        /*
         * Permisos solicitados por docentes
         */
        $count += TeacherPermitController::pendingPermits()->count();

        /*
         * Permisos solicitados por orientadores
         */
        $count += OrientationPermitController::pendingPermits()->count();

        /*
         * Permisos solicitados por coordinadores
         */
        $count += CoordinationPermitController::pendingPermits()->count();

        return $count;
    }
}
