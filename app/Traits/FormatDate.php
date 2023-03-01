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

    /**
     * Parse date from "d/m/Y" format to "{Day}/{Month Name}/{Year}" format.
     * @param $yearMonth
     * @return string
     */
    private function parseDateWithSlash($date)
    {
        $dateArray = explode("/", $date);

        $month = trans("months.abbreviation.{$dateArray[1]}");

        if ( count($dateArray) === 2 )
            return "{$dateArray[0]}/{$month}";

        return "{$dateArray[0]}/{$month}/{$dateArray[2]}";
    }
}
