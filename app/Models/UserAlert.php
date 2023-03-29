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
        'checked',
        'user_approval_id',
        'approval'
    ];

    protected $casts = [
        'for_users' => 'array',
        'checked' => 'array',
        'priority' => 'boolean',
        'approval' => 'boolean'
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

    public function user_approval()
    {
        return $this->belongsTo(Coordination::class, 'user_approval_id', 'id');
    }

    public function approvalCheck()
    {
        return $this->approval ? '<i class="icon bi-check-circle text-primary ms-1"></i>' : '';
    }
}
