<?php

namespace Webkul\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

//Cart repositories
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartProductRepository;

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

    protected $cartProduct;

    protected $customer;

    public function __construct(CartRepository $cart, CartProductRepository $cartProduct, CustomerRepository $customer) {

        $this->middleware('customer')->except(['add', 'remove', 'test']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartProduct = $cartProduct;
    }

    /**
     * Function for guests
     * user to add the product
     * in the cart.
     *
     * @return Mixed
     */

    public function add($id) {

        if(auth()->guard('customer')->check()) {
            Cart::add($id);
        } else {
            Cart::guestUnitAdd($id);
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

    // public function test() {
    //     $cookie = Cookie::get('cart_session_id');

    //     $cart = $this->cart->findOneByField('session_id', $cookie);

    //     $cart_products = $this->cart->getProducts($cart->id);

    //     foreach($cart_products as $cart_product) {
    //         $quantity = $cart_product->toArray()['pivot']['quantity'] + 1;

    //         $pivot = $cart_product->toArray()['pivot'];

    //         $saveQuantity = $this->cart->saveRelated($pivot, 'quantity', $quantity+1);
    //     }
    //     dd('done');
    // }
}