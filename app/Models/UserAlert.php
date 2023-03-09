<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserAlert extends Model
{
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'for_users',
        'priority',
        'message',
        'sub_message',
        'student_id',
        'created_user_type',
        'created_user_id',
        'checked'
    ];

    protected $casts = [
        'for_users' => 'array',
        'checked' => 'array',
        'priority' => 'boolean'
    ];


    public function created_user()
    {
        return $this->morphTo('created_user', 'created_user_type', 'created_user_id', 'id')->select('id', 'names', 'last_names');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'group_id'
        );
    }
}
