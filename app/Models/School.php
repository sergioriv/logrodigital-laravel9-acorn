<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class School extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nit',
        'contact_email',
        'contact_telephone',
        'city',
        'badge',
        'students'
    ];
}
