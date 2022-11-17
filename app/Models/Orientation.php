<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Orientation extends Model
{
    use Uuid;

    protected $primaryKey = 'uuid';
    protected $table = 'user_orientation';

    protected $fillable = [
        'id',
        'name',
        'last_names',
        'email',
        'telephone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }


    /* accesores */
    public function getFullName()
    {
        return "{$this->name} {$this->last_names}";
    }
}
