<?php

namespace App\Models;

use App\Models\Data\RoleUser;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserAlert extends Model
{
    use Uuid;

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
        // if ( $this->student_id ) {
            return $this->belongsTo(Student::class, 'student_id', 'id')->select(
                'id',
                'first_name',
                'second_name',
                'first_last_name',
                'second_last_name',
                'group_id');
        // }

        // return null;
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($v) => Carbon::parse($v)->format('d/m/Y')
        );
    }
}
