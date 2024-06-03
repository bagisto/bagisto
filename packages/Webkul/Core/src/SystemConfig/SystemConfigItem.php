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
        public ?array $fields,
        public ?string $icon,
        public ?string $info,
        public string $key,
        public string $name,
        public ?string $route = null,
        public ?int $sort = null
    ) {
    }

    /**
     * Get name of config item.
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * Get name of config item.
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * Get current route.
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * Get the url of the config item.
     */
    public function getUrl(): string
    {
        return route($this->getRoute());
    }

    /**
     * Get the key of the config item.
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
        return $this->icon;
    }

    /**
     * Check weather config item have children or not.
     */
    public function haveChildren(): bool
    {
        return $this->children->isNotEmpty();
    }

    /**
     * Get children of config item.
     */
    public function getChildren(): Collection
    {
        if (! $this->haveChildren()) {
            return collect();
        }

        return $this->children;
    }
}
