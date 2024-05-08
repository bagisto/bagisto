<?php

namespace Webkul\Menu\Contracts;

interface MenuFiller
{
    public function route(): string;

    public function isActive(): bool;
}
