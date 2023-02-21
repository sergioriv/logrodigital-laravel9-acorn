<?php

namespace App\Enums;

enum VotingStatusEnum: int
{

    case CREATED = 1;
    case STARTED = 2;
    case FINISHED = 3;

    public function isCreated(): bool
    {
        return $this === self::CREATED;
    }
    public function isStarted(): bool
    {
        return $this === self::STARTED;
    }
    public function isFinished(): bool
    {
        return $this === self::FINISHED;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::CREATED => __('created'),
            self::STARTED => __('started'),
            self::FINISHED => __('finished'),
        };
    }

}
