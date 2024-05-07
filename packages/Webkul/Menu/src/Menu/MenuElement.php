<?php

namespace Webkul\Menu\Menu;

use Closure;
use Webkul\Menu\Traits\HasCanSee;
use Webkul\Menu\Traits\Makeable;
use Webkul\Menu\Traits\WithIcon;
use Webkul\Menu\Traits\WithLabel;
use Throwable;
use Webkul\Menu\Contracts\MenuFiller;
use Webkul\Menu\Support\Condition;

abstract class MenuElement
{
    use Makeable;
    use WithIcon;
    use HasCanSee;
    use WithLabel;

    protected Closure|string|null $url = null;

    protected Closure|bool $blank = false;

    protected Closure|bool|null $forceActive = null;

    public function forceActive(Closure|bool $forceActive): static
    {
        $this->forceActive = $forceActive;

        return $this;
    }

    public function isForceActive(): bool
    {
        return ! is_null($this->forceActive);
    }

    public function isGroup(): bool
    {
        return $this instanceof MenuGroup;
    }

    public function isItem(): bool
    {
        return $this instanceof MenuItem;
    }

    public function setUrl(string|Closure|null $url, Closure|bool $blank = false): static
    {
        $this->url = $url;

        $this->blank($blank);

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function url(): string
    {
        return value($this->url) ?? '';
    }

    /**
     * @throws Throwable
     */
    public function isActive(): bool
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

        if(menu()->hasForceActive()) {
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

        // if ($this->url() === moonshineRouter()->home()) {
        //     return request()->fullUrlIs($this->url());
        // }

        return request()->fullUrlIs($this->url() . '*');
    }

    public function blank(Closure|bool $blankCondition = true): static
    {
        $this->blank = Condition::boolean($blankCondition, true);

        return $this;
    }

    public function isBlank(): bool
    {
        return $this->blank;
    }
}
