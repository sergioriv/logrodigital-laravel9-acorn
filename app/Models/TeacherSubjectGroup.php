<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TeacherSubjectGroup extends CastCreateModel
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'school_year_id',
        'teacher_id',
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
        return $this->belongsTo(Subject::class); //->with('resourceArea', 'resourceSubject');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }


    /*
     * CHILDREN
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
