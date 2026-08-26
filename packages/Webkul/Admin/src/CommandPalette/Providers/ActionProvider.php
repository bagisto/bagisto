<?php

namespace Webkul\Admin\CommandPalette\Providers;

use Webkul\Admin\CommandPalette\Contracts\Provider;
use Webkul\Admin\CommandPalette\Item;

class ActionProvider implements Provider
{
    /**
     * Where the declared actions are read from.
     */
    const CONFIG_KEY = 'command_palette.actions';

    /**
     * The declared actions the signed in admin is permitted to start.
     *
     * An action naming a `parent` is grafted onto that node rather than standing alone.
     */
    public function items(): array
    {
        $items = [];

        foreach (config(self::CONFIG_KEY, []) as $action) {
            if (
                ! empty($action['permission'])
                && ! bouncer()->hasPermission($action['permission'])
            ) {
                continue;
            }

            if (! $url = $this->urlFor($action)) {
                continue;
            }

            $items[] = new Item(
                label: trans($action['title']),
                category: Item::CATEGORY_ACTION,
                url: $url,
                path: isset($action['path']) ? trans($action['path']) : null,
                icon: $action['icon'] ?? null,
                keywords: $action['keywords'] ?? [],
                parent: $action['parent'] ?? null,
            );
        }

        return $items;
    }

    /**
     * The address an action leads to, or nothing when its route is not registered.
     */
    protected function urlFor(array $action): ?string
    {
        if (empty($action['route'])) {
            return null;
        }

        try {
            return route($action['route'], $action['params'] ?? []);
        } catch (\Throwable) {
            return null;
        }
    }
}
