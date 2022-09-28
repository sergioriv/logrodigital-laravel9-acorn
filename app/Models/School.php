<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class School extends CastCreateModel
{
    use HasFactory;

    public $table = 'school';

    protected $fillable = [
        'name',
        'nit',
        'contact_email',
        'contact_telephone',
        'city',
        'badge',
        'institutional_email',
        'handbook_coexistence'
    ];

    protected $hidden = [
        'security_email'
    ];

    /* Mutadores y Accesores */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->addDays(61)->format('Y-m-d')
        );
    }
}
