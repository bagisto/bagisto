<?php

namespace Webkul\Menu\Menu;

use Closure;
use Webkul\Menu\Attributes\Icon as AttributesIcon;
use Webkul\Menu\Contracts\MenuFiller;
use Webkul\Menu\Support\Attributes as SupportAttributes;

class MenuItem extends MenuElement
{
    /**
     * Create the new instance of menu item.
     * 
     * @return void
     */
    final public function __construct(
        Closure|string $label,
        protected Closure|MenuFiller|string $filler,
        string $icon = null,
        Closure|bool $blank = false
    ) {
        $this->setLabel($label);

        if ($icon) {
            $this->icon($icon);
        }

        if ($filler instanceof MenuFiller) {
            $this->resolveMenuFiller($filler);
        } else {
            $this->setUrl($filler);
        }

        $this->blank($blank);
    }

    /**
     * Resolve the menu filler.
     */
    protected function resolveMenuFiller(MenuFiller $filler): void
    {
        $this->setUrl(fn (): string => $filler->url());

        $icon = SupportAttributes::for($filler)
            ->attribute(AttributesIcon::class)
            ->attributeProperty('icon')
            ->get();

        if (! is_null($icon) && $this->iconValue() === '') {
            $this->icon($icon);
        }
    }

    /**
     * Get filler.
     */
    public function getFiller(): MenuFiller|Closure|string
    {
        return $this->filler;
    }
}
