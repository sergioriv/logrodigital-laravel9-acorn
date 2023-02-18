<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CoordinationHierarchy extends Model
{
    use Uuid;

    protected $fillable = [
        'coordination_id',
        'number',
        'resolution',
        'date',
        'url',
    ];
}
