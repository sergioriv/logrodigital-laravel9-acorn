<?php

namespace App\Models;

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

    /*  */
    public function orientator()
    {
        if ( $this->created_rol === 'ORIENTATION' ) {
            return $this->belongsTo(Orientation::class, 'created_user_id', 'id');
        }

        return [];
    }

    public function teacher()
    {
        if ( $this->created_rol === 'TEACHER' ) {
            return $this->belongsTo(Teacher::class, 'created_user_id', 'id');
        }

        return [];
    }

    public function student()
    {
        if ( $this->student_id ) {
            return $this->belongsTo(Student::class, 'student_id', 'id');
        }

        return [];
    }
}
