<?php

namespace Webkul\Shop\Http\Controllers;

use Cart;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Contracts\Cart as CartModel;
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

        $this->middleware('customer')->only('moveToWishlist');

        parent::__construct();
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
 
        return view($this->_config['view'], [
            'cart' => $cart,
            'crossSellProducts' => $cart?->items
                ->map(fn ($item) => $item->product->cross_sells)
                ->collapse()
                ->unique('id')
                ->take($crossSellProductCount != "" ? $crossSellProductCount : 12),
        ]);
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function add($id)
    {
        try {
            if ($product = $this->productRepository->findOrFail($id)) {
                if (! $product->visible_individually) {
                    return redirect()->back();
                }
            }

            Cart::deactivateCurrentCartIfBuyNowIsActive();

            $result = Cart::addProduct($id, request()->all());

            if ($this->onFailureAddingToCart($result)) {
                return redirect()->back();
            }

            session()->flash('success', __('shop::app.checkout.cart.item.success'));

            if ($customer = auth()->guard('customer')->user()) {
                $this->wishlistRepository->deleteWhere([
                    'product_id'  => $id,
                    'customer_id' => $customer->id,
                ]);
            }

            if (request()->get('is_buy_now')) {
                Event::dispatch('shop.item.buy-now', $id);

                return redirect()->route('shop.checkout.onepage.index');
            }
        } catch (\Exception $e) {
            session()->flash('warning', __($e->getMessage()));

            $product = $this->productRepository->findOrFail($id);

            Log::error(
                'Shop CartController: ' . $e->getMessage(),
                [
                    'product_id' => $id,
                    'cart_id'    => cart()->getCart() ?? 0
                ]
            );

            return redirect()->route('shop.productOrCategory.index', $product->url_key);
        }

        return redirect()->back();
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
        }

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
                            'message' => trans('shop::app.checkout.total.coupon-already-applied'),
                        ]);
                    }
    
                    Cart::setCouponCode($couponCode)->collectTotals();
                  
                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return response()->json([
                            'success' => true,
                            'message' => trans('shop::app.checkout.total.success-coupon'),
                        ]);
                    }
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
     * Remove applied coupon from the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
        ]);
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
}
