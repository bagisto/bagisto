<?php

namespace Webkul\DataGrid;

/**
 * Initial implementation of the action class. Stay tuned for more features coming soon.
 */
class Action
{
    /**
     * Create a column instance.
     */
    public function __construct(
        public string $index,
        public mixed $icon,
        public mixed $title,
        public string $method,
        public mixed $url,
        public mixed $condition = null,
    ) {}

    /**
     * Determine whether this action should be shown for the given record.
     */
    public function isVisible(mixed $record): bool
    {
        if (! $this->condition instanceof \Closure) {
            return true;
        }

        return (bool) ($this->condition)($record);
    }

    /**
     * Convert to an array.
     */
    public function toArray()
    {
        return [
            'index' => $this->index,
            'icon' => $this->icon,
            'title' => $this->title,
            'method' => $this->method,
            'url' => $this->url,
        ];
    }
}
