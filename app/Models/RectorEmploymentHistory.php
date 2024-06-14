<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class RectorEmploymentHistory extends Model
{
    use Uuid;

    protected $table = 'rector_employment_history';

    protected $fillable = [
        'rector_id',
        'institution',
        'date_start',
        'date_end',
        'url',
    ];
}
