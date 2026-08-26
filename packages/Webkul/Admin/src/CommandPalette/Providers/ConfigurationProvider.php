<?php

namespace Webkul\Admin\CommandPalette\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webkul\Admin\CommandPalette\Aliases;
use Webkul\Admin\CommandPalette\Contracts\Provider;
use Webkul\Admin\CommandPalette\Item;

class ConfigurationProvider implements Provider
{
    /**
     * Permission that owns the whole configuration area.
     */
    const PERMISSION = 'configuration';

    /**
     * Icon shown against every configuration result.
     *
     * The tree carries SVG paths rather than icon classes, so one icon stands for the area.
     */
    const ICON = 'icon-settings';

    /**
     * Navigation entry the whole configuration tree hangs beneath.
     */
    const NODE = 'configuration';

    /**
     * Create a new instance.
     */
    public function __construct(
        protected Aliases $aliases,
    ) {}

    /**
     * Every configuration section, group and setting, if the admin may configure at all.
     */
    public function items(): array
    {
        if (! bouncer()->hasPermission(self::PERMISSION)) {
            return [];
        }

        return $this->walk(system_config()->getItems());
    }

    /**
     * Walk the configuration tree, carrying the trail of ancestor names and the page to open.
     *
     * @return Item[]
     */
    protected function walk(Collection $entries, array $trail = [], ?string $pageKey = null, int $depth = 0): array
    {
        $items = [];

        foreach ($entries as $entry) {
            $name = $this->titleOf($entry);

            if ($name === '') {
                continue;
            }

            $target = $this->targetFor($entry, $pageKey, $depth);

            $items[] = new Item(
                label: $name,
                category: Item::CATEGORY_CONFIGURATION,
                url: $this->urlFor($target),
                path: $this->pathFor($trail),
                icon: self::ICON,
                keywords: $this->keywords($entry),
                children: $this->walk(
                    $this->descendantsOf($entry),
                    [...$trail, $name],
                    $target,
                    $depth + 1,
                ),
                key: method_exists($entry, 'getKey') ? $entry->getKey() : null,
                parent: $depth === 0 ? self::NODE : null,
            );
        }

        return $items;
    }

    /**
     * The key of the page an entry is reached on.
     *
     * It settles at the second level and is inherited by everything below.
     */
    protected function targetFor(mixed $entry, ?string $pageKey, int $depth): string
    {
        if ($depth >= 2) {
            return $pageKey;
        }

        return $entry->getKey();
    }

    /**
     * The children of an entry, or the settings it holds when it has none.
     */
    protected function descendantsOf(mixed $entry): Collection
    {
        if (
            method_exists($entry, 'haveChildren')
            && $entry->haveChildren()
        ) {
            return collect($entry->getChildren());
        }

        if (method_exists($entry, 'getFields')) {
            return collect($entry->getFields());
        }

        return collect();
    }

    /**
     * The translated name of an entry, whichever way it carries one.
     */
    protected function titleOf(mixed $entry): string
    {
        foreach (['getTitle', 'getName'] as $method) {
            if (
                method_exists($entry, $method)
                && ! is_null($value = $entry->$method())
            ) {
                return trans($value);
            }
        }

        return '';
    }

    /**
     * The address of a configuration page.
     */
    protected function urlFor(string $key): string
    {
        return route('admin.configuration.index', Str::replace('.', '/', $key));
    }

    /**
     * The trail an operator reads under a result, led by the area it belongs to.
     */
    protected function pathFor(array $trail): string
    {
        return implode(Item::PATH_SEPARATOR, [trans('admin::app.configuration.index.title'), ...$trail]);
    }

    /**
     * Terms the entry answers to beyond its name, taken from its key.
     *
     * @return string[]
     */
    protected function keywords(mixed $entry): array
    {
        $key = method_exists($entry, 'getKey') ? $entry->getKey() : ($entry->getName() ?? '');

        $segments = array_map(
            fn ($segment) => str_replace('_', ' ', $segment),
            explode('.', (string) $key)
        );

        return array_merge($segments, $this->aliases->for((string) $key));
    }
}
