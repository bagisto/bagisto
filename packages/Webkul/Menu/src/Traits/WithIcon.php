<?php

namespace Webkul\Menu\Traits;

use Illuminate\Contracts\View\View;

trait WithIcon
{
    /**
     * Icon.
     */
    protected ?string $icon = null;

    /**
     * Icon.
     */
    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get Icon.
     */
    public function getIcon(): View|string 
    {
        return $this->iconValue();
    }

    /**
     * Icon value.
     */
    public function iconValue(): string
    {
        return $this->icon ?? '';
    }
}
