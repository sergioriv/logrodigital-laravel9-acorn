<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class TeacherSubjectGroup extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'teacher_id',
        'subject_id',
        'group_id',
        'work_schedule'
    ];

    /*
    * PARENTS
    */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class)->select('id','first_name','father_last_name');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class); //->with('resourceArea', 'resourceSubject');
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
