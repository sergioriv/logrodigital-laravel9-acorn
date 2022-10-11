<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyYear extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'next_year'
    ];

    protected $casts = [
        'next_year' => 'array'
    ];


    /*
     * CHILDREN
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function studyYearSubject()
    {
        return $this->hasMany(StudyYearSubject::class);
    }



    public function countNextYear()
    {
        return count($this->next_year);
    }
    public function nextYears()
    {
        $nextYears = [];
        foreach ($this->next_year as $value) {
            array_push($nextYears, StudyYear::find($value));
        }
        return $nextYears;
    }

}
