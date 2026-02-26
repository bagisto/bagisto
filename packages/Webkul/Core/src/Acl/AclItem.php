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
    ) {}
}
