<?php

namespace App\Models;

use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Uuid;

    protected $fillable = [
        'teacher_subject_group_id',
        'date'
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
        return $this->students()->whereIn('attend', ['N', 'J', 'L']);
    }

    public function dateLabel()
    {
        return ucwords( Carbon::parse($this->date)->translatedFormat('d M') );
    }
}
