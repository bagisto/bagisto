<?php

namespace Webkul\Menu\Menu;

abstract class MenuElement
{
    /**
     * Key of the menu
     */
    public string $key;

    /**
     * Name of the menu
     */
    public string $name;

    /**
     * Route of the menu.
     */
    public string $route;

    /**
     * Sort the menu.
     */
    public string $sort;

    /**
     * Icon of the menu.
     */
    public string $icon;

    /**
     * Make the new instance of the class.
     */
    public static function make(mixed ...$args): static
    {
        return new static(...$args);
    }

    /**
     * Get the route of the menu.
     */
    public function route(): string
    {
        return $this->route;
    }

    /**
     * Get the url of the menu route.
     */
    public function url(): string
    {
        return route($this->route());
    }

    /**
     * Check menu has group.
     */
    public function hasGroup(): bool
    {
        return $this instanceof MenuGroup;
    }

    /**
     * Check menu is item.
     */
    public function isItem(): bool
    {
        return ! $this->hasGroup();
    }

    /**
     * Get the current menu active status.
     */
    public function isActive(): bool
    {
        if ($this instanceof MenuGroup) {
            foreach ($this->menuItems as $item) {
                if ($item->isActive()) {
                    return true;
                }
            }

            return false;
        }

        return request()->fullUrlIs($this->url().'*');
    }

    public function getCurrentActiveItems()
    {
        if ($this instanceof MenuGroup) {
            foreach ($this->menuItems as $item) {
                if ($item->isActive()) {
                    return $item;
                }
            }
        }

        return $this;
    }
}
