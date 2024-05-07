<?php

namespace Webkul\Menu\Menu;

use Closure;
use Webkul\Menu\Attributes\Icon as AttributesIcon;
use Webkul\Menu\Contracts\MenuFiller;
use Webkul\Menu\Support\Attributes as SupportAttributes;

class MenuItem extends MenuElement
{
    protected ?Closure $badge = null;

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

    protected function resolveMenuFiller(MenuFiller $filler): void
    {
        $this->setUrl(fn (): string => $filler->url());

        $icon = SupportAttributes::for($filler)
            ->attribute(AttributesIcon::class)
            ->attributeProperty('icon')
            ->get();

        if (method_exists($filler, 'getBadge')) {
            $this->badge(fn () => $filler->getBadge());
        }

        if (! is_null($icon) && $this->iconValue() === '') {
            $this->icon($icon);
        }
    }

    public function getFiller(): MenuFiller|Closure|string
    {
        return $this->filler;
    }

    public function badge(Closure $callback): static
    {
        $this->badge = $callback;

        return $this;
    }

    public function hasBadge(): bool
    {
        return is_callable($this->badge);
    }

    public function getBadge(): mixed
    {
        return value($this->badge);
    }
}
