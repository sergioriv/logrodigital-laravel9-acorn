<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicWorkload extends Model
{
    use HasFactory;
    use Uuid;
    use FormatDate;

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
        return $this->belongsTo(StudyYear::class);
    }



}
