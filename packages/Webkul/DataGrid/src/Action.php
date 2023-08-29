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
        public string $icon,
        public string $title,
        public string $method,
        public mixed $url,
    ) {
    }
}
