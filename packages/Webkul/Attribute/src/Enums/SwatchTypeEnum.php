<?php

namespace Webkul\Attribute\Enums;

enum SwatchTypeEnum: string
{
    /**
     * Dropdown swatch type.
     */
    case DROPDOWN = 'dropdown';

    /**
     * Color swatch type.
     */
    case COLOR = 'color';

    /**
     * Image swatch type.
     */
    case IMAGE = 'image';

    /**
     * Text swatch type.
     */
    case TEXT = 'text';

    /**
     * Get all attribute type values as an array.
     */
    public static function getValues(): array
    {
        return array_map(
            fn (self $case) => $case->value,
            self::cases()
        );
    }
}
