<?php

namespace Webkul\Menu\Traits;

trait Makeable
{
    /**
     * Make.
     *
     * @param  array  ...$arguments
     */
    public static function make(...$arguments): static
    {
        $static = new static(...$arguments);

        $static->afterMake();

        return $static;
    }

    /**
     * After make.
     */
    protected function afterMake(): void
    {
    }
}
