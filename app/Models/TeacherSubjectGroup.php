<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectGroup extends Model
{
    use HasFactory;
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'school_year_id',
        'teacher_id', //null
        'subject_id',
        'group_id'
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
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->select('id', 'uuid', 'names', 'last_names', 'institutional_email');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class)->with('resourceSubject');
    }

    public function group()
    {
        return $this->belongsTo(Group::class)->with('headquarters', 'studyTime', 'studyYear');
    }


    /*
     * CHILDREN
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function descriptorsStudent()
    {
        return $this->hasMany(StudentDescriptor::class, 'teacher_subject_group_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'teacher_subject_group_id', 'id');
    }
}
