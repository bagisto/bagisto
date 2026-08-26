<?php

namespace Webkul\Admin\CommandPalette;

class Item
{
    /**
     * What separates the steps of the trail shown under a result.
     */
    const PATH_SEPARATOR = ' › ';

    /**
     * Somewhere in the admin the operator can navigate to.
     */
    const CATEGORY_PAGE = 'pages';

    /**
     * A setting, or the page holding it.
     */
    const CATEGORY_CONFIGURATION = 'configuration';

    /**
     * Something the operator can start doing.
     */
    const CATEGORY_ACTION = 'actions';

    /**
     * Selecting it goes somewhere.
     */
    const TYPE_NAVIGATE = 'navigate';

    /**
     * Selecting it opens the level beneath it.
     */
    const TYPE_BRANCH = 'branch';

    /**
     * Selecting it opens a level whose entries are fetched as they are needed.
     */
    const TYPE_COLLECTION = 'collection';

    /**
     * Create a new item.
     *
     * @param  string[]  $keywords  Extra terms this item answers to, beyond its own label.
     * @param  self[]  $children  The level this item opens, if it opens one.
     */
    public function __construct(
        public string $label,
        public string $category,
        public ?string $url = null,
        public ?string $path = null,
        public ?string $icon = null,
        public array $keywords = [],
        public array $children = [],
        public ?string $key = null,
        public ?string $parent = null,
        public ?string $source = null,
    ) {}

    /**
     * What selecting this item does.
     *
     * A configuration page is somewhere to go, not a level: its settings share its address.
     */
    public function type(): string
    {
        if ($this->source) {
            return self::TYPE_COLLECTION;
        }

        if (! $this->children) {
            return self::TYPE_NAVIGATE;
        }

        if ($this->category !== self::CATEGORY_CONFIGURATION) {
            return self::TYPE_BRANCH;
        }

        return $this->leadsBeyond() ? self::TYPE_BRANCH : self::TYPE_NAVIGATE;
    }

    /**
     * Add an entry to the level this item opens.
     */
    public function addChild(self $child): void
    {
        $this->children[] = $child;
    }

    /**
     * Represent the item the way the palette consumes it.
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'url' => $this->url,
            'category' => $this->category,
            'type' => $this->type(),
            'path' => $this->path,
            'icon' => $this->icon,
            'source' => $this->source,
            'keywords' => array_values(array_unique(array_filter($this->keywords))),
            'children' => array_map(fn (self $child) => $child->toArray(), $this->children),
        ];
    }

    /**
     * Whether anything beneath this item goes somewhere it does not go itself.
     *
     * A collection counts, since what it opens is records rather than an address.
     */
    protected function leadsBeyond(): bool
    {
        foreach ($this->children as $child) {
            if (
                $child->source
                || $child->url !== $this->url
                || $child->leadsBeyond()
            ) {
                return true;
            }
        }

        return false;
    }
}
