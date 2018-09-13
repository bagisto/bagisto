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

        if(Cookie::get('current_session_id')) {

            $cart_session_id = Cookie::get('cart_session_id');

            $cart = $this->cart->getOneByField('session_id', $cart_session_id);

            $cartId = $cart->id ?? $cart['id'];

            $products = $this->getProducts($id);

            foreach($products as $key => $value) {
                if($value == $id) {
                    dd('product already in the cart');

                    return redirect()->back();
                }
            }

            if($this->cartProduct->create($id)) {
                session()->flash('Success', 'Product Added To Cart');

                return redirect()->back();
            }
            return redirect()->back();

        } else {
            Cookie::queue('cart_session_id', session()->id(), $minutes);

            $data['session_id'] = Cookie::get('cart_session_id');

            $data['channel_id'] = core()->getCurrentChannel()->id;

            if($this->cart->create($data)) {
                if($this->cartProduct->create($product)) {
                    session()->flash('Success', 'Product Added To Cart');
                }
            }
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
        // }

        //     //case when cookie has products and customer also have data in cart tables
        //     if(isset($customerCart)  && isset($cartCookie)) {
        //         //for unsetting the cookie for the cart uncomment after all done
        //         // Cookie::queue(Cookie::forget('session_c'));

        //         //check if there is any repetition in the products
        //         $customerCartId = $customerCart->id ?? $customerCart['id'];

        //         //to check if the customer is also having some products saved in the pivot
        //         //for the products.
        //         $customerCartProducts = $this->cart->getProducts($customerCartId);

        //         //if there are products already in cart of that customer
        //         if(isset($customerCartProducts)) {

        //             foreach($customerCartProducts as $previousCartProduct) {

        //                 dump($customerCartProducts);

        //                 foreach($cookieProducts as $key => $cookieProduct) {

        //                     if($previousCartProduct->id == $cookieProduct) {

        //                         unset($cookieProducts[$key]);
        //                     }
        //                 }
        //             }
        //         }

        //         /*if the above block executes it will remove duplicates
        //         else product in cookies will be stored in the database.*/

        //         foreach($cookieProducts as $key => $cookieProduct) {

        //             $product['product_id'] = $cookieProduct;

        //             $product['quantity'] = 1;

        //             $product['cart_id'] = $customerCartId;

        //             $this->cartProduct->create($product);
        //         }

        //         dump('Products in the cart synced.');

        //         return redirect()->back();

        //     } else if(isset($customerCart)  && !isset($cartCookie)) {
        //         //case when there is no data in guest's cart

        //         $customerCartId = $customerCart->id ?? $customerCart['id'];

        //         $customerCartProducts = $this->cart->getProducts($customerCartId);

        //         foreach($customerCartProducts as $previousCartProduct) {

        //             if($previousCartProduct->id == $id) {

        //                 dd('product already in the cart::AUTH');

        //                 return redirect()->back();
        //             }
        //         }
        //         $product['product_id'] = $id;

        //         $product['quantity'] = 1;

        //         $product['cart_id'] = $customerCartId;

        //         $this->cartProduct->create($product);

        //         dump('new item added in the cart');

        //         return redirect()->back();

        //     } else if(!isset($customerCart)  && isset($cartCookie)) {

        //         $products = unserialize(Cookie::get('session_c'));

        //         if ($cart = $this->cart->create($data)) {

        //             foreach($products as $product) {

        //                $product['product_id'] = $id;

        //                $product['quantity'] = 1;

        //                $product['cart_id'] = $cart;

        //                $this->cartProduct->create($product);
        //             }
        //             return redirect()->back();

        //         } else {
        //             session()->flash('error', 'Cannot Add Your Items To Cart');

        //             return redirect()->back();
        //         }
        //     }
        // }
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
    public function handleMerge() {

        $cart_session_id = Cookie::get('cart_session_id');

        $cart = $this->cart->findOneByField('session_id', $cart_session_id);

        $cartId = $cart->id ?? $cart['id'];

        $currentCartProducts = $this->cart->getProducts($cartId);

        $data['customer_id'] = auth()->guard('customer')->user()->id;

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $customerCart = $this->cart->findOneByField('customer_id', $data['customer_id']);

        if(isset($customerCart)) {

            $customerCartId = $customerCart->id ?? $customerCart['id'];

            $customerCartProducts = $this->cart->getProducts($customerCartId);

            if(isset($customerCartProducts)) {

                foreach($customerCartProducts as $previousCartProduct) {

                    foreach($currentCartProducts as $key => $currentCartProduct) {

                        if($previousCartProduct->id == $currentCartProduct) {

                            unset($currentCartProducts[$key]);
                        }
                    }
                }
            }

            /*
                if the above block executes it will remove duplicates
                else product in cookies will be stored in the database.
            */
            $this->cart->findOneByField('session_id', $cart_session_id)->update($data['customer_id']);

            foreach($currentCartProducts as $key => $currentCartProduct) {

                $product['product_id'] = $currentCartProduct;

                $product['quantity'] = 1; //initialize the qty from the params passed from post

                $product['cart_id'] = $customerCartId;

                $this->cartProduct->create($product);
            }

            //forget the cart cookie that holds the cart session id
            Cookie::queue(Cookie::forget('cart_session_id'));
            return redirect()->back();
        } else {

            if($cart = $this->cart->create($data)) {

                foreach($currentCartProducts as $currentCartProduct) {

                    $product['product_id'] = $currentCartProduct;

                    $product['quantity'] = 1;

                    $product['cart_id'] = $cart->id;

                    $this->cartProduct->create($product);
                }
            }
            //forget the Cookie
            Cookie::queue(Cookie::forget('session_c'));

            return redirect()->back();
        }
    }
}