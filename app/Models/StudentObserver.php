<?php

namespace App\Models;

use App\Models\Data\Enum\AnnotationTypeEnum;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StudentObserver extends Model
{
    use Uuid;

    protected $table = 'student_observer';

    protected $fillable = [
        'student_id',
        'annotation_type',
        'date',
        'situation_description',
        'free_version',
        'agreements',
        'created_user_id',
        'created_rol'
    ];

    protected $casts = [
        'annotation_type' => AnnotationTypeEnum::class
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn($v) => Carbon::parse($v)->format('d/m/Y')
        );
    }
}
