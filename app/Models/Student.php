<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends CastCreateModel
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
    public function groupStudents()
    {
        return $this->hasMany(GroupStudent::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
