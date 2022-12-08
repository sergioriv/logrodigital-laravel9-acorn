<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    use Uuid;
    use FormatDate;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'id',
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

    public function permits()
    {
        return $this->hasMany(TeacherPermit::class, 'teacher_id', 'id');
    }


    /* Accesores */
    public function getFullName()
    {
        return "{$this->names} {$this->last_names}";
    }

    /* Cast */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            set: fn($v) => Carbon::parse($v)->format('Y-m-d')
        );
    }
}
