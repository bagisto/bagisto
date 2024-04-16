<?php

namespace Webkul\Core\Enums;

enum CurrencyPositionEnum: string
{
    /**
     * Left.
     */
    case LEFT = 'left';

    /**
     * Left with space.
     */
    case LEFT_WITH_SPACE = 'left_with_space';

    /**
     * Right.
     */
    case RIGHT = 'right';

    /**
     * Right with space.
     */
    case RIGHT_WITH_SPACE = 'right_with_space';

    /**
     * Options.
     *
     * @return void
     */
    public static function options()
    {
        return [
            CurrencyPositionEnum::LEFT->value             => trans('core::app.currency-position.options.left'),
            CurrencyPositionEnum::LEFT_WITH_SPACE->value  => trans('core::app.currency-position.options.left-with-space'),
            CurrencyPositionEnum::RIGHT->value            => trans('core::app.currency-position.options.right'),
            CurrencyPositionEnum::RIGHT_WITH_SPACE->value => trans('core::app.currency-position.options.right-with-space'),
        ];
    }
}
