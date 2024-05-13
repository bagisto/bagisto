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
     * Route of the menu.
     */
    public string $sort;

    /**
     * Route of the icon
     */
    public string $icon;

    /**
     * Contains the current item route.
     */
    public string $current;

    /**
     * Contains the current item key.
     */
    public string $currentKey;

    /**
     * Create a new instance of the controller.
     *
     * This method is commonly used within the controller for instantiating itself
     * and can be used for dependency injection or method chaining.
     *
     * @param  mixed  ...$args  Optional arguments to pass to the controller constructor.
     * @return static An instance of the controller.
     */
    public static function make(...$args)
    {
        $static = new static(...$args);

        return $static;
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
     * Check Menu has group.
     */
    public function hasGroup(): bool
    {
        return $this instanceof MenuGroup;
    }

    /**
     * Check menu is Item.
     */
    public function isItem(): bool
    {
        return ! $this->hasGroup();
    }

    public function isActive()
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
}
