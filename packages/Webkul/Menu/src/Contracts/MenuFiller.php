<?php

namespace Webkul\Menu\Contracts;

interface MenuFiller
{
    /**
     * Get the route name.
     */
    public function route(): string;

    /**
     * Get active menu.
     */
    public function isActive(): bool;
}
