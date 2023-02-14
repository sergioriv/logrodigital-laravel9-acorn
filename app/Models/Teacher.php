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
        'file_appointment',
        'possession_certificate',
        'date_possession_certificate',
        'file_possession_certificate',
        'transfer_resolution',
        'date_transfer_resolution',
        'file_transfer_resolution',

        'signature',

        'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    public function expeditionCity()
    {
        return $this->belongsTo(City::class, 'expedition_city', 'id');
    }
    public function birthCity()
    {
        return $this->belongsTo(City::class, 'birth_city', 'id');
    }
    public function residenceCity()
    {
        return $this->belongsTo(City::class, 'residence_city', 'id');
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

    public function hierarchies()
    {
        return $this->hasMany(TeacherHierarchy::class, 'teacher_id', 'id');
    }

    public function degrees()
    {
        return $this->hasMany(TeacherDegree::class, 'teacher_id', 'id');
    }

    public function employments()
    {
        return $this->hasMany(TeacherEmploymentHistory::class, 'teacher_id', 'id');
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
    protected function dateEntry(): Attribute
    {
        return Attribute::make(
            set: fn($v) => Carbon::parse($v)->format('Y-m-d')
        );
    }
}
