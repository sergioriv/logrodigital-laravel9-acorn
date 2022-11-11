<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Coordination extends Model
{
    use Uuid;

    protected $primaryKey = 'uuid';
    protected $table = 'user_coordination';

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


    /* accesored */
    public function fullName()
    {
        return "{$this->name} {$this->last_names}";
    }
}
