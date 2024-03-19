<?php

namespace Webkul\Admin\Http\Controllers\Customers\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\WishlistItemResource;
use Webkul\Customer\Repositories\WishlistRepository;

class WishlistController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected WishlistRepository $wishlistRepository)
    {
    }

    /**
     * Returns the compare items of the customer.
     */
    public function items(int $id): JsonResource
    {
        $wishlistItems = $this->wishlistRepository
            ->with('product')
            ->where('customer_id', $id)
            ->get();

        return WishlistItemResource::collection($wishlistItems);
    }
}
