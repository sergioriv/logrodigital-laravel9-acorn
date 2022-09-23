<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAdvice extends Model
{
    use HasFactory;

    protected $table = 'student_advices';

    public $fillable = [
        'user_id',
        'student_id',
        'date',
        'time',
        'attendance',
        'type_advice',
        'evolution',
        'recommendations_teachers',
        'date_limit_teacher',
        'recommendations_family',
        'entity_remit',
        'observations_for_entity'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
    ];

    /* MUTADORES Y ACCESORES */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'id');
    }


    public function dateFull()
    {
        return "{$this->date} {$this->time}";
    }
    protected function attendance(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtolower($value),
        );
    }


    public function enumAttendance() //enum
    {
        return ['Done', 'Not done', 'Scheduled'];
    }
    public function enumTypeAdvice() //enum
    {
        return ['Individual', 'Group', 'Family'];
    }
    public function enumEntityRemit() //enum
    {
        return ['Ninguna', 'Comisaría', 'ICBF'];
    }

}
