<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CoordinationEmploymentHistory extends Model
{
    use Uuid;

    protected $table = 'coordination_employment_history';

    protected $fillable = [
        'coordination_id',
        'institution',
        'date_start',
        'date_end',
        'url',
    ];
}
