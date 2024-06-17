<?php

namespace Webkul\Shop\Http\Controllers;

class CartController extends Controller
{
    /**
     * Cart page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        if ($error = request()->input('error')) {
            return view('shop::checkout.cart.index', compact('error'));
        }

        return view('shop::checkout.cart.index');
    }
}
