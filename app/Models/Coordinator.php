<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Coordinator extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'telephone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
