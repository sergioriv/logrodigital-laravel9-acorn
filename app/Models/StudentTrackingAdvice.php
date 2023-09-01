<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTrackingAdvice extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'student_tracking';

    public $fillable = [
        'user_id',
        'student_id',
        'type_tracking',
        'date',
        'time',
        'attendance',
        'type_advice',
        'advice_family',
        'evolution',
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
    protected function typeTracking(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
            get: fn ($value) => strtolower($value),
        );
    }
    protected function typeAdvice(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
            get: fn ($value) => strtolower($value),
        );
    }
    protected function attendance(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
            get: fn ($value) => strtolower($value),
        );
    }


    public function enumAttendance() //enum
    {
        return ['Done', 'Not done', 'Scheduled'];
    }
    public static function enumTypeAdvice() //enum
    {
        return ['Individual', 'Group', 'Family'];
    }

}
