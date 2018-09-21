<?php

namespace Webkul\Cart;

use Carbon\Carbon;

//Cart repositories
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemRepository;

//Customer repositories
use Webkul\Customer\Repositories\CustomerRepository;

//Product Repository
use Webkul\Product\Repositories\ProductRepository;

use Cookie;

/**
 * Facade for all
 * the methods to be
 * implemented in Cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Cart {

    protected $cart;

    protected $cartItem;

    protected $customer;

    //Cookie expiry limit in minutes
    protected $minutes;

    protected $product;

    public function __construct(CartRepository $cart, CartItemRepository $cartItem, CustomerRepository $customer, $minutes = 150, ProductRepository $product) {

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->minutes = $minutes;

        $this->product = $product;
    }

    /**
     * Create New Cart
     * Cart, Cookie &
     * Session.
     *
     * @return mixed
    */

    public function createNewCart($id, $data) {

        $cartData['channel_id'] = core()->getCurrentChannel()->id;

        if(auth()->guard('customer')->check()) {
            $data['customer_id'] = auth()->guard('customer')->user()->id;

            $cartData['customer_full_name'] = auth()->guard('customer')->first_name .' '. auth()->guard('customer')->last_name;
        }

        if($cart = $this->cart->create($cartData)) {

            $data['product_id'] = $id;

            if($result = $cart->items()->create($data)) {
                session()->put('cart', $cart);

                session()->flash('success', 'Item Added To Cart Successfully');

                return redirect()->back();
            }
        }
        session()->flash('error', 'Some error occured');

        return redirect()->back();
    }

    /*
    handle the after login event for the customers
    when their are pruoducts in the session or cookie
    of the logged in user.
    */

    public function add($id, $data) {

        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items;

            if(isset($cartItems)) {

                foreach($cartItems as $cartItem) {
                    if($cartItem->product_id == $id) {
                        $prevQty = $cartItem->quantity;

                        $newQty = $data['quantity'];

                        $cartItem->update(['quantity' => $prevQty + $newQty]);

                        session()->flash('success', "Product Quantity Successfully Updated");

                        return redirect()->back();
                    }
                }

                $data['cart_id'] = $cart->id;

                $data['product_id'] = $id;

                $cart->items()->create($data);

                session()->flash('success', 'Item Successfully Added To Cart');
            } else {
                if(isset($cart)) {
                    $this->cart->delete($cart->id);
                } else {
                    $this->createNewCart($id, $data);
                }
            }
        } else {
            $this->createNewCart($id, $data);
        }
    }

    /**
     * use detach to remove the
     * current product from cart tables
     *
     * @return Mixed
     */
    public function remove($id) {

        dd("Removing Item from Cart");
    }

    /**
     * Function to handle merge
     * and sync the cookies products
     * with the existing data of cart
     * in the cart tables;
    */

    public function mergeCart() {
        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items;

            $customerCart = $this->cart->findOneByField('customer_id', auth()->guard('customer')->user()->id);

            if(isset($customerCart)) {
                $customerCartItems = $this->cart->items($customerCart['id']);

                if(isset($customerCart)) {
                    foreach($customerCartItems as $customerCartItem) {

                        foreach($cartItems as $key => $cartItem) {

                            if($cartItem->product_id == $customerCartItem->id) {

                                $customerItemQuantity = $customerCartItem->quantity;

                                $cartItemQuantity = $cartItem->quantity;

                                $customerCartItem->update(['cart_id' => $customerCart->id, 'quantity' => $cartItemQuantity + $customerItemQuantity]);

                                $cartItem->destroy();

                                unset($cartItems[$key]);
                            }
                        }
                    }

                    foreach($cartItems as $cartItem) {
                        $cartItem->update(['cart_id' => $customerCart->id]);
                    }

                    $this->cart->delete($cart->id);

                    return redirect()->back();
                }
            } else {
                foreach($cartItems as $cartItem) {
                    $this->cart->update(['customer_id' => auth()->guard('customer')->user()->id], $cart->id);
                }

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Destroys the session
     * maintained for cart
     * on customer logout.
     *
     * @return Mixed
     */
    public function destroyCart() {
        if(session()->has('cart')) {
            session()->forget('cart');

            return redirect()->back();
        }
    }
}