<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class OrientationDegree extends Model
{
    use Uuid;

    protected $fillable = [
        'orientation_id',
        'institution',
        'degree',
        'date',
        'url',
    ];
}
