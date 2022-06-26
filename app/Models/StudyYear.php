<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudyYear extends CastCreateModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'available'
    ];


    /*
     * CHILDREN
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }


    /**
     * Mutadores y Accesores
     */

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords(strtolower($value)),
        );
    }
}
