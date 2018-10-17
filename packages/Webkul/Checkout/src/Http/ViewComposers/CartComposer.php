<?php

namespace Webkul\Checkout\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Collection;

use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;

//Product Image Helper Class
use Webkul\Product\Helpers\ProductImage;

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
        
    }
}
