<?php

namespace App\Models;

use App\Models\Data\RoleUser;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserAlert extends Model
{
    use Uuid;

    protected $fillable = [
        'for_user',
        'priority',
        'title',
        'message',
        'student_id',
        'created_user_id',
        'created_rol',
    ];

    public function createdRol()
    {
        switch ($this->created_rol) {
            case RoleUser::ORIENTATION_ROL:
                return $this->belongsTo(Orientation::class, 'created_user_id', 'id');
                break;

            case RoleUser::TEACHER_ROL:
                return $this->belongsTo(Teacher::class, 'created_user_id', 'id');
                break;
        }

        return null;
    }

    public function student()
    {
        if ( $this->student_id ) {
            return $this->belongsTo(Student::class, 'student_id', 'id');
        }

        return null;
    }
}
