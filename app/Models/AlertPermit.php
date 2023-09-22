<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AlertPermit extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $fillable = [
        'to_user_id',
        'type',
        'date'
    ];

    protected $casts = [];

    public function typeName()
    {
        return match($this->type) {
            1 => 'aprobado',
            2 => 'rechazado',
            default => ''
        };
    }

    public function message()
    {
        return "El permiso solicitado en la fecha <b>{$this->date}</b> ha sido <b>{$this->typeName()}</b>";
    }
}
