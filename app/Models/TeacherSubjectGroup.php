<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class TeacherSubjectGroup extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'group_id',
        'work_schedule'
    ];

    /*
    * PARENTS
    */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }


    /*
     * CHILDREN
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
