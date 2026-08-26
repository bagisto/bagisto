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
     * Every admin menu entry the signed in admin may reach.
     *
     * The menu arrives already filtered by permission, so nothing is checked again here.
     */
    /**
     * Create a new instance.
     */
    public function __construct(
        protected Aliases $aliases,
    ) {}

    public function items(): array
    {
        return $this->walk(Menu::getItems(BaseMenu::ADMIN));
    }

    /**
     * Flatten the menu tree, carrying the trail of ancestor names as the path.
     *
     * @return Item[]
     */
    protected function walk(Collection $menuItems, array $trail = []): array
    {
        $items = [];

        foreach ($menuItems as $menuItem) {
            $items[] = new Item(
                label: $menuItem->getName(),
                url: $menuItem->getUrl(),
                category: Item::CATEGORY_PAGE,
                path: $trail ? implode(Item::PATH_SEPARATOR, $trail) : null,
                icon: $menuItem->getIcon() ?: null,
                keywords: $this->keywords($menuItem),
            );

            if ($menuItem->getChildren()->isNotEmpty()) {
                $items = array_merge(
                    $items,
                    $this->walk($menuItem->getChildren(), [...$trail, $menuItem->getName()])
                );
            }
        }

        return $items;
    }

    /**
     * Terms the entry answers to beyond its label, taken from its key.
     *
     * A key such as `catalog.products` lends both `catalog` and `products`, so an
     * operator finds the page by the words the codebase uses for it.
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
