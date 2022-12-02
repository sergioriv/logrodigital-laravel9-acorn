<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    use FormatDate;

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
    /* public function teacherSubject()
    {
        return $this->hasOne(TeacherSubjectGroup::class, 'subject_id', 'id');
    } */

    public function studyYearSubject()
    {
        return $this->hasOne(StudyYearSubject::class,'subject_id','id');
    }

    public function piar()
    {
        return $this->hasMany(Piar::class);
    }
    public function piarOne()
    {
        return $this->hasOne(Piar::class,'subject_id','id');
    }

}
