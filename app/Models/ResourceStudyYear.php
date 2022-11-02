<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ResourceStudyYear extends Model
{
    public $timestamps = false;

    protected $fillable = ['uuid', 'name', 'next_year'];

    protected $hidden = ['id'];




    public function studentReportBook()
    {
        return $this->hasOne(StudentReportBook::class,'resource_study_year_id', 'id');
    }

    public function study_years()
    {
        return $this->hasMany(StudyYear::class, 'id', 'resource_study_year_id');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value)
        );
    }
}
