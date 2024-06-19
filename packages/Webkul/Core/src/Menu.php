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
    private array $items = [];

    /**
     * Config menu.
     */
    private array $configMenu = [];

    /**
     * Contains current item key.
     */
    private string $currentKey = '';

    /**
     * Menu area for admin.
     */
    const ADMIN = 'admin';

    /**
     * Menu area for customer.
     */
    const CUSTOMER = 'customer';

    /**
     * Add a new menu item.
     */
    public function addItem(MenuItem $menuItem): void
    {
        $this->items[] = $menuItem;
    }

    /**
     * Get all menu items.
     */
    public function getItems(?string $area = null): Collection
    {
        if (! $area) {
            throw new \Exception('Area must be provided to get menu items.');
        }

        $configMenu = collect(config("menu.$area"));

        switch ($area) {
            case self::ADMIN:
                $this->configMenu = $configMenu
                    ->filter(fn ($item) => bouncer()->hasPermission($item['key']))
                    ->toArray();
                break;

            case self::CUSTOMER:
                $canShowWishlist = ! (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');

                $this->configMenu = $configMenu
                    ->reject(fn ($item) => $item['key'] == 'account.wishlist' && $canShowWishlist)
                    ->toArray();
                break;

            default:
                $this->configMenu = $configMenu->toArray();

                break;
        }

        if (! $this->items) {
            $this->prepareMenuItems();
        }

        return collect($this->removeUnauthorizedMenuItem())
            ->sortBy('sort');
    }

    /**
     * Prepare menu items.
     */
    private function prepareMenuItems(): void
    {
        $menuWithDotNotation = [];

        foreach ($this->configMenu as $item) {
            if (strpos(request()->url(), route($item['route'])) !== false) {
                $this->currentKey = $item['key'];
            }

            $menuWithDotNotation[$item['key']] = $item;
        }

        $menu = Arr::undot(Arr::dot($menuWithDotNotation));

        foreach ($menu as $menuItemKey => $menuItem) {
            $subMenuItems = $this->processSubMenuItems($menuItem);

            $this->addItem(new MenuItem(
                key: $menuItemKey,
                name: trans($menuItem['name']),
                route: $menuItem['route'],
                sort: $menuItem['sort'],
                icon: $menuItem['icon'],
                children: $subMenuItems,
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
                    children: $subSubMenuItems,
                );
            });
    }

    /**
     * Get current active menu.
     */
    public function getCurrentActiveMenu(?string $area = null): ?MenuItem
    {
        $currentKey = implode('.', array_slice(explode('.', $this->currentKey), 0, 2));

        return $this->findMatchingItem($this->getItems($area), $currentKey);
    }

    /**
     * Finding the matching item.
     */
    private function findMatchingItem($items, $currentKey): ?MenuItem
    {
        foreach ($items as $item) {
            if ($item->key == $currentKey) {
                return $item;
            }

            if ($item->haveChildren()) {
                $matchingChild = $this->findMatchingItem($item->getChildren(), $currentKey);

                if ($matchingChild) {
                    return $matchingChild;
                }
            }
        }

        return null;
    }

    /**
     * Remove unauthorized menu item.
     */
    private function removeUnauthorizedMenuItem(): array
    {
        return collect($this->items)->map(function ($item) {
            $this->removeChildrenUnauthorizedMenuItem($item);

            return $item;
        })->toArray();
    }

    /**
     * Remove unauthorized menuItem's children. This will handle all levels.
     */
    private function removeChildrenUnauthorizedMenuItem(MenuItem &$menuItem): void
    {
        if ($menuItem->haveChildren()) {
            $firstChildrenItem = $menuItem->getChildren()->first();

            $menuItem->route = $firstChildrenItem->getRoute();

            $this->removeChildrenUnauthorizedMenuItem($firstChildrenItem);
        }
    }
}
