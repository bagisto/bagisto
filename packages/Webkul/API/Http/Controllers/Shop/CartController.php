<?php

namespace Webkul\API\Http\Controllers\Shop;

use Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\API\Http\Resources\Checkout\Cart as CartResource;

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
     * @var \Webkul\Checkout\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * CartItemRepository object
     *
     * @var \Webkul\Checkout\Repositories\CartItemRepository
     */
    protected $cartItemRepository;

    /**
     * WishlistRepository object
     *
     * @var \Webkul\Checkout\Repositories\WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * Controller instance
     *
     * @param \Webkul\Checkout\Repositories\CartRepository     $cartRepository
     * @param \Webkul\Checkout\Repositories\CartItemRepository $cartItemRepository
     * @param \Webkul\Checkout\Repositories\WishlistRepository $wishlistRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        WishlistRepository $wishlistRepository
    ) {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        // $this->middleware('auth:' . $this->guard);

        $this->_config = request('_config');

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;

        $this->wishlistRepository = $wishlistRepository;
    }

    /**
     * Get customer cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $customer = auth($this->guard)->user();

        $cart = Cart::getCart();

        return response()->json([
            'data' => $cart ? new CartResource($cart) : null,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id): ?JsonResponse
    {
        if (request()->get('is_buy_now')) {
            Event::dispatch('shop.item.buy-now', $id);
        }

        Event::dispatch('checkout.cart.item.add.before', $id);

        try {
            $result = Cart::addProduct($id, request()->except('_token'));

            if (is_array($result) && isset($result['warning'])) {
                return response()->json([
                    'error' => $result['warning'],
                ], 400);
            }

            if ($customer = auth($this->guard)->user()) {
                $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
            }

            Event::dispatch('checkout.cart.item.add.after', $result);

            Cart::collectTotals();

            $cart = Cart::getCart();

            return response()->json([
                'message' => __('shop::app.checkout.cart.item.success'),
                'data'    => $cart ? new CartResource($cart) : null,
            ]);
        } catch (Exception $e) {
            Log::error('API CartController: ' . $e->getMessage(),
                ['product_id' => $id, 'cart_id' => cart()->getCart() ?? 0]);

            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode()
                ]
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'qty' => 'required|array',
        ]);

        $requestedQuantity = $request->get('qty');

        foreach ($requestedQuantity as $qty) {
            if ($qty <= 0) {
                return response()->json([
                    'message' => trans('shop::app.checkout.cart.quantity.illegal'),
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        foreach ($requestedQuantity as $itemId => $qty) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);

            Event::dispatch('checkout.cart.item.update.before', $itemId);

            Cart::updateItems(['qty' => $requestedQuantity]);

            Event::dispatch('checkout.cart.item.update.after', $item);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.quantity.success'),
            'data'    => $cart ? new CartResource($cart) : null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        Event::dispatch('checkout.cart.delete.before');

        Cart::deActivateCart();

        Event::dispatch('checkout.cart.delete.after');

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.item.success-remove'),
            'data'    => $cart ? new CartResource($cart) : null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyItem($id)
    {
        Event::dispatch('checkout.cart.item.delete.before', $id);

        Cart::removeItem($id);

        Event::dispatch('checkout.cart.item.delete.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.item.success-remove'),
            'data'    => $cart ? new CartResource($cart) : null,
        ]);
    }

    /**
     * Function to move a already added product to wishlist will run only on customer authentication.
     *
     * @param \Webkul\Checkout\Repositories\CartItemRepository $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function moveToWishlist($id)
    {
        Event::dispatch('checkout.cart.item.move-to-wishlist.before', $id);

        Cart::moveToWishlist($id);

        Event::dispatch('checkout.cart.item.move-to-wishlist.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.move-to-wishlist-success'),
            'data'    => $cart ? new CartResource($cart) : null,
        ]);
    }

    /**
     * Apply coupon code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                Cart::setCouponCode($couponCode)->collectTotals();

                if (Cart::getCart()->coupon_code == $couponCode) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('shop::app.checkout.total.success-coupon'),
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.invalid-coupon'),
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.coupon-apply-issue'),
            ]);
        }

    }

    /**
     * Remove coupon code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
        ]);
    }
}