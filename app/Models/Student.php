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
        'institutional_email',
        'telephone',
        'document_type_code',
        'document',
        'expedition_city_id',
        'number_siblings',
        'country_id', // *
        'birth_city_id',
        'birthdate',
        'gender_id',
        'rh_id',


        'zone',
        'residence_city_id',
        'address',
        'social_stratum',
        'dwelling_type_id', //* (Propia, Familiar, En arriendo, Usufructo, Posecion sin título)
        'neighborhood', //*
            'electrical_energy', //*
            'natural_gas', //*
            'sewage_system', //*
            'aqueduct', //*
            'internet', //*
            'lives_with_father', //*
            'lives_with_mother', //*
            'lives_with_siblings', //*
            'lives_with_other_relatives', //*

        'health_manager_id',
        'school_insurance',
        'sisben_id',
        'disability_id', //cambiar por lista de seleccion

        'ethnic_group_id',
        'conflict_victim',
        'origin_school_id',
        'ICBF_protection_measure_id', //* (Ninguna, Hogar sustituto, Medio abierto, Menor infractor)
        'foundation_beneficiary', //* (Si, No)
        'linked_to_process_id', //* (Ninguna, ICBF, Comisaria de familia, Fiscalia, Inspeccion de policia)
        'religion_id', //*
        'economic_dependence_id', //* (recuersos familiares, recursos propios, ...)

        'plays_sports', //* (Si, No)
        'freetime_activity', //*
        'allergies', //*
        'medicines', //*
        'favorite_subjects',
        'most_difficult_subjects',
        'insomnia',
        'colic',
        'biting_nails',
        'sleep_talk',
        'nightmares',
        'seizures',
        'physical_abuse',
        'pee_at_night',
        'hear_voices',
        'fever',
        'fears_phobias',
        'drug_consumption',
        'head_blows',
        'desire_to_die',
        'see_strange_things',
        'learning_problems',
        'dizziness_fainting',
        'school_repetition',
        'accidents',
        'asthma',
        'suicide_attempts',
        'constipation',
        'stammering',
        'hands_sweating',
        'sleepwalking',
        'nervous_tics',


        'inclusive',

        'headquarters_id',
        'study_time_id',
        'study_year_id',
        'enrolled_date',
        'enrolled',
        'status',
        'person_charge'
,
    ];

    /*
    * nacionalidad
    * medida de proteccion ICBF (No, Hogar sustituto, Medio abierto, Menor infractor)
    * beneficiario de fundacion
    * vinculacion de procesos (Ninguna, ICBF, Comisaria de familia, Fiscalia, Inspeccion de policia)
    * Tipo de vivienda (Propia, Familiar, En arriendo, Usufructo, Posecion sin título)
    * servicios de la vivienda (Energía eléctrica, Gas natural, Alcantarillado, Acueducto, Internet)
    * Barrio
    * religion
    * religion de la familia
    * dependencia económica (recuersos familiares, recursos propios, ...)
    * deporte que practica
    * actividad en tiempo libre
    * con quien vive en su casa (...)
    * ha sido victima de: (Abuso sexual, Maltrato, Violencia intrafamiliar, Bullyng, ...)
    *
    * alergias
    *
    * antecedentes psicosociales (...)
    */

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
