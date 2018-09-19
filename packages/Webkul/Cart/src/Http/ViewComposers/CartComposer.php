<?php

namespace Webkul\Cart\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Collection;

use Webkul\Cart\Repositories\CartRepository;

use Cookie;
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
    public function __construct(CartRepository $cart) {
        $this->cart = $cart;
    }

    public function compose(View $view) {
        if(auth()->guard('customer')->check()) {
            $cart = $this->cart->findOneByField('customer_id', auth()->guard('customer')->user()->id);

            $cart_products = $this->cart->getProducts($cart['id']);

            // dd($cart_products);

            $view->with('cart', $cart_products);

        } else {
            if(Cookie::has('cart_session_id')) {
                $cart = $this->cart->findOneByField('session_id', Cookie::get('cart_session_id'));

                $cart_products = $this->cart->getProducts($cart['id']);

                $view->with('cart', $cart_products);
            }
        }
    }
}
