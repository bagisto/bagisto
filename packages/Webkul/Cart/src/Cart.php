<?php

namespace Webkul\Cart;

use Carbon\Carbon;

use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemRepository;

use Webkul\Customer\Repositories\CustomerRepository;

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

    protected $cart;    //cart repository instance

    protected $cartItem;    //cart_item repository instance

    protected $customer;    //customer repository instance

    protected $product;     //product repository instance

    public function __construct(CartRepository $cart,
    CartItemRepository $cartItem,
    CustomerRepository $customer,
    ProductRepository $product) {

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->product = $product;
    }

    /**
     * Create new cart
     * instance with the
     * current item added.
     *
     * @param Integer $id
     * @param Mixed $data
     *
     * @return Mixed
     */
    public function createNewCart($id, $data) {
        $cartData['channel_id'] = core()->getCurrentChannel()->id;

        if(auth()->guard('customer')->check()) {
            $cartData['customer_id'] = auth()->guard('customer')->user()->id;

            $cartData['customer_full_name'] = auth()->guard('customer')->user()->first_name .' '. auth()->guard('customer')->user()->last_name;
        }

        if($cart = $this->cart->create($cartData)) {
            $data['cart_id'] = $cart->id;
            $data['product_id'] = $id;

            if ($data['is_configurable'] == "true") {
                $temp = $data['super_attribute'];

                unset($data['super_attribute']);

                $data['additional'] = json_encode($temp);
            }

            if($result = $this->cartItem->create($data)) {
                session()->put('cart', $cart);

                session()->flash('success', 'Item Added To Cart Successfully');

                return redirect()->back();
            }
        }
        session()->flash('error', 'Some error occured');

        return redirect()->back();
    }

    /**
     * Add Items in a
     * cart with some
     * cart and item
     * details.
     *
     * @param @id
     * @param $data
     *
     * @return Mixed
     */
    public function add($id, $data) {

        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items()->get();

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

                if ($data['is_configurable'] == "true") {
                    $temp = $data['super_attribute'];

                    unset($data['super_attribute']);

                    $data['additional'] = json_encode($temp);
                }

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
     * @param Integer $id
     * @return Mixed
     */
    public function remove($id) {

        dd("Removing Item from Cart");
    }

    /**
     * This function handles
     * when guest has some of
     * cart products and then
     * logs in.
     *
     * @return Redirect
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

                            if($cartItem->product_id == $customerCartItem->product_id) {

                                $customerItemQuantity = $customerCartItem->quantity;

                                $cartItemQuantity = $cartItem->quantity;

                                $customerCartItem->update(['cart_id' => $customerCart->id, 'quantity' => $cartItemQuantity + $customerItemQuantity]);

                                $this->cartItem->delete($cartItem->id);

                                $cartItems->forget($key);
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
}