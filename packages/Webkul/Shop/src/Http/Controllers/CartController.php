<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;

class CartController extends Controller
{
    /**
     * Cart page. A basket parked for an unfinished buy now purchase is merged back in here, so
     * the shopper looking at their cart sees it rather than the buy now product alone.
     *
     * @return View
     */
    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        Cart::mergeParkedCart();

        return view('shop::checkout.cart.index');
    }
}
