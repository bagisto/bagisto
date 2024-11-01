<?php

declare(strict_types=1);

namespace Frosko\DSK\Enums;

enum PageView: string
{
    case DESKTOP = 'DESKTOP';
    case MOBILE = 'MOBILE';

    public static function default(): self
    {
        return self::DESKTOP;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
