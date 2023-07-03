<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shop\Http\Resources\CartResource;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

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

        $cart = Cart::getCart();

        return new JsonResource([
            'data' => $cart ? new CartResource($cart) : null,
        ]);
    }

    /**
     * Store items in cart.
     */
    public function store(): JsonResource
    {
        try {
            $product = $this->productRepository->with('parent')->find(request()->input('product_id'));

            $cart = Cart::addProduct($product->id, request()->all());

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
                        'product_id'  => $product->id,
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
                'redirect_uri' => route('shop.productOrCategory.index', $product->product->url_key),
                'message'      => $exception->getMessage(),
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

    /**
     * Apply coupon to the cart.
     */
    public function storeCoupon()
    {
        $couponCode = request()->input('code');

        try {
            if (strlen($couponCode)) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $couponCode);

                if (! $coupon) {
                    return (new JsonResource([
                        'data'     => new CartResource(Cart::getCart()),
                        'message'  => trans('Coupon not found.'),
                    ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                if ($coupon->cart_rule->status) {
                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return (new JsonResource([
                            'data'     => new CartResource(Cart::getCart()),
                            'message'  => trans('shop::app.checkout.cart.coupon-already-applied'),
                        ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    Cart::setCouponCode($couponCode)->collectTotals();

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return new JsonResource([
                            'data'     => new CartResource(Cart::getCart()),
                            'message'  => trans('shop::app.checkout.cart.coupon.success-apply'),
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            return (new JsonResource([
                'data'    => new CartResource(Cart::getCart()),
                'message' => trans('shop::app.checkout.cart.coupon.error'),
            ]))->response()->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove applied coupon from the cart.
     */
    public function destroyCoupon(): JsonResource
    {
        Cart::removeCouponCode()->collectTotals();

        return new JsonResource([
            'data'     => new CartResource(Cart::getCart()),
            'message'  => trans('shop::app.checkout.cart.coupon.remove'),
        ]);
    }
}
