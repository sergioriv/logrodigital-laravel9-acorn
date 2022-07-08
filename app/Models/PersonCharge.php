<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonCharge extends CastCreateModel
{
    use HasFactory;

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
}
