<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AttendanceStudent extends Model
{
    use Uuid;

    protected $fillable = [
        'attendance_id',
        'student_id',
        'attend',
        'reason'
    ];


    /*
     * PARENTS
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
