<?php

namespace App\Models;

use App\Enums\StatusPermitEnum;
use App\Traits\FormatDate;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CoordinationPermit extends Model
{
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'user_id',
        'coordination_id',
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

    public function coordination()
    {
        return $this->belongsTo(Coordination::class, 'coordination_id', 'id');
    }

    public function accept_deny()
    {
        return $this->morphTo('accept_deny', 'accept_deny_type', 'accept_deny_id', 'id')->select('id', 'names', 'last_names');
    }

    /* Accesores */
    protected function start(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->parseDateWithSlash( Carbon::parse($v)->format('d/M') )
        );
    }

    protected function end(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->parseDateWithSlash( Carbon::parse($v)->format('d/M') )
        );
    }

    public function dateRange()
    {
        if ( $this->start === $this->end ) {
            return "{$this->start}";
        } else {
            return "{$this->start} - {$this->end}";
        }
    }
}
