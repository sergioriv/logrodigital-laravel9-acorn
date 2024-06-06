<?php

namespace App\Models;

use App\Http\Controllers\SchoolYearController;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Uuid;

    protected $fillable = [
        'teacher_subject_group_id',
        'date',
        'hours'
    ];
    protected $casts = ['hours' => 'integer'];


    /*
     * PARENTS
     */
    public function teacherSubjectGroup()
    {
        $Y =  SchoolYearController::current_year();
        return $this->belongsTo(TeacherSubjectGroup::class)->where('school_year_id', $Y->id);
    }

    /* CHILDREN */
    public function students()
    {
        return $this->hasMany(AttendanceStudent::class, 'attendance_id');
    }
    public function student()
    {
        return $this->hasOne(AttendanceStudent::class, 'attendance_id');
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
