<?php

namespace Webkul\Menu\Menu;

use Closure;
use Webkul\Menu\Contracts\MenuFiller;
use Webkul\Menu\Support\Condition;
use Webkul\Menu\Traits\HasCanSee;
use Webkul\Menu\Traits\Makeable;
use Webkul\Menu\Traits\WithIcon;
use Webkul\Menu\Traits\WithLabel;

abstract class MenuElement
{
    use HasCanSee, Makeable, WithIcon, WithLabel;

    /**
     * Route.
     */
    protected Closure|string|null $route = null;

    /**
     * Blank.
     */
    protected Closure|bool $blank = false;

    /**
     * forceActive.
     */
    protected Closure|bool|null $forceActive = null;

    /**
     * Force active.
     */
    public function forceActive(Closure|bool $forceActive): static
    {
        $this->forceActive = $forceActive;

        return $this;
    }

    /**
     * Is force active.
     */
    public function isForceActive(): bool
    {
        return ! is_null($this->forceActive);
    }

    /**
     * Is Group.
     */
    public function isGroup(): bool
    {
        return $this instanceof MenuGroup;
    }

    /**
     * Is Item.
     */
    public function isItem(): bool
    {
        return $this instanceof MenuItem;
    }

    /**
     * Set Route.
     */
    public function setRoute(string|Closure|null $route, Closure|bool $blank = false): static
    {
        $this->route = $route;

        $this->blank($blank);

        return $this;
    }

    /**
     * Route.
     */
    public function route(): string
    {
        return value($this->route) ?? '';
    }

    /**
     * Url of the route.
     */
    public function url(): string
    {
        if (empty($this->route())) {
            return '';
        }

        return route($this->route());
    }

    /**
     * Is active.
     *
     * @return void
     */
    public function isActive()
    {
        if ($this->isForceActive() && value($this->forceActive) === true) {
            return true;
        }

        if ($this instanceof MenuGroup) {
            foreach ($this->items() as $item) {
                if ($item->isActive()) {
                    return true;
                }
            }

            return false;
        }

        if (menu()->hasForceActive()) {
            return false;
        }

        if (! $this->isItem()) {
            return false;
        }

        $filler = $this instanceof MenuItem
            ? $this->getFiller()
            : null;

        if ($filler instanceof MenuFiller) {
            return $filler->isActive();
        }

        $path = parse_url($this->url(), PHP_URL_PATH) ?? '/';
        $host = parse_url($this->url(), PHP_URL_HOST) ?? '';

        if ($path === '/' && request()->host() === $host) {
            return request()->path() === $path;
        }

        return request()->fullUrlIs(route($this->route()).'*');
    }

    /**
     * Redirect to another page.
     */
    public function blank(Closure|bool $blankCondition = true): static
    {
        $this->blank = Condition::boolean($blankCondition, true);

        return $this;
    }

    /**
     * Is Blank.
     */
    public function isBlank(): bool
    {
        return $this->blank;
    }
}
