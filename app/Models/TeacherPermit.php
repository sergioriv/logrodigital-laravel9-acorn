<?php

namespace App\Models;

use App\Enums\StatusPermitEnum;
use App\Traits\FormatDate;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class TeacherPermit extends Model
{
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'type_permit_id',
        'description',
        'start',
        'end',
        'status',
        'accept_deny_type',
        'accept_deny_id'
    ];

    protected $casts = [
        'status' => StatusPermitEnum::class
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function accept_deny()
    {
        return $this->morphTo('accept_deny', 'accept_deny_type', 'accept_deny_id', 'id')->select('id', 'names', 'last_names');
    }

    public function typePermit()
    {
        return $this->belongsTo(TypePermitsTeacher::class, 'type_permit_id', 'id');
    }

    /* Accesores */
    public function dateRange()
    {
        if ( $this->start === $this->end ) {
            return $this->dateRangeFormat($this->start);
        } else {
            return $this->dateRangeFormat($this->start) . ' - ' . $this->dateRangeFormat($this->end);
        }
    }

    private function dateRangeFormat($v)
    {
        return $this->parseDateWithSlash( Carbon::parse($v)->format('d/M') );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->parseDateWithSlash( Carbon::parse($v)->format('d/M/Y H:i') )
        );
    }
}
