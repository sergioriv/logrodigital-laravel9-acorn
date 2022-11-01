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
        'resource_study_year_id'
    ];


    public function resource()
    {
        return $this->belongsTo(ResourceStudyYear::class, 'resource_study_year_id', 'id');
    }


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

}
