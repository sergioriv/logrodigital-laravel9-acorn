<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class RectorDegree extends Model
{
    use Uuid;

    protected $fillable = [
        'rector_id',
        'institution',
        'degree',
        'date',
        'url',
    ];
}
