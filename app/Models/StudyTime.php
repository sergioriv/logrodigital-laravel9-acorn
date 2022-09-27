<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudyTime extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'conceptual',
        'procedural',
        'attitudinal',
        'missing_areas',
    ];


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


    /**
     * Mutadores y Accesores
     */

    /* protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords(strtolower($value)),
        );
    } */
}
