<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    use FormatDate;

    protected $fillable = [
        'school_year_id',
        'headquarters_id',
        'study_time_id',
        'study_year_id',
        'teacher_id',
        'name',
        'student_quantity',
        'specialty'
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

    public function studyTimeSelectAll()
    {
        return $this->belongsTo(StudyTime::class, 'study_time_id', 'id');
    }

    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class)->select('id', 'name');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->select('id', 'uuid', 'names', 'last_names');
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
