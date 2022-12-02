<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Uuid;
    use FormatDate;

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
        return $this->students()->whereIn('attend', ['N', 'JUSTIFIED']);
    }
}
