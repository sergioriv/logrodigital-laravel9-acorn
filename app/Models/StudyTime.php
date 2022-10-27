<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'conceptual',
        'procedural',
        'attitudinal',
        'missing_areas',
        'minimum_grade',
        'low_performance',
        'acceptable_performance',
        'high_performance',
        'maximum_grade'
    ];

    protected $hidden = ['active'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value, 'UTC')->format('Y-m-d')
        );
    }



    /*
     * CHILDREN
     */
    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }


    /* accesores */
    public function lowRange()
    {
        return "{$this->minimum_grade} - {$this->low_performance}";
    }

    public function acceptableRange()
    {
        return ($this->low_performance + 0.01) ." - {$this->acceptable_performance}";
    }

    public function highRange()
    {
        return ($this->acceptable_performance + 0.01) ." - {$this->high_performance}";
    }
    public function superiorRange()
    {
        return ($this->high_performance + 0.01) ." - {$this->maximum_grade}";
    }
}
