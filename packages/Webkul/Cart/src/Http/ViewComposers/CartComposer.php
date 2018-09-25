<?php

namespace Webkul\Cart\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Collection;

use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemRepository;

//Product Image Helper Class
use Webkul\Product\Product\ProductImage;

use Cart;

/**
 * cart List Composer on Navigation Menu
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartComposer
{

    /**
     * The cart implementation
     * for shop bundle's navigation
     * menu
     */
    protected $cart;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function __construct(CartRepository $cart, CartItemRepository $cartItem, ProductImage $productImage) {
        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->productImage = $productImage;
    }

    public function compose(View $view) {
        if(auth()->guard('customer')->check()) {
            $cart = $this->cart->findOneByField('customer_id', auth()->guard('customer')->user()->id);

            if(isset($cart)) {
                $cart = $this->cart->findOneByField('id', 144);

                $cartItems = $this->cart->items($cart['id']);

                $products = array();

                foreach($cartItems as $cartItem) {
                    $image = $this->productImage->getGalleryImages($cartItem->product);

                    if(isset($image[0]['small_image_url'])) {
                        $products[$cartItem->product->id] = [$cartItem->product->name, $cartItem->price, $image[0]['small_image_url'], $cartItem->quantity];
                    }
                    else {
                        $products[$cartItem->product->id] = [$cartItem->product->name, $cartItem->price, 'null', $cartItem->quantity];
                    }

                }
                session()->put('cart', $cart);

                $view->with('cart', $products);
            }
        } else {
            if(session()->has('cart')) {
                $cart = session()->get('cart');

                if(isset($cart)) {
                    $cart = $this->cart->findOneByField('id', 144);

                    $cartItems = $this->cart->items($cart['id']);

                    $products = array();

                    foreach($cartItems as $cartItem) {
                        $image = $this->productImage->getGalleryImages($cartItem->product);

                        if(isset($image[0]['small_image_url'])) {
                            $products[$cartItem->product->id] = [$cartItem->product->name, $cartItem->price, $image[0]['small_image_url'], $cartItem->quantity];
                        }
                        else {
                            $products[$cartItem->product->id] = [$cartItem->product->name, $cartItem->price, 'null', $cartItem->quantity];
                        }

                    }

                    $view->with('cart', $products);
                }
            }
        }
    }
}
