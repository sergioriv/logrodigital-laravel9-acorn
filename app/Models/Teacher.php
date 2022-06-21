<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Teacher extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'telephone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /*
     * CHILDREN
     */
    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherSubjectGroup::class);
    }
}
