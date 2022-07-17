<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'first_name',
        'second_name',
        'father_last_name',
        'mother_last_name',
        'document_type_code',
        'document',
        'telephone',
        'institutional_email',
        'zone',
        'address',
        'health_manager_id',
        'residence_city_id',
        'expedition_city_id',
        'birth_city_id',
        'birthdate',
        'gender_id',
        'rh_id',
        'conflict_victim',
        'number_siblings',
        'sisben_id',
        'social_stratum',
        // 'lunch',
        // 'refreshment',
        // 'transport',
        'ethnic_group_id',
        'disability',
        'origin_school_id',
        'school_insurance',
        'headquarters_id',
        'study_time_id',
        'study_year_id',
        'enrolled_date',
        'enrolled',
        'status',
        'inclusive',
        'person_charge'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /*
     * PARENTS
     */
    public function headquarters()
    {
        return $this->belongsTo(Headquarters::class);
    }
    public function studyTime()
    {
        return $this->belongsTo(StudyTime::class);
    }
    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class);
    }
    public function documentTypeCode()
    {
        return $this->belongsTo(DocumentType::class, 'code');
    }
    public function healthManager()
    {
        return $this->belongsTo(HealthManager::class);
    }
    public function sisben()
    {
        return $this->belongsTo(Sisben::class);
    }
    public function ethnicGroup()
    {
        return $this->belongsTo(EthnicGroup::class);
    }
    public function originSchool()
    {
        return $this->belongsTo(OriginSchool::class);
    }




    /*
     * CHILDREN
     */
    public function groupYear()
    {
        return $this->hasOne(GroupStudent::class,'student_id','id');
    }

    public function groupStudents()
    {
        return $this->hasMany(GroupStudent::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function mother()
    {
        return $this->hasOne(PersonCharge::class,'student_id')->where('kinship_id', 1);
    }
    public function father()
    {
        return $this->hasOne(PersonCharge::class,'student_id')->where('kinship_id', 2);
    }
    public function tutor()
    {
        return $this->hasOne(PersonCharge::class,'student_id')->where('kinship_id', '>', 2);
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

    public function age()
    {
        if (NULL !== $this->birthdate)
            return Carbon::createFromDate($this->birthdate)->diff(Carbon::now())->format('%y');
    }
}
