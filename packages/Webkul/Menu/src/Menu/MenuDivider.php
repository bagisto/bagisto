<?php

namespace Webkul\Menu\Menu;

use Closure;

class MenuDivider extends MenuElement
{
    /**
     * Create the new instance of the class.
     *
     * @param string $label
     * @return voids
     */
    final public function __construct(Closure|string $label = '')
    {
        $this->setLabel($label);
    }
}
