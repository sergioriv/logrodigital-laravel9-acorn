<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTrackingTeacher extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'student_tracking';

    public $fillable = [
        'user_id',
        'student_id',
        'type_tracking',
        'recommendations_teachers',
        'date_limit_teacher'
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


    protected function typeTracking(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
            get: fn ($value) => strtolower($value),
        );
    }

}
