<?php

declare(strict_types=1);

namespace Frosko\DSK\Enums;

enum Locale: string
{
    case BULGARIAN = 'bg';
    case ENGLISH = 'en';

    public static function default(): self
    {
        return self::BULGARIAN;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function tryByCode(string $code): self
    {
        return match ($code) {
            self::BULGARIAN->value => self::BULGARIAN,
            self::ENGLISH->value   => self::ENGLISH,
            default                => self::default(),
        };
    }
}
