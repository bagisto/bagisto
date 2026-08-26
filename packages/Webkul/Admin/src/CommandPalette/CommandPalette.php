<?php

namespace Webkul\Admin\CommandPalette;

use Illuminate\Contracts\Foundation\Application;
use Webkul\Admin\CommandPalette\Contracts\Provider;

class CommandPalette
{
    /**
     * Registered providers, as class names resolved on demand.
     *
     * @var string[]
     */
    protected array $providers = [];

    /**
     * Create a new instance.
     */
    public function __construct(
        protected Application $app,
        protected RecordSources $recordSources,
    ) {}

    /**
     * Register a provider of searchable items.
     */
    public function register(string $provider): void
    {
        if (in_array($provider, $this->providers)) {
            return;
        }

        $this->providers[] = $provider;
    }

    /**
     * The registered providers.
     *
     * @return string[]
     */
    public function providers(): array
    {
        return $this->providers;
    }

    /**
     * The tree the operator walks, ready for the palette.
     *
     * Grafting what belongs to a node onto that node happens here, not in a provider.
     */
    public function items(): array
    {
        $roots = [];

        $orphans = [];

        foreach ($this->providers as $provider) {
            $resolved = $this->app->make($provider);

            if (! $resolved instanceof Provider) {
                continue;
            }

            foreach ($resolved->items() as $item) {
                $item->parent
                    ? $orphans[] = $item
                    : $roots[] = $item;
            }
        }

        $index = $this->indexByKey($roots);

        foreach ($this->recordSources->all() as $source) {
            $this->graft($index, $source['node'] ?? null, $this->collectionFor($source), $orphans);
        }

        foreach ($orphans as $orphan) {
            $this->graft($index, $orphan->parent, $orphan, $roots);
        }

        return array_map(fn (Item $item) => $item->toArray(), $roots);
    }

    /**
     * The entry that opens a source's records as a level of their own.
     */
    protected function collectionFor(array $source): Item
    {
        return new Item(
            label: $source['collection_title'],
            category: Item::CATEGORY_PAGE,
            url: $source['index'],
            icon: $source['icon'] ?? null,
            keywords: ['all', $source['key']],
            source: $source['key'],
        );
    }

    /**
     * Put an item under the node it names, or leave it where it can still be found.
     *
     * @param  array<string, Item>  $index
     * @param  Item[]  $fallback
     */
    protected function graft(array $index, ?string $key, Item $item, array &$fallback): void
    {
        if (
            $key
            && isset($index[$key])
        ) {
            $parent = $index[$key];

            $item->path ??= $this->trailUnder($parent);

            $parent->addChild($item);

            return;
        }

        $fallback[] = $item;
    }

    /**
     * The trail an item grafted onto a node reads under, which is that node's own trail
     * with the node itself on the end.
     */
    protected function trailUnder(Item $parent): string
    {
        return $parent->path
            ? $parent->path.Item::PATH_SEPARATOR.$parent->label
            : $parent->label;
    }

    /**
     * Every node of the tree that carries a key, so grafts can find their parent.
     *
     * @param  Item[]  $items
     * @return array<string, Item>
     */
    protected function indexByKey(array $items): array
    {
        $index = [];

        foreach ($items as $item) {
            if ($item->key) {
                $index[$item->key] = $item;
            }

            $index += $this->indexByKey($item->children);
        }

        return $index;
    }
}
