<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ResourceStudyYear extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $hidden = ['id'];

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
