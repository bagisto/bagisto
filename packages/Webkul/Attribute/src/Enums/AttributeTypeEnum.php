<?php

namespace Webkul\Attribute\Enums;

enum AttributeTypeEnum: string
{
    /**
     * Text type attribute.
     */
    case TEXT = 'text';

    /**
     * Textarea type attribute.
     */
    case TEXTAREA = 'textarea';

    /**
     * Price type attribute.
     */
    case PRICE = 'price';

    /**
     * Boolean type attribute.
     */
    case BOOLEAN = 'boolean';

    /**
     * Checkbox type attribute.
     */
    case CHECKBOX = 'checkbox';

    /**
     * Select type attribute.
     */
    case SELECT = 'select';

    /**
     * Multiselect type attribute.
     */
    case MULTISELECT = 'multiselect';

    /**
     * Date type attribute.
     */
    case DATE = 'date';

    /**
     * Datetime type attribute.
     */
    case DATETIME = 'datetime';

    /**
     * Image type attribute.
     */
    case IMAGE = 'image';

    /**
     * File type attribute.
     */
    case FILE = 'file';

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

    /**
     * Get boolean options.
     */
    public static function getBooleanOptions(): array
    {
        return [
            [
                'id'   => 0,
                'name' => trans('attribute::app.boolean.options.no'),
            ],
            [
                'id'   => 1,
                'name' => trans('attribute::app.boolean.options.yes'),
            ],
        ];
    }
}
