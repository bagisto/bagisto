<?php

namespace Webkul\Menu\Menu;

use Webkul\Menu\Menu;

class MenuItem extends Menu
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
        public array|null $menuGroup = null,
    ) {
    }
}