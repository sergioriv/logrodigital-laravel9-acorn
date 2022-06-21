<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Grade extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'teacher_subject_group_id',
        'period_id',
        'student_id',
        'grade'
    ];

    /*
     * PARENTS
     */
    public function teacherSubjectGroup()
    {
        return $this->belongsTo(TeacherSubjectGroup::class)->with('teacher', 'subject', 'group');
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
