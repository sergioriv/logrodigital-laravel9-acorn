<?php

namespace App\Models;

use App\Models\StudyYear as ModelsStudyYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudyYearSubject extends CastCreateModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'school_year_id',
        'study_year_id',
        'subject_id',
        'hours_week',
        'course_load'
    ];


    /*
     * PARENTS
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function studyYear()
    {
        return $this->belongsTo(ModelsStudyYear::class);
    }



}
