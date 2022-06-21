<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Group extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'headquarters_id',
        'study_time_id',
        'study_year_id',
        'name'
    ];

    /*
    * PARENTS
    */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function headquarters()
    {
        return $this->belongsTo(Headquarters::class);
    }

    public function studyTime()
    {
        return $this->belongsTo(StudyTime::class);
    }

    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class);
    }


    /*
     * CHILDREN
     */
    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherSubjectGroup::class);
    }

    public function groupStudents()
    {
        return $this->hasMany(GroupStudent::class);
    }
}
