<?php

namespace Webkul\Shop\Http\Controllers\Checkout;

use Cart;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

class CartController extends Controller
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
    public function index()
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

        return view('shop::checkout.cart.index', compact('cart', 'crossSellProducts'));
    }

    /**
     * Function for user to add the product in the cart.
     *
     * @return array
     */
    public function store()
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
                        'product_id'  => $productId, 
                        'customer_id' => $customer->id
                    ]);
                }

                return response()->json([
                    'message'  => trans('shop::app.components.products.item-add-to-cart')
                ]);
            }
        } catch (\Exception $exception) {
            \Log::error('CartController: ' . $exception->getMessage(),
                ['product_id' => $productId, 'cart_id' => cart()->getCart() ?? 0]);

            return response()->json([
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
    public function remove($itemId)
    {
        $result = Cart::removeItem($itemId);

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.item.success-remove'));
        } else {
            session()->flash('error', trans('shop::app.checkout.cart.item.warning-remove'));
        }
        return redirect()->back();
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateBeforeCheckout()
    {
        try {
            $result = Cart::updateItems(request()->all());

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.quantity.success'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }

        return redirect()->back();
    }

    /**
     * Apply coupon to the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $couponCode);
                if ($coupon->cart_rule->status) {

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return response()->json([
                            'success' => false,
                            'message' => trans('shop::app.checkout.cart.coupon.already-applied'),
                        ]);
                    }

                    Cart::setCouponCode($couponCode)->collectTotals();

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        session()->flash('success', trans('shop::app.checkout.cart.coupon.success-apply'));
                        
                        return redirect()->back();
                    }

                    session()->flash('error', trans('shop::app.checkout.cart.coupon.cannot-apply'));
                    return redirect()->back();
                }
            }
           
            session()->flash('success', trans('shop::app.checkout.cart.coupon.invalid'));

            return redirect()->back();            
        } catch (\Exception $e) {
            report($e);
            session()->flash('success', trans('shop::app.checkout.cart.coupon.apply-issue'));

            return redirect()->back();
        }
    }

    /**
     * Remove applied coupon from the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        session()->flash('success', trans('shop::app.checkout.cart.coupon.remove'));

        return redirect()->back();
    }

    /**
     * Returns true, if result of adding product to cart
     * is an array and contains a key "warning" or "info".
     *
     * @param  array  $result
     * @return boolean
     */
    private function onFailureAddingToCart($result): bool
    {
        if (! is_array($result)) {
            return false;
        }

        if (isset($result['warning'])) {
            session()->flash('warning', $result['warning']);
        } elseif (isset($result['info'])) {
            session()->flash('info', $result['info']);
        } else {
            return false;
        }

        return true;
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
