<?php

declare(strict_types=1);

namespace Frosko\DSK\Enums;

enum Currency: string
{
    case BGN = 'BGN';
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';

    public static function default(): self
    {
        return self::BGN;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function tryByCode(mixed $code): self
    {
        return match ($code) {
            self::BGN->value => self::BGN,
            self::EUR->value => self::EUR,
            self::USD->value => self::USD,
            self::GBP->value => self::GBP,
            default          => self::default(),
        };
    }

    public function iso975(): int
    {
        return match ($this) {
            self::BGN => 975,
            self::EUR => 978,
            self::USD => 840,
            self::GBP => 826,
        };
    }
}
