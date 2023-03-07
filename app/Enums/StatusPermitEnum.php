<?php

namespace App\Enums;

enum StatusPermitEnum: int
{

    case PENDING = 0;
    case ACCEPTED = 1;
    case DENIED = 2;

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
    public function isAccepted(): bool
    {
        return $this === self::ACCEPTED;
    }
    public function isDenied(): bool
    {
        return $this === self::DENIED;
    }

    public function getLabelText(): string
    {
        return match($this) {
            self::PENDING => __('Pending'),
            self::ACCEPTED => __('Accepted'),
            self::DENIED => __('Denied'),
        };
    }

    public function getLabelHtml(): string
    {
        return match($this) {
            self::PENDING => '<div class="badge bg-muted">' . __('Pending') . '</div>',
            self::ACCEPTED => '<div class="badge bg-success">' . __('Accepted') . '</div>',
            self::DENIED => '<div class="badge bg-danger">' . __('Denied') . '</div>',
        };
    }

}
