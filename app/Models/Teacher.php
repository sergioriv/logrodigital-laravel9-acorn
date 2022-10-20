<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Teacher extends CastCreateModel
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'uuid',
        'names',
        'last_names',
        'institutional_email',
        'date_entry',

        'document',
        'expedition_city',
        'birth_city',
        'birthdate',
        'residence_city',
        'address',
        'telephone',
        'cellphone',
        'marital_status',

        'type_appointment',
        'type_admin_act',
        'appointment_number',
        'date_appointment',
        'possession_certificate',
        'date_possession_certificate',
        'transfer_resolution',
        'date_transfer_resolution',

        'hierarchy_grade',
        'resolution_hierarchy',
        'date_resolution_hierarchy',

        'last_diploma',
        'institution_last_diploma',
        'date_last_diploma',

        'active'
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
        return $this->hasMany(TeacherSubjectGroup::class, 'teacher_id', 'id');
    }

    public function director_groups()
    {
        return $this->hasMany(Group::class);
    }


    /* Accesores */
    public function fullName()
    {
        return "{$this->names} {$this->last_names}";
    }
}
