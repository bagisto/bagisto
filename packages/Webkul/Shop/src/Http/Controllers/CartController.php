<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Helpers\ProductImage;
use Webkul\Product\Helpers\View as ProductView;
use Cart;

/**
 * Cart controller for the customer and guest users for adding and
 * removing the products in the cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{

    /**
     * Protected Variables that holds instances of the repository classes.
     *
     * @param Array $_config
     * @param $cart
     * @param $cartItem
     * @param $customer
     * @param $product
     * @param $productImage
     * @param $productView
     */
    protected $_config;

    protected $cart;

    protected $cartItem;

    protected $customer;

    protected $product;

    protected $productView;

    public function __construct(
        CartRepository $cart,
        CartItemRepository $cartItem,
        CustomerRepository $customer,
        ProductRepository $product,
        ProductImage $productImage,
        ProductView $productView
    ) {

        // $this->middleware('customer')->except(['add', 'remove', 'test']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->product = $product;

        $this->productImage = $productImage;

        $this->productView = $productView;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return Mixed
     */
    public function index() {
        return view($this->_config['view'])->with('cart', Cart::getCart());
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return Mixed
     */

    public function add($id) {
        $data = request()->input();

        Cart::add($id, $data);

        Cart::collectTotals();

        return redirect()->back();
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param integer $itemId
     */
    public function remove($itemId) {
        Cart::removeItem($itemId);

        Cart::collectTotals();

        return redirect()->back();
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return response
     */
    public function updateBeforeCheckout() {
        $data = request()->except('_token');

        foreach($data['qty'] as $id => $quantity) {
            if($quantity <= 0) {
                session()->flash('warning', trans('shop::app.checkout.cart.quantity.illegal'));

                return redirect()->back();
            }
        }

        Cart::update($data);

        Cart::collectTotals();

        return redirect()->back();
    }

    /**
     * Add the configurable product
     * to the cart.
     *
     * @return response
     */
    public function addconfigurable($slug) {
        session()->flash('warning', trans('shop::app.checkout.cart.add-config-warning'));
        return redirect()->route('shop.products.index', $slug);
    }

    public function test($id) {
        $result = Cart::proceedForBuyNow($id);

        if(!$result) {
            return redirect()->back();
        }
    }
}