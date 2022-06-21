<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class GroupStudent extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'student_id'
    ];

    /*
    * PARENTS
    */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
