<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class OrientationHierarchy extends Model
{
    use Uuid;

    protected $fillable = [
        'orientation_id',
        'number',
        'resolution',
        'date',
        'url',
    ];
}
