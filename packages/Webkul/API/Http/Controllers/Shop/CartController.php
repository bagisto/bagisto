<?php

namespace Webkul\API\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Auth;
// use Cart;
use Webkul\Checkout\Repositories\CartRepository;

/**
 * Cart controller for the APIs of User Cart
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    protected $customer;

    protected $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;

        if(auth()->guard('customer')->check()) {
            $this->customer = auth()->guard('customer')->user();
        } else {
            return response()->json('Unauthorized', 401);
        }
    }

    public function getAllCart() {
        $carts = $this->customer->carts;

        if($cart->count() > 0) {
            return response()->json($cart, 200);
        } else {
            return response()->json('Cart Empty', 200);
        }
    }

    public function getActiveCart() {
        return $this->customer->cart;
    }
}