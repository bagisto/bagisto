<?php

namespace Webkul\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Cart\Repositories\CartRepository as Cart;
use Webkul\Cart\Repositories\CartProductRepository as cartProduct;
use Webkul\Customer\Repositories\CustomerRepository;
use Session;
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

    public function __construct(Cart $cart, cartProduct $cartProduct, CustomerRepository $customer) {
        $this->middleware('customer')->except(['add', 'remove']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartProduct = $cartProduct;
    }

    public function add($id) {
        $minutes = 5;

        $products = array();

        $customerLoggedIn = auth()->guard('customer')->check();

        if($customerLoggedIn) {
            //customer is authenticated

            //assuming that there is data in cookie and customers' cart also.

            $data['customer_id'] = auth()->guard('customer')->user()->id;

            $data['channel_id'] = core()->getCurrentChannel()->id;

            $cookieProducts = unserialize(Cookie::get('session_c'));

            $cartCustomer = $this->cart->findOneByField('customer_id', $data['customer_id']);

            //case when cookie has products and customer also have data in cart
            if(isset($cartCustomer)  && isset($cartCookie)) {
                //there are already items in cart of customer

                //for unsetting the cookie for the cart
                // Cookie::queue(Cookie::forget('session_c'));

                //check if there is any repetition in the products
                $cartId = $cartCustomer->id ?? $cartCustomer['id'];

                $previousCartProducts = $this->cart->withProducts($cartId);

                //if there are products already in cart of that customer
                if(isset($previousCartProducts)) {

                    foreach($previousCartProducts as $previousCartProduct) {

                        dump($previousCartProducts);

                        foreach($cookieProducts as $key => $cookieProduct) {

                            if($previousCartProduct->id == $cookieProduct) {

                                unset($cookieProducts[$key]);
                            }
                        }
                    }
                }

                /*if the above block executes it will remove duplicates
                else product in cookies will be stored in the database.*/

                foreach($cookieProducts as $key => $cookieProduct) {

                    $product['product_id'] = $cookieProduct;

                    $product['quantity'] = 1;

                    $product['cart_id'] = $cartId;

                    $this->cartProduct->create($product);
                }

                dump('Added the new items into the customer cart.');

                return redirect()->back();

            } else if(isset($cartCustomer)  && !isset($cartCookie)){
                //case when there is no data in customer's cart

                $cartId = $cartCustomer->id ?? $cartCustomer['id'];

                $previousCartProducts = $this->cart->withProducts($cartId);

                foreach($previousCartProducts as $previousCartProduct) {

                    if($previousCartProduct->id == $id) {

                        dd('product already in the cart::AUTH');

                        return redirect()->back();
                    }
                }
                $product['product_id'] = $id;

                $product['quantity'] = 1;

                $product['cart_id'] = $cartId;

                $this->cartProduct->create($product);

                dump('new item added in the cart');

                return redirect()->back();

            } else if(!isset($cartCustomer)  && isset($cartCookie)) {

                $products = unserialize(Cookie::get('session_c'));

                if ($cart = $this->cart->create($data)) {

                    foreach($products as $product) {

                       $product['product_id'] = $id;

                       $product['quantity'] = 1;

                       $product['cart_id'] = $cart;

                       $this->cartProduct->create($product);
                    }
                    return redirect()->back();

                } else {
                    session()->flash('error', 'Cannot Add Your Items To Cart');

                    return redirect()->back();
                }
            }

        } else {
            //case when the customer is unauthenticated
            if(Cookie::has('session_c')) {
                $products = unserialize(Cookie::get('session_c'));

                foreach($products as $key => $value) {
                    if($value == $id) {
                        dd('product already in the cart');
                        return redirect()->back();
                    }
                }
                array_push($products, $id);

                Cookie::queue('session_c', serialize($products));

                return redirect()->back();

            } else {
                array_push($products, $id);

                Cookie::queue('session_c', serialize($products), $minutes);

                return redirect()->back();
            }

        }

        // if(Cookie::has('session_id')) {
        //     //add the item to the cart if not already there

        //     //Cookie::queue(\Cookie::forget('myCookie'));
        //     $match_prev_session = $this->cart->findOneByField('session_id', Cookie::get('session_id'));

        //     if(isset($match_prev_session)) {

        //         $cart_id = $match_prev_session->id; //cart ID from the existing session values from database and cookie

        //         $products = $this->cart->getProducts($match_prev_session->id); //getting the products associated with that cart id.

        //         $existingProductsLookup = $this->cartProduct->model->where(['product_id' => $id, 'cart_id' => $cart_id]);

        //         dd($existingProductsLookup);

        //         //no action to be taken if the product already exists
        //         if(isset($existingProductsLookup)) {
        //             return redirect()->back();
        //         } else {
        //             //insert the new products using cartProduct when product is new
        //             $cartProduct['product_id'] = $id;

        //             $cartProduct['quantity'] = 1;

        //             $cartProduct['cart_id'] = $cart_id;

        //             $this->cartProduct->create($cartProduct);

        //             return redirect()->back();
        //         }
        //     } else {
        //         return response()->json(['Cookie Already There', Cookie::get('session_id'), $id], 200);
        //     }
        // } else {
        //     /*
        //         make the entry in database
        //         and show the database entry
        //         in cart
        //     */
        //     Cookie::queue('session_id', session()->getId(), $minutes);

        //     $data['session_id'] = session()->getId();

        //     $data['channel_id'] = core()->getCurrentChannel()->id;

        //     if($cart = $this->cart->create($data)) {
        //         $product['product_id'] = $id;

        //         $product['quantity'] = 1;

        //         $product['cart_id'] = $cart;

        //         $this->cartProduct->create($product);

        //         return redirect()->back();
        //     }
        // }
    }

    public function remove($id) {
        dd("Removing Item from Cart");
    }
}