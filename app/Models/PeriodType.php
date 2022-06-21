<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class PeriodType extends CastCreateModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];


    /*
     * CHILDREN
     */
    public function periods()
    {
        return $this->hasMany(Period::class);
    }

}
