<?php

declare(strict_types=1);

namespace Frosko\DSK\Enums;

enum PaymentStatus: int
{
    case CREATED = 0;
    case APPROVED = 1;
    case DEPOSITED = 2;
    case REVERSED = 3;
    case REFUNDED = 4;
    case DECLINED = 5;
    case TIMEOUT = 6;

    public function label(): string
    {
        return match ($this) {
            self::CREATED   => 'Created',
            self::APPROVED  => 'Approved',
            self::DEPOSITED => 'Deposited',
            self::REVERSED  => 'Reversed',
            self::REFUNDED  => 'Refunded',
            self::DECLINED  => 'Declined',
            self::TIMEOUT   => 'Timeout',
        };
    }

    public static function fromValue(int $value): ?self
    {
        return self::tryFrom($value);
    }
}
