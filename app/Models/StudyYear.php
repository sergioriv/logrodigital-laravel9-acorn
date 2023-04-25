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
        'resource_study_year_id',
        'use_grades',
        'use_components',
        'use_descriptors'
    ];

    public function useGrades(): bool { return $this->use_grades == 1; }
    public function useComponents(): bool { return $this->use_components == 1; }
    public function useDescriptors(): bool { return $this->use_descriptors == 1; }

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

    public function academicWorkload()
    {
        return $this->hasMany(AcademicWorkload::class);
    }

}
