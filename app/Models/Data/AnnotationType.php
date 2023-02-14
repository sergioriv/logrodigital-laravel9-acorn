<?php

namespace App\Models\Data;

class AnnotationType
{
    public static function getData(): array
    {
        return [
            1 => __('Congratulation'),
            2 => __('Remark'),
            3 => __('Recommendation'),
            4 => __('Call for attention'),
            5 => __('Warning'),
            6 => __('Type I Situation (Dec. 1965 / 2013)'),
            7 => __('Type II Situation (Dec. 1965 / 2013)'),
            8 => __('Type III Situation (Dec. 1965 / 2013)'),
        ];
    }
}
