<?php

namespace Webkul\Cart;

use Carbon\Carbon;

//Cart repositories
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartProductRepository;

//Customer repositories
use Webkul\Customer\Repositories\CustomerRepository;

use Cookie;

/**
 * Cart facade for all
 * the methods to be
 * implemented for Cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Cart {

    protected $_config;

    protected $cart;

    protected $cartProduct;

    protected $customer;

    public function __construct(CartRepository $cart, CartProductRepository $cartProduct, CustomerRepository $customer) {

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartProduct = $cartProduct;
    }

    public function guestUnitAdd($id) {

        $products = array();

        $minutes = 10;

        if(Cookie::has('cart_session_id')) {

            $cart_session_id = Cookie::get('cart_session_id');

            $current_cart = $this->cart->findOneByField('session_id', $cart_session_id);

            // dd('Cookie = ',$cart_session_id, 'Session = ', session()->get('cart_session_id'), 'DB = ', $current_cart->session_id);

            if(isset($current_cart)) {
                $current_cart_id = $current_cart['id'] ?? $current_cart->id;

                $current_cart_session_id = $current_cart['session_id'] ?? $current_cart->session_id;
            } else {
                $this->repairCart($cart_session_id, $id);
            }


            if((session()->get('cart_session_id') == Cookie::get('cart_session_id')) && ($current_cart_session_id == session()->get('cart_session_id'))) {
                $current_cart_products = array();

                $current_cart_products = $this->cart->getProducts($current_cart_id);

                foreach($current_cart_products as $key => $value) {

                    $product_id = $value['id'] ?? $value->id;

                    if($product_id == $id) {
                        session()->flash('error', 'Item Already In Cart');

                        dump('Item Already In Cart');

                        return redirect()->back();
                    }
                }

                $cart_data = $this->cart->attach($current_cart_id, $id, 1);

                $cart_products = $this->cart->getProducts($current_cart_id);

                session()->put('cart_data', [$current_cart, $cart_products]);

                session()->flash('Success', 'Item Added In Cart');

                dump($cart_products);

                return redirect()->back();

            } else {
                // throw new \Exception('Error, Many or Few Session discrepancies found.');

                $this->repairCart($cart_session_id, $id);
            }
        } else {
            $this->createNewCart($id);
        }
    }

    /*helpers*/
    public function makeCartSession($to_process) {
        session()->put('cart_session_id', $to_process);

        return session()->get('cart_session_id');
    }

    /**
     * Create New Cart
     * Cart, Cookie &
     * Session.
     *
     * @return mixed
     */

    public function createNewCart($id) {
        $minutes = 120;

        $fresh_cart_session_id = session()->getId();

        Cookie::queue('cart_session_id', $fresh_cart_session_id, $minutes);

        $cart_session_id = $this->makeCartSession($fresh_cart_session_id);

        $data['session_id'] = $fresh_cart_session_id;

        $data['channel_id'] = core()->getCurrentChannel()->id;

        if($cart = $this->cart->create($data)) {

            $new_cart_id = $cart->id ?? $cart['id'];

            $cart_product['product_id'] = $id;

            $cart_product['quantity'] = 1;

            $cart_product['cart_id'] = $new_cart_id;

            if($cart_product = $this->cart->attach($new_cart_id, $cart_product['product_id'], $cart_product['quantity'])) {

                session()->put('cart_data', [$cart, $cart_product]);

                session()->flash('success', 'Product Added To Cart');

                return redirect()->back();
            }
        }
        session()->flash('error', 'Some Error Occured');

        return redirect()->back();
    }

    /**
     * Reset Session and
     * Cookie values
     * and sync database
     * if present.
     *
     * @return mixed
     */

    public function repairCart($cart_session_id, $product_id) {

        if($cart_session_id == session()->get('cart_session_id')) {
            $data['session_id'] = $cart_session_id;

            $data['channel_id'] = core()->getCurrentChannel()->id;

            $cart = $this->cart->create($data);

            $cart_id = $cart['id'] ?? $cart->id;

            $this->cart->attach($cart_id, $product_id, 1);

            session()->flash('success', 'Product Added In Cart');

            return redirect()->back();

        } else {

            Cookie::queue(Cookie::forget('cart_session_id'));

            session()->forget('cart_session_id');

            session()->regenerate();

            session()->flash('error', 'Please Try Adding Product Again.');

            return redirect()->back();
        }
    }

    /**
     * Function for guests
     * user to remove the product
     * in the cart.
     *
     * @return Mixed
     */
    public function guestUnitRemove($id) {

        //remove the products here
        if(Cookie::has('session_c')) {
            $products = unserialize(Cookie::get('session_c'));

            foreach($products as $key => $value) {
                if($value == $id) {
                    unset($products[$key]);

                    array_push($products, $id);

                    Cookie::queue('session_c', serialize($products));

                    return redirect()->back();
                }
            }
        }
    }

    /*
    handle the after login event for the customers
    when their are pruoducts in the session or cookie
    of the logged in user.
    */

    public function add($id) {

        $products = array();

        // $customerLoggedIn = auth()->guard('customer')->check();

        // //customer is authenticated
        // if ($customerLoggedIn) {
            //assuming that there is data in cookie and customer's cart also.

        $data['customer_id'] = auth()->guard('customer')->user()->id;

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $customerCart = $this->cart->findOneByField('customer_id', $data['customer_id']);

        //if there are products already in cart of that customer.
        $customerCartId = $customerCart->id ?? $customerCart['id'];

        $customerCartProducts = $this->cart->getProducts($customerCartId);

        if (isset($customerCartProducts)) {

            foreach ($customerCartProducts as $previousCartProduct) {

                if($previousCartProduct->id == $id) {
                    dd('product already exists in cart');

                    session()->flash('error', 'Product already exists in cart');

                    return redirect()->back();
                }
            }
            //add the product in the cart

            $product['product_id'] = $id;

            $product['quantity'] = 1;

            $product['cart_id'] = $customerCartId;

            $this->cartProduct->create($product);

            return redirect()->back();
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

        if(Cookie::has('cart_session_id')) {

            $cart_session_id = Cookie::get('cart_session_id');

            $current_cart = $this->cart->findOneByField('session_id', $cart_session_id);

            //it is impossible to not have an entry in cart table and cart_products.
            //will later handle the exceoption.
            $current_cart_id = $current_cart['id'] ?? $current_cart->id;

            $current_cart_session_id = $current_cart['session_id'] ?? $current_cart->session_id;

            $current_cart_products = $this->cart->getProducts($current_cart_id);

            $customer_id = auth()->guard('customer')->user()->id; //working

            if($cart_session_id == $current_cart_session_id) {
                $current_cart_products = array();

                $customer_cart = $this->cart->findByField(['customer_id'=> $customer_id]);

                //check previous saved cart of customer.
                if(!$customer_cart->isEmpty()) {

                    $customer_cart_id = $customer_cart->id;

                    $customer_cart_products = $this->cart->getProducts($customer_cart_id);

                    foreach($current_cart_products as $key => $value) {

                        $product_id = $value['id'] ?? $value->id;

                        foreach($current_cart_products as $key => $current_cart_product) {

                            $current_product_id = $current_cart_product['id'] ?? $current_cart_product->id;

                            if($current_product_id == $product_id) {

                                unset($current_cart_products[$key]);
                            }
                        }
                    }


                    foreach($current_cart_products as $current_cart_product) {

                        $current_cart_product_id = $current_cart_product['id'] ?? $current_cart_product->id;

                        $this->cart->attach($current_cart_id, $current_cart_product_id, 1);
                    }

                    $this->cart->update(['customer_id' => $customer_id], $current_cart_id);

                    $customer_cart = $this->cart->findOneByField('customer_id', $customer_id);

                    $customer_cart_id = $customer_cart->id;

                    if($this->cart->getProducts($customer_cart_id) && isset($current_cart_products)) {
                        foreach($current_cart_products as $key => $value) {

                            array_push($cart_products, $current_cart_product);
                        }
                    }

                    session()->put('cart_data', [$customer_cart, $cart_products]);

                    session()->flash('Success', 'Item Added In Cart');

                    dump($cart_products);

                    return redirect()->back();
                } else {

                    $session_id = session()->getId();

                    $customer_id = auth()->guard('customer')->user()->id;

                    $updated_cart = $this->cart->update(['customer_id' => $customer_id, 'session_id' => $session_id], $current_cart_id);

                    $updated_cart_products = $this->cart->getProducts($updated_cart->id);

                    Cookie::queue('cart_session_id', $session_id, 120);

                    session()->put('cart_session_id', $session_id);

                    session('cart_data', [$updated_cart, $updated_cart_products]);

                    return redirect()->back();
                }
            } else {
                throw new \Exception('Error, Session discrepancies found.');

                $this->repairCart($cart_session_id, $id);
            }
        } else {
            throw new \Exception('Nothing found');

            return redirect()->back();
        }
    }
}