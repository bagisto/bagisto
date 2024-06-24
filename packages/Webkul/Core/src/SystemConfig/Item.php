<?php

namespace Webkul\Core\SystemConfig;

use Illuminate\Support\Collection;

class Item
{
    /**
     * Create a new Item instance.
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
    ) {}

    /**
     * Get name of config item.
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * Format options.
     */
    private function formatOptions($options)
    {
        return is_array($options) ? $options : (is_string($options) ? $options : []);
    }

    /**
     * Get fields of config item.
     */
    public function getFields(): Collection
    {
        return collect($this->fields)->map(function ($field) {
            return new ItemField(
                item_key: $this->key,
                name: $field['name'],
                title: $field['title'],
                info: $field['info'] ?? null,
                type: $field['type'],
                depends: $field['depends'] ?? null,
                path: $field['path'] ?? null,
                validation: $field['validation'] ?? null,
                default: $field['default'] ?? null,
                channel_based: $field['channel_based'] ?? null,
                locale_based: $field['locale_based'] ?? null,
                options: $this->formatOptions($field['options'] ?? null),
                is_visible: true,
            );
        });
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
     * Get Icon.
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
