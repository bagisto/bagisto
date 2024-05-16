<?php

namespace Webkul\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Webkul\Core\Menu\MenuItem;

class Menu
{
    /**
     * Menu items.
     */
    protected array $menuItems = [];

    /**
     * Add a new menu item.
     */
    public function add(MenuItem $menuItem): void
    {
        $this->menuItems[] = $menuItem;
    }

    /**
     * Get all menu items.
     */
    public function getItems(): Collection
    {
        if (! $this->menuItems) {
            $this->prepareMenuItems();
        }

        return collect($this->menuItems)
            ->sortBy('sort');
    }

    /**
     * Prepare menu items.
     */
    private function prepareMenuItems(): void
    {
        $menuWithDotNotation = [];

        foreach (config('menu.admin') as $item) {
            if (! bouncer()->hasPermission($item['key'])) {
                continue;
            }

            $menuWithDotNotation[$item['key']] = $item;
        }

        $menu = Arr::undot(Arr::dot($menuWithDotNotation));

        foreach ($menu as $menuItemKey => $menuItem) {
            $subMenuItems = $this->processSubMenuItems($menuItem);

            $this->add(new MenuItem(
                key: $menuItemKey,
                name: trans($menuItem['name']),
                route: $menuItem['route'],
                sort: $menuItem['sort'],
                icon: $menuItem['icon'],
                menuItems: $subMenuItems
            ));
        }
    }

    /**
     * Process sub menu items.
     */
    private function processSubMenuItems($menuItem): Collection
    {
        return collect($menuItem)
            ->sortBy('sort')
            ->filter(fn ($value) => is_array($value))
            ->map(function ($subMenuItem) {
                $subSubMenuItems = $this->processSubMenuItems($subMenuItem);

                return new MenuItem(
                    key: $subMenuItem['key'],
                    name: trans($subMenuItem['name']),
                    route: $subMenuItem['route'],
                    sort: $subMenuItem['sort'],
                    icon: $subMenuItem['icon'],
                    menuItems: $subSubMenuItems
                );
            });
    }
}
