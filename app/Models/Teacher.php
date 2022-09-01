<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Teacher extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'telephone',
        'document',
        'first_name',
        'second_name',
        'father_last_name',
        'mother_last_name',
        'bonding_type',
        'latest_degree',
        'institutional_email',
        'personal_email',
        'birthdate',
        'whatsapp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /*
     * CHILDREN
     */
    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherSubjectGroup::class);
    }

    public function director_groups()
    {
        return $this->hasMany(Group::class);
    }


    /*
     * Accesores
     */
    public function getFullName()
    {
        return "{$this->first_name} {$this->father_last_name}";
    }
    public function getNames()
    {
        return "{$this->first_name} {$this->second_name}";
    }
    public function getLastNames()
    {
        return "{$this->father_last_name} {$this->mother_last_name}";
    }

    protected function bondingType(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(strtolower($value)),
        );
    }

    protected function secondName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value !== NULL ? $value : ''
        );
    }
    protected function motherLastName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value !== NULL ? $value : ''
        );
    }
}
