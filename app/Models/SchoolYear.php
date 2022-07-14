<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class SchoolYear extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'available'
    ];

    /*
     * available = NULL years unaivalable
     * available = 0 or 1 years editable
     * */


    /*
     * CHILDREN
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherSubjectGroup::class);
    }

}
