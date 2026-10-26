<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Product\Exceptions\InsufficientProductInventoryException;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Shop\Http\Resources\CartResource;
use Webkul\Shop\Http\Resources\ProductResource;

class CartController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository
    ) {}

    /**
     * Cart. The shopper's basket, untouched by any buy now purchase, which runs on its own
     * isolated cart.
     */
    public function index(): JsonResource
    {
        if (! Cart::getCart()) {
            return new JsonResource(['data' => null]);
        }

        Cart::collectTotals();

        $response = [
            'data' => ($cart = Cart::getCart()) ? new CartResource($cart) : null,
        ];

        if (session()->has('info')) {
            $response['message'] = session()->get('info');
        }

        return new JsonResource($response);
    }

    /**
     * Store items in cart.
     */
    public function store()
    {
        $this->validate(request(), [
            'product_id' => 'required|integer|exists:products,id',
            'is_buy_now' => 'integer|in:0,1',
            'quantity' => 'integer|min:1',
            'qty' => 'array',
            'qty.*' => 'integer|min:0',
            'bundle_option_qty' => 'array',
            'bundle_option_qty.*' => 'integer|min:1',
        ]);

        $product = $this->productRepository->with('parent')->findOrFail(request()->input('product_id'));

        $isBuyNow = request()->boolean('is_buy_now');

        try {
            if (! $product->status) {
                throw new \Exception(trans('shop::app.checkout.cart.inactive-add'));
            }

            if ($isBuyNow) {
                $cart = Cart::createBuyNowCart($product, request()->all());

                return new JsonResource([
                    'data' => new CartResource($cart),
                    'message' => trans('shop::app.checkout.cart.item-add-to-cart'),
                    'redirect' => route('shop.checkout.onepage.index', ['buy_now' => Cart::buyNowToken($cart->id)]),
                ]);
            }

            $cart = Cart::addProduct($product, request()->all());

            return new JsonResource([
                'data' => new CartResource($cart),
                'message' => trans('shop::app.checkout.cart.item-add-to-cart'),
            ]);
        } catch (InsufficientProductInventoryException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return response()->json([
                'redirect_uri' => route('shop.product_or_category.index', $product->url_key),
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Removes the item from the cart if it exists.
     */
    public function destroy(): JsonResource
    {
        $this->validate(request(), [
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        Cart::removeItem(request()->input('cart_item_id'));

        Cart::collectTotals();

        return new JsonResource([
            'data' => ($cart = Cart::getCart()) ? new CartResource($cart) : null,
            'message' => trans('shop::app.checkout.cart.success-remove'),
        ]);
    }

    /**
     * Method for remove selected items from cart.
     */
    public function destroySelected(): JsonResource
    {
        $this->validate(request(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|distinct',
        ]);

        foreach (request()->input('ids') as $id) {
            Cart::removeItem($id);
        }

        Cart::collectTotals();

        return new JsonResource([
            'data' => ($cart = Cart::getCart()) ? new CartResource($cart) : null,
            'message' => trans('shop::app.checkout.cart.index.remove-selected-success'),
        ]);
    }

    /**
     * Method for move to wishlist selected items from cart.
     */
    public function moveToWishlist(): JsonResource
    {
        if (! core()->getConfigData('customer.settings.wishlist.wishlist_option')) {
            abort(403, trans('shop::app.errors.403.title'));
        }

        $this->validate(request(), [
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'qty' => 'required|array',
            'qty.*' => 'integer|min:1',
        ]);

        foreach (request()->input('ids') as $index => $id) {
            $qty = request()->input('qty')[$index];

            Cart::moveToWishlist($id, $qty);
        }

        Cart::collectTotals();

        return new JsonResource([
            'data' => ($cart = Cart::getCart()) ? new CartResource($cart) : null,
            'message' => trans('shop::app.checkout.cart.index.move-to-wishlist-success'),
        ]);
    }

    /**
     * Updates the quantity of the items present in the cart.
     */
    public function update(): JsonResource
    {
        $this->validate(request(), [
            'qty' => 'present|array',
            'qty.*' => 'required|integer|min:0',
        ]);

        try {
            Cart::updateItems(request()->input());

            Cart::collectTotals();

            return new JsonResource([
                'data' => new CartResource(Cart::getCart()),
                'message' => trans('shop::app.checkout.cart.index.quantity-update'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResource([
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Estimate Shipping and Tax amount.
     */
    public function estimateShippingMethods(): JsonResource
    {
        $this->validate(request(), [
            'country' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'shipping_method' => 'sometimes|required',
        ]);

        $cart = Cart::getCart();

        $address = (new CartAddress)->fill([
            'country' => request()->input('country'),
            'state' => request()->input('state'),
            'postcode' => request()->input('postcode'),
            'cart_id' => $cart->id,
        ]);

        $cart->setRelation('billing_address', $address);

        $cart->setRelation('shipping_address', $address);

        Cart::setCart($cart);

        if (request()->has('shipping_method')) {
            Cart::saveShippingMethod(request()->input('shipping_method'));
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        $cart->setRelation('billing_address', $address);

        $cart->setRelation('shipping_address', $address);

        $cartResource = (new CartResource($cart))->jsonSerialize();

        return new JsonResource([
            'data' => [
                'cart' => $cartResource,
                'shipping_methods' => array_values(Shipping::collectRates()['shippingMethods']),
            ],
        ]);
    }

    /**
     * Apply coupon to the cart.
     */
    public function storeCoupon()
    {
        $validatedData = $this->validate(request(), [
            'code' => 'required',
        ]);

        try {
            if (strlen($validatedData['code'])) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $validatedData['code']);

                if (! $coupon) {
                    return (new JsonResource([
                        'data' => new CartResource(Cart::getCart()),
                        'message' => trans('shop::app.checkout.coupon.invalid'),
                    ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                if ($coupon->cart_rule->status) {
                    if (Cart::getCart()->coupon_code == $coupon->code) {
                        return (new JsonResource([
                            'data' => new CartResource(Cart::getCart()),
                            'message' => trans('shop::app.checkout.coupon.already-applied'),
                        ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    Cart::setCouponCode($coupon->code)->collectTotals();

                    if (Cart::getCart()->coupon_code == $coupon->code) {
                        return new JsonResource([
                            'data' => new CartResource(Cart::getCart()),
                            'message' => trans('shop::app.checkout.coupon.success-apply'),
                        ]);
                    }
                }

                return (new JsonResource([
                    'data' => new CartResource(Cart::getCart()),
                    'message' => trans('shop::app.checkout.coupon.apply-issue'),
                ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (\Exception $e) {
            return (new JsonResource([
                'data' => new CartResource(Cart::getCart()),
                'message' => trans('shop::app.checkout.coupon.error'),
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
            'data' => new CartResource(Cart::getCart()),
            'message' => trans('shop::app.checkout.coupon.remove'),
        ]);
    }

    /**
     * Cross-sell product listings.
     *
     * @return JsonResource::collection
     */
    public function crossSellProducts()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            return new JsonResource([
                'data' => [],
            ]);
        }

        $productIds = $cart->items->pluck('product_id')->toArray();

        $crossSellIds = $this->productRepository
            ->with('cross_sells')
            ->findWhereIn('id', $productIds)
            ->pluck('cross_sells')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->diff($productIds)
            ->values()
            ->all();

        $products = $this->productRepository
            ->whereIn('products.id', $crossSellIds)
            ->take(core()->getConfigData('catalog.products.cart_view_page.no_of_cross_sells_products'))
            ->get();

        return ProductResource::collection($products);
    }
}
