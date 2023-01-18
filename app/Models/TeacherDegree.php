<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class TeacherDegree extends Model
{
    use Uuid;

    protected $fillable = [
        'teacher_id',
        'institution',
        'degree',
        'date',
        'url',
    ];
}
