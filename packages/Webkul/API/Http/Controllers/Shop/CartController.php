<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\API\Http\Resources\Checkout\Cart as CartResource;
use Cart;

/**
 * Cart controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * CartRepository object
     *
     * @var Object
     */
    protected $cartRepository;

    /**
     * CartItemRepository object
     *
     * @var Object
     */
    protected $cartItemRepository;

    /**
     * Controller instance
     *
     * @param Webkul\Checkout\Repositories\CartRepository     $cartRepository
     * @param Webkul\Checkout\Repositories\CartItemRepository $cartItemRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);
        
        // $this->middleware('auth:' . $this->guard);
        
        $this->_config = request('_config');

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Get customer cart
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $customer = auth($this->guard)->user();

        $cart = Cart::getCart();

        return response()->json([
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        Event::fire('checkout.cart.item.add.before', $id);

        $result = Cart::add($id, request()->except('_token'));

        if (! $result) {
            $message = session()->get('warning') ?? session()->get('error');
            return response()->json([
                    'error' => session()->get('warning')
                ], 400);
        }

        Event::fire('checkout.cart.item.add.after', $result);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Product added to cart successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        foreach (request()->get('qty') as$qty) {
            if ($qty <= 0) {
                return response()->json([
                        'message' => trans('shop::app.checkout.cart.quantity.illegal')
                    ], 401);
            }
        }

        foreach (request()->get('qty') as $itemId => $qty) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);

            Event::fire('checkout.cart.item.update.before', $itemId);

            Cart::updateItem($item->product_id, ['quantity' => $qty], $itemId);

            Event::fire('checkout.cart.item.update.after', $item);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart updated successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        Event::fire('checkout.cart.delete.before');
        
        Cart::deActivateCart();

        Event::fire('checkout.cart.delete.after');

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart removed successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyItem($id)
    {
        Event::fire('checkout.cart.item.delete.before', $id);

        Cart::removeItem($id);

        Event::fire('checkout.cart.item.delete.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart removed successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Function to move a already added product to wishlist
     * will run only on customer authentication.
     *
     * @param instance cartItem $id
     */
    public function moveToWishlist($id)
    {
        Event::fire('checkout.cart.item.move-to-wishlist.before', $id);

        Cart::moveToWishlist($id);

        Event::fire('checkout.cart.item.move-to-wishlist.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart item moved to wishlist successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }
}