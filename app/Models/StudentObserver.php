<?php

namespace App\Models;

use App\Enums\AnnotationTypeEnum;
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
        'school_year_id',
        'annotation_type',
        'date',
        'situation_description',
        'free_version',
        'agreements',
        'accept',
        'created_user_id',
        'created_rol',
        'file_support'
    ];

    protected $casts = [
        'annotation_type' => AnnotationTypeEnum::class
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function user_creator()
    {
        return $this->morphTo('user_creator', 'created_rol', 'created_user_id', 'id');
    }

    /*
     *
     *  */
    public function isAccept(): bool
    {
        return $this->accept === 1;
    }
    public function isReject(): bool
    {
        return $this->accept === 0;
    }
    /*
     *
     *  */

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn($v) => Carbon::parse($v)->format('d/m/Y')
        );
    }
}
