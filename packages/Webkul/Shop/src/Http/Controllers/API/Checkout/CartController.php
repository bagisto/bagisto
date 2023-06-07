<?php

namespace Webkul\Shop\Http\Controllers\API\Checkout;

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
        $this->middleware('throttle:5,1')->only('applyCoupon');
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return \Illuminate\View\View
     */

    public function home()
    {
        return view('shop::checkout.cart.index');
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
        $cartItemId = request()->all();
        dd($cartItemId);

        Cart::removeItem($cartItemId);

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
            Cart::updateItems(request()->all());

            session()->flash('success', trans('shop::app.checkout.cart.quantity.success'));

            return new JsonResource([
                'messege' => trans('shop::app.checkout.cart.quantity.success'),
            ]);
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }

        return new JsonResource([
            'messege' => trans('error'),
        ]);
    }

    /**
     * Apply coupon to the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $couponCode);

                if ($coupon->cart_rule->status) {

                    if (Cart::getCart()->coupon_code == $couponCode) {

                        session()->flash('warning',trans('shop::app.checkout.cart.coupon.already-applied'));

                        return new JsonResource([
                            'message' => trans('shop::app.checkout.cart.coupon.already-applied'),
                        ]);
                    }
    
                    Cart::setCouponCode($couponCode)->collectTotals();
                  
                    if (Cart::getCart()->coupon_code == $couponCode) {

                        session()->flash('success', trans('shop::app.checkout.cart.coupon.success'));

                        return redirect()->back();
                    }
                }
            }
           
            session()->flash('error', trans('shop::app.checkout.cart.coupon.invalid'));

            return new JsonResource([
                'message' => trans('shop::app.checkout.cart.coupon.invalid'),
            ]);
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('shop::app.checkout.cart.coupon-apply-issue'));

            return new JsonResource([
                'message' => trans('shop::app.checkout.cart.coupon-apply-issue'),
            ]);
        }
    }

    /**
     * Remove applied coupon from the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        session()->flash('error', trans('shop::app.checkout.total.remove-coupon'));

        return redirect()->back();
    }

    /**
     * Removes the item from the cart if it exists.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAllItems()
    {
        $result = Cart::removeAllItems();

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.item.success-all-remove'));
        }

        return redirect()->back();
    }

    /**
     * Function to move a already added product to wishlist will run only on customer authentication.
     *
     * @param  int  $id
     * @return mixed
     */
    public function moveToWishlist($id)
    {
        $result = Cart::moveToWishlist($id);

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.move-to-wishlist-success'));
        } else {
            session()->flash('warning', trans('shop::app.checkout.cart.move-to-wishlist-error'));
        }

        return redirect()->back();
    }
}
