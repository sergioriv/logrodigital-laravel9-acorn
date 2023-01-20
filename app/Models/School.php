<?php

namespace App\Models;

use App\Traits\FormatDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    use FormatDate;

    public $table = 'school';

    protected $fillable = [
        'name',
        'nit',
        'dane',
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
