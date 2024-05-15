<?php

namespace Webkul\Core\Menu;

use Illuminate\Support\Collection;

class MenuItem
{
    /**
     * Create a new MenuItem instance.
     *
     * @return void
     */
    public function __construct(
        public string $key,
        public string $name,
        public string $route,
        public int $sort,
        public string $icon,
        public ?Collection $menuItems,
    ) {
    }

    /**
     * Get name of menu item.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the icon of menu item.
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Get current route.
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * Get the url of the menu item.
     */
    public function getUrl(): string
    {
        return route($this->getRoute());
    }

    /**
     * Check weather menu item have children or not.
     */
    public function haveItem(): bool
    {
        if ($this->menuItems) {
            return $this->menuItems->isNotEmpty();
        }

        return false;
    }

    /**
     * Get children of menu item.
     */
    public function getChildren(): ?Collection
    {
        if ($this->haveItem()) {
            return $this->menuItems;
        }

        return null;
    }

    /**
     * Check weather menu item is active or not.
     */
    public function isActive(): bool
    {
        if (request()->routeIs($this->getRoute())) {
            return true;
        }

        if ($this->haveItem()) {
            foreach ($this->getChildren() as $child) {
                if ($child->isActive()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get active menu item.
     */
    public function getActiveItem(): ?MenuItem
    {
        if ($this->haveItem()) {
            foreach ($this->menuItems as $item) {
                if ($item->isActive()) {
                    return $item;
                }
            }
        }

        return $this;
    }
}
