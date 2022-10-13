<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTracking extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'student_tracking';

    public $fillable = [];


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
            get: fn ($value) => strtolower($value)
        );
    }
    protected function typeAdvice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(strtolower($value)),
        );
    }
    protected function attendance(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(strtolower($value)),
        );
    }

    public function dateFull()
    {
        return "{$this->date} {$this->time}";
    }

}
