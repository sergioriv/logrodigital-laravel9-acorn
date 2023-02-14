<?php

namespace App\Models\Data\Enum;

enum AnnotationTypeEnum: int
{

    case CONGRATULATION = 1; // FELICITACION
    case REMARK = 2; // OBSERVACION
    case RECOMMENDATION = 3; // RECOMENDACION
    case CALL_FOR_ATTENTION = 4; //LLAMADE DE ATENCION
    case WARNING = 5; // AMONESTACION
    case TYPE_ONE = 6; // SITUACION TIPO 1 DECRETO 1965/2013
    case TYPE_TWO = 7; // SITUACION TIPO 2 DECRETO 1965/2013
    case TYPE_THREE = 8; // SITUACION TIPO 3 DECRETO 1965/2013

    public function isCongratulation(): bool
    {
        return $this === self::CONGRATULATION;
    }
    public function isRemark(): bool
    {
        return $this === self::REMARK;
    }
    public function isRecommendation(): bool
    {
        return $this === self::RECOMMENDATION;
    }
    public function isCallForAttention(): bool
    {
        return $this === self::CALL_FOR_ATTENTION;
    }
    public function isWarning(): bool
    {
        return $this === self::WARNING;
    }
    public function isTypeOne(): bool
    {
        return $this === self::TYPE_ONE;
    }
    public function isTypeTwo(): bool
    {
        return $this === self::TYPE_TWO;
    }
    public function isTypeThree(): bool
    {
        return $this === self::TYPE_THREE;
    }


    public function getLabelText(): string
    {
        return match ($this) {
            self::CONGRATULATION => __('Congratulation'),
            self::REMARK => __('Remark'),
            self::RECOMMENDATION => __('Recommendation'),
            self::CALL_FOR_ATTENTION => __('Call for attention'),
            self::WARNING => __('Warning'),
            self::TYPE_ONE => __('Type I Situation (Dec. 1965 / 2013)'),
            self::TYPE_TWO => __('Type II Situation (Dec. 1965 / 2013)'),
            self::TYPE_THREE => __('Type III Situation (Dec. 1965 / 2013)'),
        };
    }
}
