<?php

namespace Webkul\Menu\Menu;

use Closure;
use Illuminate\Support\Collection;

class MenuGroup extends MenuElement
{
    /**
     * Create a new menu group.
     *
     * @return void
     */
    public function __construct(
        Closure|string $label,
        protected iterable $items = [],
        ?string $icon = null,
    ) {
        $this->setLabel($label);

        if ($icon) {
            $this->icon($icon);
        }
    }

    /**
     * Set items.
     */
    public function setItems(iterable $items): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get all group items.
     */
    public function items(): Collection
    {
        return collect($this->items);
    }
}
