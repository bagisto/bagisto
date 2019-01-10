<?php

namespace Webkul\API\Http\Controllers\Shop;

/**
 * Presenter Class for cart and mini cart(if implemented)
 */
class Presenter {

    /**
     * presenter method will handle the cart and collect all the necessary data to be displayed on the onepage cart
     */
    public function onePagePresenter($cart) {
        $cartSummary['grand_total'] = $cart->grand_total;
        $cartSummary['tax_total'] = $cart->tax_total;
        $cartSummary['total_items'] = $cart->items_count;
        $cartSummary['total_items_qty'] = $cart->items_qty;
        $cartSummary['total_items_qty'] = $cart->items_qty;

        return $cartSummary;
    }
}