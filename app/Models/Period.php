<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'school_year_id',
        'study_time_id',
        'period_type_id',
        'ordering',
        'name',
        'start',
        'end',
        'workload',
        'start_grades',
        'end_grades',
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
    public function oneGrade()
    {
        return $this->hasOne(Grade::class);
    }
    public function permits()
    {
        return $this->hasMany(PeriodPermit::class, 'period_id', 'id');
    }
    public function remarks()
    {
        return $this->hasMany(Remark::class);
    }


    /* accesores */
    public function getFullDate()
    {
        return "{$this->start} / {$this->end}";
    }
    public function startLabel()
    {
        return $this->parseDateWithSlash( Carbon::parse($this->start)->format('d/M/Y') );
    }
    public function endLabel()
    {
        return $this->parseDateWithSlash( Carbon::parse($this->end)->format('d/M/Y') );
    }
    public function startGradesLabel()
    {
        return $this->parseDateWithSlash( Carbon::parse($this->start_grades)->format('d/M') );
    }
    public function endGradesLabel()
    {
        return $this->parseDateWithSlash( Carbon::parse($this->end_grades)->format('d/M') );
    }

    public function dateUploadingNotes()
    {
        if ( $this->start_grades !== $this->end_grades )
            return $this->startGradesLabel() .' - '. $this->endGradesLabel();
        else
            return $this->startGradesLabel();
    }

    public function active()
    {
        return (Carbon::now()->between($this->start_grades, $this->end_grades . ' 23:59:00'));
    }
}
