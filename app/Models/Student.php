<?php

namespace App\Models;

use App\Http\Controllers\SchoolController;
use App\Traits\FormatDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    use FormatDate;

    protected $fillable = [
        'id',
        'code',
        'first_name',
        'second_name',
        'first_last_name',
        'second_last_name',
        'institutional_email',
        'telephone',
        'document_type_code',
        'document',
        'expedition_city_id',
        'number_siblings',
        'siblings_in_institution',
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
        'reservation_id',
        'type_conflic_id',
        'origin_school',
        'type_origin_school',
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
        'see_shadows',
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
        'sexual_abuse',
        'unmotivated_crying',
        'chest_pain',
        'bullying',

        'simat', // (Si, No)
        'inclusive', // (Si, No)
        'medical_diagnosis',
        'medical_prediagnosis',

        'school_year_create',
        'headquarters_id',
        'study_time_id',
        'study_year_id',
        'group_id',
        'enrolled_date',
        'enrolled',
        'status',
        'person_charge',
        'data_treatment'
    ];

    protected $hidden = [
        'signature_tutor',
        'signature_student',
        'wizard_documents',
        'wizard_report_books',
        'wizard_person_charge',
        'wizard_personal_info',
        'wizard_complete'
    ];

    public static function singleData()
    {
        return self::select(
            'id',
            'code',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'institutional_email',
            'telephone',
            'document_type_code',
            'document',
            'headquarters_id',
            'study_time_id',
            'study_year_id',
            'group_id',
            'enrolled',
            'status',
            'inclusive',
            'created_at'
        );
    }


    /* ADICIONALES */
    public function enumTypeSchoolOrigin()
    {
        return ['Public', 'Private'];
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function isRepeat() : bool
    {
        return $this->status === 'repeat';
    }

    public function isRetired() : bool
    {
        return $this->status === 'retired';
    }

    /*
     * PARENTS
     */
    public function schoolYearCreate()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_create');
    }
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
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function groupSpecialty()
    {
        return $this->belongsTo(Group::class, 'group_specialty_id', 'id');
    }
    public function documentTypeCode()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_code', 'code');
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
    public function expeditionCity()
    {
        return $this->belongsTo(City::class, 'expedition_city_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function birthCity()
    {
        return $this->belongsTo(City::class, 'birth_city_id', 'id');
    }
    public function residenceCity()
    {
        return $this->belongsTo(City::class, 'residence_city_id', 'id');
    }
    public function dwellingType()
    {
        return $this->belongsTo(DwellingType::class, 'dwelling_type_id', 'id');
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id', 'id');
    }
    public function rh()
    {
        return $this->belongsTo(Rh::class, 'rh_id', 'id');
    }
    public function typeConflic()
    {
        return $this->belongsTo(TypesConflict::class, 'type_conflic_id');
    }
    public function disability()
    {
        return $this->belongsTo(Disability::class, 'disability_id');
    }




    /*
     * CHILDREN
     */
    public function groupYear()
    {
        return $this->hasOne(GroupStudent::class, 'student_id', 'id');
    }

    public function groupStudents()
    {
        return $this->hasMany(GroupStudent::class);
    }

    public function groupOfSpecialty()
    {
        return $this->hasOne(GroupStudent::class)
                ->withWhereHas('groupSpecialty');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function mother()
    {
        return $this->hasOne(PersonCharge::class, 'student_id')->where('kinship_id', 1);
    }
    public function father()
    {
        return $this->hasOne(PersonCharge::class, 'student_id')->where('kinship_id', 2);
    }
    public function tutor()
    {
        return $this->hasOne(PersonCharge::class, 'student_id')->where('kinship_id', '>', 2);
    }
    public function myTutorIs()
    {
        switch ($this->person_charge) {
            case 1:
                return $this->mother();
                break;

            case 2:
                return $this->father();
                break;

            default:
                return $this->tutor();
                break;
        }
    }
    public function files()
    {
        return $this->hasMany(StudentFile::class, 'student_id', 'id');
    }
    public function filesRequired()
    {
        return $this->hasMany(StudentFile::class, 'student_id', 'id')
            ->whereHas('studentFileType', fn ($sft) => $sft->where('required', 1));
    }
    public function reportBooks()
    {
        return $this->hasMany(StudentReportBook::class, 'student_id', 'id');
    }
    public function tracking()
    {
        return $this->hasMany(StudentTracking::class, 'student_id', 'id');
    }
    public function attendanceStudent()
    {
        return $this->hasMany(AttendanceStudent::class, 'student_id', 'id');
    }
    public function oneAttendanceStudent()
    {
        return $this->hasOne(AttendanceStudent::class, 'student_id', 'id');
    }
    public function studentDescriptors()
    {
        return $this->hasMany(StudentDescriptor::class, 'student_id', 'id');
    }



    /*
     * Accesores
     */
    public function getFullName()
    {
        return "{$this->first_name} {$this->first_last_name}";
    }

    public function getNames()
    {
        return "{$this->first_name} {$this->second_name}";
    }

    public function getLastNames()
    {
        return "{$this->first_last_name} {$this->second_last_name}";
    }

    public function getCompleteNames()
    {
        return "{$this->first_last_name} {$this->second_last_name} {$this->first_name} {$this->second_name}";
    }

    public function age()
    {
        if (NULL !== $this->birthdate)
            return Carbon::createFromDate($this->birthdate)->diff(Carbon::now())->format('%y');
    }

    public function tag()
    {

        $this->tag = null;

        if ($this->inclusive) {
            $this->tag .= ' <span class="badge bg-outline-warning">'. __('inclusive') .'</span> ';
        }

        if ($this->status === 'new') {
            $this->tag .= ' <span class="badge bg-outline-primary">'. __('new') .'</span> ';
        } elseif ($this->status === 'repeat') {
            $this->tag .= ' <span class="badge bg-outline-danger">'. __('repeat') .'</span> ';
        }


        return $this->tag;
    }



    /* contar retirados o no */
    public static function available()
    {
        $S = SchoolController::myschool();

        $available = self::when($S->getData()->withdraw, function ($withdraw) {
            $withdraw->where(function ($query) {
                $query->whereIn('status', ['new', 'repeat'])->orWhereNull('status');
            });
        });

        return $available;
    }


    /* Cast */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            set: fn($v) => Carbon::parse($v)->format('Y-m-d')
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('students.first_last_name')
                ->orderBy('students.second_last_name')
                ->orderBy('students.first_name')
                ->orderBy('students.second_name');
        });
    }
}
