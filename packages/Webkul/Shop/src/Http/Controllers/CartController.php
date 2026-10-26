<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Cart page.
     *
     * @return View
     */
    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        return view('shop::checkout.cart.index');
    }
}
