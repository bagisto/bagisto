<?php

namespace Webkul\Admin\CommandPalette\Contracts;

use Webkul\Admin\CommandPalette\Item;

interface Provider
{
    /**
     * The items this provider contributes for the signed in admin.
     *
     * Only what the admin is permitted to reach may be returned; the palette does not
     * filter afterwards.
     *
     * @return Item[]
     */
    public function items(): array;
}
