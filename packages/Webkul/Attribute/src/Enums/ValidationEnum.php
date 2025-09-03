<?php

namespace Webkul\Attribute\Enums;

enum ValidationEnum: string
{
    /**
     * Numeric validation type.
     */
    case NUMERIC = 'numeric';

    /**
     * Email validation type.
     */
    case EMAIL = 'email';

    /**
     * Decimal validation type.
     */
    case DECIMAL = 'decimal';

    /**
     * URL validation type.
     */
    case URL = 'url';

    /**
     * Regex validation type.
     */
    case REGEX = 'regex';

    /**
     * Get all validation type values as an array.
     */
    public static function getValues(): array
    {
        return array_map(
            fn (self $case) => $case->value,
            self::cases()
        );
    }
}
