<?php

namespace Webkul\Core\Acl;

use Illuminate\Support\Collection;

class AclItem
{
    /**
     * Create a new AclItem instance.
     */
    public function __construct(
        public string $key,
        public string $name,
        public string $route,
        public int $sort,
        public Collection $children,
    ) {
    }

    /**
     * Get name of acl item.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get current route.
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * Get the url of the acl item.
     */
    public function getUrl(): string
    {
        return route($this->getRoute());
    }

    /**
     * Check weather acl item have children or not.
     */
    public function haveChildren(): bool
    {
        return $this->children->isNotEmpty();
    }

    /**
     * Get children of acl item.
     */
    public function getChildren(): Collection
    {
        if (! $this->haveChildren()) {
            return collect([]);
        }

        return $this->children;
    }
}
