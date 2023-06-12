<?php

namespace Webkul\Shop\Http\Controllers;

use Cart;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected ProductRepository $productRepository,
        protected WishlistRepository $wishlistRepository,
    ) {}

    /**
     * Cart page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop::checkout.cart.index');
    }

    /**
     * Add the product in the cart.
     * 
     * To Do: Need to remove this old methods once all pages are developed.
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

            \Log::error(
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
                        session()->flash('success', trans('shop::app.checkout.cart.coupon-already-applied'));

                        return redirect()->back();
                    }

                    Cart::setCouponCode($couponCode)->collectTotals();

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        session()->flash('success', trans('shop::app.checkout.cart.coupon.success-apply'));

                        return redirect()->back();
                    }
                }
            }

            session()->flash('danger', trans('shop::app.checkout.cart.coupon.invalid'));

            return redirect()->back();

        } catch (\Exception $e) {
            report($e);

            session()->flash('warning', trans('shop::app.checkout.cart.coupon.apply-issue'));

            return redirect()->back();
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

        session()->flash('warning', trans('shop::app.checkout.cart.coupon.remove'));

        return redirect()->back();
    }

    /**
     * Returns true, if result of adding product to cart
     * is an array and contains a key "warning" or "info".
     * 
     * To Do: Need to remove this old methods once all pages are developed.
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
