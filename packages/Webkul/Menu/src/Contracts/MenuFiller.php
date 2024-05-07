<?php

namespace Webkul\Menu\Contracts;

interface MenuFiller
{
    public function url(): string;

    public function isActive(): bool;
}
