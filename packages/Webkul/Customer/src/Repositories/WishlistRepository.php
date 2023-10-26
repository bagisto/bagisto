<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\Wishlist;

class WishlistRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Wishlist::class;
    }
}
