<?php

namespace Webkul\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

//Cart repositories
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemRepository;

//Customer repositories
use Webkul\Customer\Repositories\CustomerRepository;

use Cart;
use Cookie;

/**
 * Cart controller for the customer
 * and guest users for adding and
 * removing the products in the
 * cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    protected $cart;

    protected $cartItem;

    protected $customer;

    public function __construct(CartRepository $cart, CartItemRepository $cartItem, CustomerRepository $customer) {

        $this->middleware('customer')->except(['add', 'remove', 'test']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;
    }

    /**
     * Function for guests
     * user to add the product
     * in the cart.
     *
     * @return Mixed
     */

    public function add($id) {
        $data = request()->input();
        if(auth()->guard('customer')->check()) {
            Cart::add($id, $data);
        } else {
            Cart::guestUnitAdd($id, $data);
        }

        return redirect()->back();
    }

    public function remove($id) {

        if(auth()->guard('customer')->check()) {
            Cart::remove($id);
        } else {
            Cart::guestUnitRemove($id);
        }

        return redirect()->back();
    }

    /**
     * This is a test for
     * relationship existence
     * from cart item to product
     *
     * @return Array
     */
    public function test() {
        $cartItems = $this->cart->items(75);

        $products = array();
        foreach($cartItems as $cartItem) {
            $cartItemId = $cartItem->id;

            $this->cart->updateItem(75, $cartItemId, 'quantity', $cartItem->quantity+1);

            array_push($products, ['product_id' => $this->cartItem->getProduct($cartItemId), 'quantity' => $cartItem->quantity]);
        }

        dd($products);
        return response()->json($products, 200);
    }
}