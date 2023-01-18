<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class TeacherEmploymentHistory extends Model
{
    use Uuid;

    protected $table = 'teacher_employment_history';

    protected $fillable = [
        'teacher_id',
        'institution',
        'date_start',
        'date_end',
        'url',
    ];
}
