<?php

namespace Webkul\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

//Cart repositories
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemRepository;

//Product Repository
use Webkul\Product\Repositories\ProductRepository;

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

    protected $product;

    public function __construct(CartRepository $cart, CartItemRepository $cartItem, CustomerRepository $customer, ProductRepository $product) {

        $this->middleware('customer')->except(['add', 'remove', 'test']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->product = $product;
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
        // dd($data);
        if(!isset($data['is_configurable']) || !isset($data['product']) ||!isset($data['quantity'])) {
            session()->flash('error', 'Cannot Product Due to User\'s miscreancy in system\'s integrity');

            return redirect()->back();
        }

        if($data['is_configurable'] == "false") {
            $data['price'] = $this->product->findOneByField('id', $data['product'])->price;
        } else {
            $id = $data['selected_configurable_option'];

            $data['price'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->price;
        }

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
        $data['product_id'] = 60;
        $data['quantity'] = 7;
        $data['price'] = 1600.00;
        dd($this->cart->createItem(80, $data));
    }
}