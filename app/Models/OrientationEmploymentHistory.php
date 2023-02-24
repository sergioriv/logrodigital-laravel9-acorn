<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class OrientationEmploymentHistory extends Model
{
    use Uuid;

    protected $table = 'orientation_employment_history';

    protected $fillable = [
        'orientation_id',
        'institution',
        'date_start',
        'date_end',
        'url',
    ];
}
