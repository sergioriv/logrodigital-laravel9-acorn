<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CoordinationDegree extends Model
{
    use Uuid;

    protected $fillable = [
        'coordination_id',
        'institution',
        'degree',
        'date',
        'url',
    ];
}
