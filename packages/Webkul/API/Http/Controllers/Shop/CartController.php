<?php

namespace Webkul\API\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository as CartItem;
use Webkul\API\Http\Controllers\Shop\Presenter as Presenter;
use Auth;
use Cart;

/**
 * Cart controller for the APIs of User Cart
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    protected $customer;
    protected $cart;
    protected $cartItem;

    public function __construct(CartRepository $cart, CartItem $cartItem)
    {
        $this->cart = $cart;

        $this->cartItem = $cartItem;
    }

    /**
     * Function to get the current cart instance for customer or guest
     *
     * @return Response array && Collection Cart
     */
    public function get() {
        $cart = Cart::getCart();

        if ($cart == null || $cart == 'null') {
            return response()->json(['message' => 'empty', 'items' => null]);
        }

        return response()->json(['message' => 'success', 'items' => $cart]);
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return Mixed
     */
    public function add($id) {
        $result = Cart::add($id, request()->all());

        if ($result) {
            Cart::collectTotals();

            return response()->json(['message' => 'successful', 'items' => Cart::getCart()->items]);
        } else {
            return response()->json(['message' => 'failed', 'items' => Cart::getCart()->items]);
        }
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param integer $itemId
     */
    public function remove($itemId) {
        $result = Cart::removeItem($itemId);

        Cart::collectTotals();

        return response()->json(['message' => $result, 'items' => Cart::getCart()]);
    }

    /**
     * Before checkout starts or full details on the cart
     *
     * @return response json
     */
    public function onePage() {
        $cart = Cart::getCart();

        if ($cart == null || $cart == 'null') {
            return response()->json(['message' => 'empty', 'items' => null]);
        }

        $presenter = new Presenter();
        $summary = $presenter->onePagePresenter($cart);

        return response()->json(['message' => 'success', 'items' => $cart->items, 'summary' => $summary]);
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return response JSON
     */
    public function updateOnePage() {
        $request = request()->except('_token');

        foreach ($request['qty'] as $id => $quantity) {
            if ($quantity <= 0) {
                return response()->json(['message' => 'Illegal Quantity', 'status' => 'error']);
            }
        }

        foreach ($request['qty'] as $key => $value) {
            $item = $this->cartItem->findOneByField('id', $key);

            $data['quantity'] = $value;

            $result = Cart::updateItem($item->product_id, $data, $key);

            unset($item);
            unset($data);
        }

        Cart::collectTotals();

        return response()->json(['message' => 'success', 'items' => Cart::getCart()]);
    }
}