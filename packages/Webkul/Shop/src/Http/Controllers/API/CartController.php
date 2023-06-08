<?php

namespace Webkul\Shop\Http\Controllers\API;

use Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Shop\Http\Controllers\API\APIController;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

class CartController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CartItemRepository  $wishlistRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\CartRule\Repositories\CartRuleCouponRepository  $cartRuleCouponRepository
     * @return void
     */
    public function __construct(
        protected WishlistRepository $wishlistRepository,
        protected ProductRepository $productRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository
    )
    {
    }

    public function index(): JsonResource
    {
        Cart::collectTotals();

        $cart = Cart::getCart();

        $cart?->load('items.product.cross_sells');
       
        $crossSellProductCount = core()->getConfigData('catalog.products.cart_view_page.no_of_cross_sells_products');

        $crossSellProducts = $cart?->items
                            ->map(fn ($item) => $item->product->cross_sells)
                            ->collapse()
                            ->unique('id')
                            ->take($crossSellProductCount != "" ? $crossSellProductCount : 12);

        return new JsonResource([
            'data'  => $cart,
            'crossSellProducts'  => $crossSellProducts,
            'message'  => trans('shop::app.components.products.item-add-to-cart')
        ]);
    }

     /**
     * Function for user to add the product in the cart.
     *
     * @return array
     */
    public function store(): JsonResource
    {
        try {
            $productId = request()->input('product_id');

            Cart::deactivateCurrentCartIfBuyNowIsActive();

            $cart = Cart::addProduct($productId, request()->all());
    
            if (
                is_array($cart)
                && isset($cart['warning'])
            ) {
                return response()->json([
                    'message' => $cart['warning'],
                ]);
            }
    
            if ($cart) {
                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere([
                        'product_id' => $productId, 
                        'customer_id' => $customer->id
                    ]);
                }

                session()->flash('success', trans('shop::app.components.products.item-add-to-cart'));

                return new JsonResource([
                    'message'  => trans('shop::app.components.products.item-add-to-cart')
                ]);
            }
        } catch (\Exception $exception) {
            \Log::error('CartController: ' . $exception->getMessage(),
                ['product_id' => $productId, 'cart_id' => cart()->getCart() ?? 0]);

            session()->flash('success', $exception->getMessage());

            return new JsonResource([
                'message'   => $exception->getMessage()
            ]);
        }
    }

    /**
     * Removes the item from the cart if it exists.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(): JsonResource
    {
        Cart::removeItem(request()->input('cart_item_id'));

        session()->flash('success', trans('shop::app.checkout.cart.item.success-remove'));

        return new JsonResource([
            'messege' => trans('shop::app.checkout.cart.item.success-remove'),
        ]);
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(): JsonResource
    {
        try {
            Cart::updateItems(request()->input());

            return new JsonResource([
                'message' => trans('shop::app.checkout.cart.quantity-update'),
            ]);
        } catch (\Exception $e) {}

        return new JsonResource([
            'message' => trans('error'),
        ]);
    }
}
