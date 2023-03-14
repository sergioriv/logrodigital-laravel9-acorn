<?php

namespace App\Enums;

enum AttendStudentEnum: string
{
    case YES = 'Y';
    case NO = 'N';
    case JUSTIFIED = 'J';
    case LATE_ARRIVAL = 'L';

    public function getLabelText(): string
    {
        return match($this) {
            self::YES => __('Asistió'),
            self::NO => __('No justificada'),
            self::JUSTIFIED => __('Justified'),
            self::LATE_ARRIVAL => __('Late arrival'),
        };
    }

}
