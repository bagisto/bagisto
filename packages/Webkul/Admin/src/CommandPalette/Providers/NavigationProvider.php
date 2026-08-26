<?php

namespace Webkul\Admin\CommandPalette\Providers;

use Illuminate\Support\Collection;
use Webkul\Admin\CommandPalette\Aliases;
use Webkul\Admin\CommandPalette\Contracts\Provider;
use Webkul\Admin\CommandPalette\Item;
use Webkul\Core\Facades\Menu;
use Webkul\Core\Menu as BaseMenu;
use Webkul\Core\Menu\MenuItem;

class NavigationProvider implements Provider
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected Aliases $aliases,
    ) {}

    /**
     * The admin menu, as the tree the operator walks.
     *
     * The menu arrives already filtered by permission, so nothing is checked again here.
     */
    public function items(): array
    {
        return $this->walk(Menu::getItems(BaseMenu::ADMIN));
    }

    /**
     * Turn menu entries into items, keeping the shape of the menu.
     *
     * @return Item[]
     */
    protected function walk(Collection $menuItems, array $trail = []): array
    {
        $items = [];

        foreach ($menuItems as $menuItem) {
            $children = $this->walk($menuItem->getChildren(), [...$trail, $menuItem->getName()]);

            $item = new Item(
                label: $menuItem->getName(),
                category: Item::CATEGORY_PAGE,
                url: $menuItem->getUrl(),
                path: $trail ? implode(Item::PATH_SEPARATOR, $trail) : null,
                icon: $menuItem->getIcon() ?: null,
                keywords: $this->keywords($menuItem),
                children: $children,
                key: $menuItem->getKey(),
            );

            $items[] = $item;
        }

        return $items;
    }

    /**
     * Terms the entry answers to beyond its label, taken from its key and its aliases.
     *
     * @return string[]
     */
    protected function keywords(MenuItem $menuItem): array
    {
        $segments = array_map(
            fn ($segment) => str_replace('_', ' ', $segment),
            explode('.', $menuItem->getKey())
        );

        return array_merge($segments, $this->aliases->for($menuItem->getKey()));
    }
}
