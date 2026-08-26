<?php

namespace Webkul\Admin\CommandPalette;

class Item
{
    /**
     * What separates the steps of the trail shown under a result.
     *
     * A single angle quote rather than an arrow, because an arrow sits on the baseline
     * and reads as though it has dropped below the words either side of it.
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
     * Create a new item.
     *
     * @param  string[]  $keywords  Extra terms this item answers to, beyond its own label.
     */
    public function __construct(
        public string $label,
        public string $url,
        public string $category,
        public ?string $path = null,
        public ?string $icon = null,
        public array $keywords = [],
    ) {}

    /**
     * Represent the item the way the palette consumes it.
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'url' => $this->url,
            'category' => $this->category,
            'path' => $this->path,
            'icon' => $this->icon,
            'keywords' => array_values(array_unique(array_filter($this->keywords))),
        ];
    }
}
