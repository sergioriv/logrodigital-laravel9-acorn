<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class LeveledStudent extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $fillable = [
        'teacher_subject_group_id',
        'student_id'
    ];


    /*
     * PARENTS
     */
    public function teacherSubjectGroup()
    {
        return $this->belongsTo(TeacherSubjectGroup::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
