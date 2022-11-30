<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class PeriodPermit extends Model
{
    // use Uuid;

    protected $fillable = [
        'teacher_subject_group_id',
        'period_id',
        'user_created_id'
    ];
}
