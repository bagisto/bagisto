<?php

namespace Webkul\Customer\Helpers;

class Wishlist
{
    /**
     * Returns wishlist products for current customer.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return boolean
     */
    public function getWishlistProduct($product)
    {
        $wishlist = false;

        if ($customer = auth()->guard('customer')->user()) {
            $wishlist = $customer->wishlist_items->filter(function ($item) use ($product) {
                return $item->channel_id == core()->getCurrentChannel()->id && $item->product_id == $product->product_id;
            })->first();
        }

        if ($wishlist) {
            return $wishlist;
        }

        return null;
    }
}