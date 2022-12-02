<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;
    use FormatDate;

    protected $fillable = [
        'name',
        'available'
    ];

    /*
     * available = NULL years unaivalable
     * available = 0 or 1 years editable
     * */


    /*
     * CHILDREN
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function oneGroupStudent()
    {
        return $this->hasOne(Group::class,'school_year_id','id');
    }

    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherSubjectGroup::class);
    }

    public function studyYearSubject()
    {
        return $this->hasMany(StudyYearSubject::class);
    }

}
