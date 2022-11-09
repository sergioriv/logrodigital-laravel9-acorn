<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Period extends CastCreateModel
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'school_year_id',
        'study_time_id',
        'period_type_id',
        'ordering',
        'name',
        'start',
        'end',
        'workload',
        'days'
    ];

    /*
    * PARENTS
    */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
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
