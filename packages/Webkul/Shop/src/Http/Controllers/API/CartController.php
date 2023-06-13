<?php

namespace Webkul\Shop\Http\Controllers\API;

use Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\CartResource;

class CartController extends APIController
{
    public function __construct(
        protected WishlistRepository $wishlistRepository,
        protected ProductRepository $productRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository
    ) {
    }

    /**
     * Cart.
     */
    public function index(): JsonResource
    {
        $customer = auth()->guard('customer')->user();

        Cart::collectTotals();

        if ($cart = Cart::getCart()) {
            if (! $customer) {
                $cart = $cart->where('is_guest', 1)->get();

                return new JsonResource([
                    'data' => $cart ? CartResource::collection($cart) : null,
                ]);
            } else {
                $cart = $cart->where('customer_id', $customer->id)->get();

                return new JsonResource([
                    'data' => $cart ? CartResource::collection($cart) : null,
                ]);
            }
        }

        return null;
    }

    /**
     * Store items in cart.
     */
    public function store(): JsonResource
    {
        try {
            $customer = auth()->guard('customer')->user();

            $productId = request()->input('product_id');

            $request = request()->all();

            if (! $customer) {
                $request = array_merge($request, [
                    'guest_id'    => 1,
                    'customer_id' => null,
                ]);
            } else {
                $request = array_merge($request, [
                    'guest_id'    => null,
                    'customer_id' => $customer->id,
                ]);
            }

            $cart = Cart::addProduct($productId, $request);

            /**
             * To Do (@devansh-webkul): Need to check this and improve cart facade.
             */
            if (
                is_array($cart)
                && isset($cart['warning'])
            ) {
                return new JsonResource([
                    'message' => $cart['warning'],
                ]);
            }

            if ($cart) {
                if ($customer) {
                    $this->wishlistRepository->deleteWhere([
                        'product_id'  => $productId,
                        'customer_id' => $customer->id,
                    ]);
                }

                return new JsonResource([
                    'data'     => new CartResource(Cart::getCart()),
                    'message'  => trans('shop::app.components.products.item-add-to-cart'),
                ]);
            }
        } catch (\Exception $exception) {
            return new JsonResource([
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Removes the item from the cart if it exists.
     */
    public function destroy(): JsonResource
    {
        Cart::removeItem(request()->input('cart_item_id'));

        return new JsonResource([
            'data'    => new CartResource(Cart::getCart()),
            'message' => trans('shop::app.checkout.cart.item.success-remove'),
        ]);
    }

    /**
     * Updates the quantity of the items present in the cart.
     */
    public function update(): JsonResource
    {
        try {
            Cart::updateItems(request()->input());

            return new JsonResource([
                'data'    => new CartResource(Cart::getCart()),
                'message' => trans('shop::app.checkout.cart.quantity-update'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResource([
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
