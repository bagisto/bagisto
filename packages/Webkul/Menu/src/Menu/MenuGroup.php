<?php

namespace Webkul\Menu\Menu;

use Closure;
use Illuminate\Support\Collection;

/**
 * @method static static make(Closure|string $label, iterable $items, string|null $icon = null)
 */
class MenuGroup extends MenuElement
{
    public function __construct(
        Closure|string $label,
        protected iterable $items = [],
        string $icon = null,
    ) {
        $this->setLabel($label);

        if ($icon) {
            $this->icon($icon);
        }
    }

    public function setItems(iterable $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function items(): Collection
    {
        return collect($this->items);
    }
}
