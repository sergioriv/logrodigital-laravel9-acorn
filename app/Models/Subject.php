<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Subject extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'resource_area_id',
        'resource_subject_id'
    ];

    /*
    * PARENTS
    */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function resourceArea()
    {
        return $this->belongsTo(ResourceArea::class);
    }

    public function resourceSubject()
    {
        return $this->belongsTo(ResourceSubject::class);
    }


    /*
     * CHILDREN
     */
    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherSubjectGroup::class);
    }

    public function studyYearSubject()
    {
        return $this->hasMany(StudyYearSubject::class);
    }

}
