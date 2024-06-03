<?php

namespace Webkul\Core\SystemConfig;

use Illuminate\Support\Collection;

class SystemConfigItem
{
    /**
     * Create a new AclItem instance.
     */
    public function __construct(
        public Collection $children,
        public ?array $fields = null,
        public ?string $icon = null,
        public ?string $info = null,
        public string $key,
        public string $name,
        public ?string $route = null,
        public ?int $sort = null
    ) {
    }

    /**
     * Get name of menu item.
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function getFields(): ?array
    {
        return $this->fields ?? null;
    }


    public function getType()
    {
        return $this->fields['type'] ?? null;
    }

    public function getPath()
    {
        return $this->fields['path'] ?? null;
    }

    /**
     * Get name of menu item.
     */
    public function getInfo(): ?string
    {
        return $this->info ?? '';
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
     * Get the key of the menu item.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get Icon
     */
    public function getIcon(): ?string
    {
        return $this->icon ?? null;
    }

    /**
     * Check weather menu item have children or not.
     */
    public function haveChildren(): bool
    {
        return $this->children->isNotEmpty();
    }

    /**
     * Get children of menu item.
     */
    public function getChildren(): Collection
    {
        if (! $this->haveChildren()) {
            return collect();
        }

        return $this->children;
    }

    /**
     * Check weather menu item is active or not.
     */
    public function isActive(): bool
    {
        if (request()->fullUrlIs($this->getUrl().'*')) {
            return true;
        }

        if ($this->haveChildren()) {
            foreach ($this->getChildren() as $child) {
                if ($child->isActive()) {
                    return true;
                }
            }
        }

        return false;
    }
}
