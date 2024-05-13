<?php

namespace Webkul\Menu\Menu;

class MenuItem extends MenuElement
{
    /**
     * Create the new instance of the class
     *
     * @return void
     */
    public function __construct(
        public string $key,
        public string $name,
        public string $route,
        public string $sort,
        public string $icon,
        public ?array $menuGroup = null,
    ) {
    }
}
