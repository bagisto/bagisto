<?php

namespace Webkul\Menu;

use Closure;
use Illuminate\Support\Collection;
use Webkul\Menu\Menu\MenuElement;
use Webkul\Menu\Menu\MenuGroup;

class Menu
{
    protected Closure|Collection|array|null $menu = null;

    public function register(Closure|array|Collection|null $data): void
    {
        $this->menu = $data;
    }

    public function all(): Collection
    {
        return $this->prepareMenu(value($this->menu, request()));
    }

    public function hasForceActive(): bool
    {
        return $this->all()->contains(function (MenuElement $item) {
            if($item->isForceActive()) {
                return true;
            }

            if($item instanceof MenuGroup) {
                return $item->items()->contains(fn (MenuElement $child): bool => $child->isForceActive());
            }

            return false;
        });
    }

    public function prepareMenu(Collection|array|null $items = []): Collection
    {
        return collect($items)->filter(function (MenuElement $item): bool {
            if ($item instanceof MenuGroup) {
                $item->setItems(
                    $item->items()->filter(
                        fn (MenuElement $child): bool => $child->isSee(request())
                    )
                );
            }

            return $item->isSee(request());
        });
    }
}