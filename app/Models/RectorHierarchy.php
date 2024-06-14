<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class RectorHierarchy extends Model
{
    use Uuid;

    protected $fillable = [
        'rector_id',
        'number',
        'resolution',
        'date',
        'url',
    ];
}
