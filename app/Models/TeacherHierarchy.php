<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class TeacherHierarchy extends Model
{
    use Uuid;

    protected $fillable = [
        'teacher_id',
        'number',
        'resolution',
        'date',
        'url',
    ];
}
