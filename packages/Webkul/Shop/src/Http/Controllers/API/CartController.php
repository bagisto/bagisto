<?php

namespace Webkul\Shop\Http\Controllers\API;

use Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;

class CartController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        Cart::collectTotals();

        return new JsonResource([
            'data'     => Cart::getCart(),
            'message'  => trans('shop::app.components.products.item-add-to-cart'),
        ]);
    }

    /**
     * Store items in cart.
     */
    public function store(): JsonResource
    {
        try {
            $productId = request()->input('product_id');

            $cart = Cart::addProduct($productId, request()->all());

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
                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere([
                        'product_id'  => $productId,
                        'customer_id' => $customer->id,
                    ]);
                }

                return new JsonResource([
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
            'data'      => Cart::getCart(),
            'messege' => trans('shop::app.checkout.cart.item.success-remove'),
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
                'data'      => Cart::getCart(),
                'message' => trans('shop::app.checkout.cart.quantity-update'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResource([
                'message'   => $exception->getMessage(),
            ]);
        }
    }
}
