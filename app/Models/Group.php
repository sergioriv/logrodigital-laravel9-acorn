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
        'teacher_id',
        'name',
        'student_quantity'
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
        return $this->belongsTo(Headquarters::class)->select('id', 'name');
    }

    public function studyTime()
    {
        return $this->belongsTo(StudyTime::class)->select('id', 'name');
    }

    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class)->select('id', 'name');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class)->select('id', 'first_name', 'father_last_name');
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
