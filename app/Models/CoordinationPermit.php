<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CoordinationPermit extends Model
{
    use Uuid;

    protected $fillable = [
        'user_id',
        'coordination_id',
        'description',
        'start',
        'end',
        'url',
        'url_absolute',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
    ];

    /* Accesores */
    public function dateRange()
    {
        return "{$this->start} - {$this->end}";
    }
}
