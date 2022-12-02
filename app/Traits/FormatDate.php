<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait FormatDate
{
    public function formatDate($d)
    {
        return ucwords( Carbon::parse($d)->translatedFormat('d M Y - g:i a') );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatDate($value)
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatDate($value)
        );
    }
}
