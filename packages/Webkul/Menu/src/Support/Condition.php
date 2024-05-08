<?php

namespace Webkul\Menu\Support;

use Closure;

final class Condition
{
    /**
     * Returns the Boolean value of the condition
     *
     * @param  bool  $default  Default value. Return if condition not isset
     */
    public static function boolean(
        Closure|bool|null $condition,
        bool $default
    ): bool {
        if (is_null($condition)) {
            return $default;
        }

        return value($condition);
    }
}
