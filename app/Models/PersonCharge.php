<?php

namespace App\Models;

use App\Traits\FormatDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonCharge extends Model
{
    use HasFactory;
    use FormatDate;

    protected $table = 'persons_charge';

    protected $fillable = [
        'id',
        'student_id',
        'name',
        'email',
        'document',
        'expedition_city_id',
        'residence_city_id',
        'address',
        'telephone',
        'cellphone',
        'birthdate',
        'kinship_id',
        'occupation'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }


     /*
      * PARENTS
      */
    public function kinship()
    {
        return $this->belongsTo(kinship::class);
    }
    public function residenceCity()
    {
        return $this->belongsTo(City::class, 'residence_city_id');
    }


    /* Cast */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            set: fn($v) => Carbon::parse($v)->format('Y-m-d')
        );
    }
}
