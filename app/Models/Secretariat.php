<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretariat extends Model
{
    use HasFactory;

    protected $table = 'user_secretariat';

    protected $fillable = [
        'id',
        'name',
        'last_names',
        'email',
        'telephone',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
