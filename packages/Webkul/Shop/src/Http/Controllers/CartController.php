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
        return view('shop::checkout.cart.index');
    }
}
