<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Period extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'headquarters_id',
        'study_time_id',
        'period_type_id',
        'name',
        'start',
        'end'
    ];

    /*
    * PARENTS
    */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function headquarters()
    {
        return $this->belongsTo(Headquarters::class);
    }

    public function studyTime()
    {
        return $this->belongsTo(StudyTime::class);
    }

    public function periodType()
    {
        return $this->belongsTo(PeriodType::class);
    }


    /*
     * CHILDREN
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
