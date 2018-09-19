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

    //Cookie expiry limit in minutes
    protected $minutes = 150;

    public function __construct(CartRepository $cart, CartProductRepository $cartProduct, CustomerRepository $customer ,$minutes = 150) {

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartProduct = $cartProduct;

        $this->minutes = $minutes;
    }

    public function guestUnitAdd($id) {

        //empty array for storing the products
        $products = array();

        if(Cookie::has('cart_session_id')) {

            //getting the cart session id from cookie
            $cart_session_id = Cookie::get('cart_session_id');

            //finding current cart instance in the database table.
            $current_cart = $this->cart->findOneByField('session_id', $cart_session_id);

            //check there is any cart or not
            if(isset($current_cart)) {
                $current_cart_id = $current_cart['id'] ?? $current_cart->id;

                $current_cart_session_id = $current_cart['session_id'] ?? $current_cart->session_id;
            } else {
                //if someone deleted then take the flow to the normal
                $this->repairCart($cart_session_id, $id);
            }

            //matching the session id present in the cookie and database are same or not.
            if((session()->get('cart_session_id') == Cookie::get('cart_session_id')) && ($current_cart_session_id == session()->get('cart_session_id'))) {
                $current_cart_products = array();

                $current_cart_products = $this->cart->getProducts($current_cart_id);

                //checking new product coming in the cart is new or previously added item.
                foreach($current_cart_products as $key => $value) {

                    $product_id = $value['id'] ?? $value->id;

                    if($product_id == $id) {
                        //create status code to communicate with session flash
                        session()->flash('error', 'Item Already In Cart');

                        //remove this its temporary
                        dump('Item Already In Cart');

                        return redirect()->back();
                    }
                }

                //cart data being attached to the instace.
                $cart_data = $this->cart->attach($current_cart_id, $id, 1);

                //getting the products after being attached to cart instance
                $cart_products = $this->cart->getProducts($current_cart_id);

                //storing the information in session.
                session()->put('cart_data', [$current_cart, $cart_products]);

                session()->flash('Success', 'Item Added To Cart Successfully');

                dump($cart_products);

                //return the control to the controller
                return redirect()->back();

            } else {
                //repair the cart, will remake the session
                //and add the product in the new cart instance.
                $this->repairCart($cart_session_id, $id);
            }
        } else {
            //function call
            $this->createNewCart($id);
        }
    }

    /*helpers*/

    /**
     * Create New Cart
     * Cart, Cookie &
     * Session.
     *
     * @return mixed
    */

    public function createNewCart($id) {

        $fresh_cart_session_id = session()->getId();

        $data['session_id'] = $fresh_cart_session_id;

        $data['channel_id'] = core()->getCurrentChannel()->id;

        if($cart = $this->cart->create($data)) {

            $this->makeCartSession($fresh_cart_session_id);

            $new_cart_id = $cart->id ?? $cart['id'];

            $cart_product['product_id'] = $id;

            $cart_product['quantity'] = 1;

            $cart_product['cart_id'] = $new_cart_id;

            if($cart_product = $this->cart->attach($new_cart_id, $cart_product['product_id'], $cart_product['quantity'])) {

                session()->put('cart_data', [$cart, $cart_product]);

                session()->flash('success', 'Item Added To Cart Successfully');

                return redirect()->back();
            }
        }
        session()->flash('error', 'Some Error Occured');

        return redirect()->back();
    }

    /**
     * This makes session
     * cart.
     */
    public function makeCartSession($cart_session_id) {

        $fresh_cart_session_id = $cart_session_id;

        Cookie::queue('cart_session_id', $fresh_cart_session_id, $this->minutes);

        session()->put('cart_session_id', $fresh_cart_session_id);
    }


    /**
     * Reset Session and
     * Cookie values
     * and sync database
     * if present.
     *
     * @return mixed
     */
    public function repairCart($cart_session_id ="null", $product_id = 0) {

        if($cart_session_id == session()->get('cart_session_id')) {
            $data['session_id'] = $cart_session_id;

            $data['channel_id'] = core()->getCurrentChannel()->id;

            $cart = $this->cart->create($data);

            $cart_id = $cart['id'] ?? $cart->id;

            $this->cart->attach($cart_id, $product_id, 1);

            session()->flash('success', 'Item Added To Cart Successfully');

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
        if(Cookie::has('session_cart_id')) {
            $products = unserialize(Cookie::get('session_cart_id'));

            foreach($products as $key => $value) {
                if($value == $id) {
                    unset($products[$key]);

                    array_push($products, $id);

                    Cookie::queue('session_cart_id', serialize($products));

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

        if(!auth()->guard('customer')->check()) {
            throw new \Exception('This function is protected for auth customers only.');
        }

        $data['customer_id'] = auth()->guard('customer')->user()->id;

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $customer_cart = $this->cart->findOneByField('customer_id', $data['customer_id']);

        //if there are products already in cart of that customer.
        $customer_cart_id = $customer_cart->id ?? $customer_cart['id'];

        /**
         * Check if their any
         * instance of current
         * customer in the cart
         * table.
         */
        if(isset($customer_cart)) {
            $customer_cart_products = $this->cart->getProducts($customer_cart_id);

            if (isset($customer_cart_products)) {

                foreach ($customer_cart_products as $customer_cart_product) {
                    if($customer_cart_product->id == $id) {
                        dump('Item already exists in cart');

                        session()->flash('error', 'Item already exists in cart');

                        return redirect()->back();

                        //maybe increase the quantity in here
                    }
                }
                //add the product in the cart

                $this->cart->attach($customer_cart_id, $id, 1);

                session()->flash('success', 'Item Added To Cart Successfully');

                return redirect()->back();
            } else {
                $this->cart->destroy($customer_cart_id);

                session()->flash('error', 'Try Adding The Item Again');

                dd('cart instance without any product found, delete it and create a new one for the current product id');

                return redirect()->back();
            }
        } else {
            /**
             * this will work
             * for logged in users
             * and they do not have
             * any cart instance
             * found in the database.
             */

            if($new_cart = $this->cart->create($data)) {
                $new_cart_id = $new_cart->id ?? $new_cart['id'];

                $this->cart->attach($new_cart_id, $id, 1);

                session()->flash('success', 'Item Added To Cart Successfully');

                return redirect()->back();

            } else {
                session()->flash('error', 'Cannot Add Item in Cart');

                return redirect()->back();
            }
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
        //considering cookie as a source of truth.
        if(Cookie::has('cart_session_id')) {
            /*
                Check for previous cart of customer and
                pull products from that cart instance
                and then check for unique products
                and delete the record with session id
                and increase the quantity of the products
                that are added again before deleting the
                guest cart record.
            */

            //To hold the customer ID which is currently logged in
            $customer_id = auth()->guard('customer')->user()->id;

            //having the session id saved in the cart.
            $cart_session_id = Cookie::get('cart_session_id');

            //pull the record from cart table for above session id.
            $guest_cart = $this->cart->findOneByField('session_id', $cart_session_id);

            if(!isset($guest_cart)) {
                dd('Some One Deleted Cart or it wasn\'t there from the start');

                return redirect()->back();
            }

            $guest_cart_products = $this->cart->getProducts($guest_cart->id);

            //check if the current logged in customer is also
            //having any previously saved cart instances.
            $customer_cart = $this->cart->findOneByField('customer_id', $customer_id);

            if(isset($customer_cart)) {
                $customer_cart_products = $this->cart->getProducts($customer_cart->id);

                foreach($guest_cart_products as $key => $guest_cart_product) {

                    foreach($customer_cart_products as $customer_cart_product) {

                        if($guest_cart_product->id == $customer_cart_product->id) {

                            $quantity = $guest_cart_product->toArray()['pivot']['quantity'] + 1;

                            $pivot = $guest_cart_product->toArray()['pivot'];

                            $saveQuantity = $this->cart->updateRelatedForMerge($pivot, 'quantity', $quantity);

                            unset($guest_cart_products[$key]);
                        }
                    }
                }

                //insert the new products here.
                foreach ($guest_cart_products as $key => $guest_cart_product) {
                    $product = $guest_cart_product->toArray();

                    $this->cart->updateRelatedForMerge($product['pivot'], 'cart_id', $customer_cart->id);
                }

                //detach with guest cart records
                $this->cart->detachAndDeleteParent($guest_cart->id);

                Cookie::queue(Cookie::forget('cart_session_id'));

                return redirect()->back();
            } else {
                //this will just update the customer id column in the cart table
                $this->cart->update(['customer_id' => $customer_id], $guest_cart->id);

                Cookie::queue(Cookie::forget('cart_session_id'));

                return redirect()->back();
            }
        }
        return redirect()->back();
    }
}