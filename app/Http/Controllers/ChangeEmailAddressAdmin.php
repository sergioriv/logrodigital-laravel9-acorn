<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\Notify;
use App\Models\ChangeEmailCode;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChangeEmailAddressAdmin extends Controller
{


    protected $code;
    protected $modelType;
    protected $model;

    const MODEL_TEACHER = "App\Models\Teacher";

    public function __construct()
    {
        $this->middleware('hasroles:SUPPORT,SECRETARY');
    }


    public function teacher(Teacher $teacher)
    {
        if (is_null(SchoolController::myschool()->securityEmail())) {
            return ['status' => false, 'message' => 'fail|' . __('No security email exists')];
        }

        $generateCodeRemoval = $this->generate(self::MODEL_TEACHER, $teacher);

        if ($generateCodeRemoval === TRUE)
            return ['status' => true, 'message' => 'info|' . __("A code was sent to the security email")];
        else
            return ['status' => false, 'message' => 'fail|' . $generateCodeRemoval];
    }

    public function teacherUpdate(Request $request, Teacher $teacher)
    {

        $request->validate([
            'code_confirm' => ['required', 'string'],
            'new_email' => ['required', 'max:191', 'email', Rule::unique('users', 'email')->ignore($teacher->id)]
        ]);

        if (is_null(SchoolController::myschool()->securityEmail())) {
            Notify::fail(__('No security email exists'));
            return redirect()->back();
        }

        $confirmCode = ChangeEmailCode::where('model_type', self::MODEL_TEACHER)
            ->where('model_id', $teacher->id)
            ->where('code', $request->code_confirm)
            ->first();

        if (is_null($confirmCode)) {
            Notify::fail(__('Code invalid'));
            return redirect()->back();
        }


        $newEmail = Str::lower($request->new_email);

        /* Actualizamos el correo tanto en el modelo como en su usuario */
        $teacher->update([
            'institutional_email' => $newEmail
        ]);
        $teacher->user->update([
            'email' => $newEmail
        ]);


        /* Se borran los codigos que se hayan generado para el docente */
        ChangeEmailCode::where('model_type', self::MODEL_TEACHER)->where('model_id', $teacher->id)->delete();


        Notify::success(__('Email changed!'));
        return redirect()->back();
    }




    public function generate($modelType, $model)
    {
        $changeCodes = ChangeEmailCode::where('model_type', $modelType)->where('model_id', $model->id);
        if ($changeCodes->count()) {
            if ($changeCodes->latest()->first()->addMinutes() > now()) {
                $diff = Carbon::now()->diffInMinutes($changeCodes->first()->addMinutes());
                return __("Must wait :minutes minutes to send another code.", ['minutes' => $diff]);
            }
        }


        $this->modelType = $modelType;
        $this->model = $model;
        $this->code = Str::upper(Str::random(6));

        if ($this->sendMail())
            $this->save();
        else
            return __('Unexpected Error');

        return true;
    }

    private function save()
    {
        (new ChangeEmailCode())->forceFill([
            'model_type' => $this->modelType,
            'model_id' => $this->model->id,
            'code' => $this->code,
            'created_id' => auth()->id(),
            'created_at' => now()
        ])->save();
    }

    private function sendMail()
    {
        return SmtpMail::init()->sendChangeEmailCode($this->model->getFullName(), $this->code);
    }
}
