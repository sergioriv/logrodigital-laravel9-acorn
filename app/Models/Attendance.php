<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Uuid;

    protected $fillable = [
        'teacher_subject_group_id',
    ];


    /*
     * PARENTS
     */
    public function teacherSubjectGroup()
    {
        return $this->belongsTo(TeacherSubjectGroup::class)->with('teacher', 'subject', 'group');
    }

    /* CHILDREN */
    public function students()
    {
        return $this->hasMany(AttendanceStudent::class, 'attendance_id');
    }

    public function absences()
    {
        return $this->students()->where('attend', 'N');
    }
}
