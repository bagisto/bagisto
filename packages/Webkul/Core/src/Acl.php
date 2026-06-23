<?php

namespace Webkul\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Webkul\Core\Acl\AclItem;

class Acl
{
    /**
     * acl items.
     */
    protected array $items = [];

    /**
     * Add a new acl item.
     */
    public function addItem(AclItem $aclItem): void
    {
        $this->items[] = $aclItem;
    }

    /**
     * Get all acl items.
     */
    public function getItems(): Collection
    {
        if (! $this->items) {
            $this->prepareAclItems();
        }

        return collect($this->items)
            ->sortBy('sort');
    }

    /**
     * Acl Config.
     */
    private function getAclConfig(): array
    {
        static $aclConfig;

        if ($aclConfig) {
            return $aclConfig;
        }

        $aclConfig = config('acl');

        return $aclConfig;
    }

    /**
     * Get all roles.
     */
    public function getRoles(): Collection
    {
        static $roles;

        if ($roles) {
            return $roles;
        }

        $roles = collect($this->getAclConfig())->flatMap(fn ($role) => collect(Arr::wrap($role['route']))
            ->mapWithKeys(fn ($routeName) => [$routeName => $role['key']]));

        return $roles;
    }

    /**
     * Prepare acl items.
     */
    private function prepareAclItems(): void
    {
        $aclWithDotNotation = [];

        foreach ($this->getAclConfig() as $item) {
            $aclWithDotNotation[$item['key']] = $item;
        }

        $acl = Arr::undot(Arr::dot($aclWithDotNotation));

        foreach ($acl as $aclItemKey => $aclItem) {
            $subAclItems = $this->processSubAclItems($aclItem);

            $this->addItem(new AclItem(
                key: $aclItemKey,
                name: trans($aclItem['name']),
                route: Arr::wrap($aclItem['route'])[0] ?? '',
                sort: $aclItem['sort'],
                children: $subAclItems,
            ));
        }
    }

    /**
     * Process sub acl items.
     */
    private function processSubAclItems($aclItem): Collection
    {
        return collect($aclItem)
            ->sortBy('sort')
            ->filter(fn ($value) => is_array($value) && isset($value['key']))
            ->map(function ($subAclItem) {
                $subSubAclItems = $this->processSubAclItems($subAclItem);

                return new AclItem(
                    key: $subAclItem['key'],
                    name: trans($subAclItem['name']),
                    route: Arr::wrap($subAclItem['route'])[0] ?? '',
                    sort: $subAclItem['sort'],
                    children: $subSubAclItems,
                );
            });
    }
}
