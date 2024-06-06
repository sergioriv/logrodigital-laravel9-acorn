<?php

namespace App\Models;

use App\Enums\AttendStudentEnum;
use Illuminate\Database\Eloquent\Model;

class AttendanceStudent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'attendance_id',
        'student_id',
        'attend',
        'file_support'
    ];

    protected $casts = [
        'attend' => AttendStudentEnum::class
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
        return $this->belongsTo(Student::class)->select('id', 'code', 'first_name', 'second_name', 'first_last_name', 'second_last_name', 'status');
    }
}
