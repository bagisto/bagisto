<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OnepageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CustomerRepository $customerRepository
    ) {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Event::dispatch('checkout.load.index');

        if (
            ! auth()->guard('customer')->check()
            && ! core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        if (
            auth()->guard('customer')->check()
            && auth()->guard('customer')->user()->is_suspended
        ) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if ($cart->applied_cart_rule_ids != '') {
            session()->flash('success', trans('shop::app.checkout.cart.rule-applied'));
        }

        if (
            (
                ! auth()->guard('customer')->check()
                && $cart->hasDownloadableItems()
            )
            || (
                ! auth()->guard('customer')->check()
                && ! $cart->hasGuestCheckoutItems()
            )
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?: 0;

        if (! $cart->checkMinimumOrder()) {
            session()->flash('warning', trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));

            return redirect()->back();
        }

        Cart::collectTotals();

        return view('shop::checkout.onepage.index', compact('cart'));
    }

    /**
     * Order success page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success()
    {
        if (! $order = session('order')) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view('shop::checkout.success', compact('order'));
    }

    /**
     * Check customer is exist or not.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkExistCustomer()
    {
        $customer = $this->customerRepository->findOneWhere([
            'email' => request()->email,
        ]);

        if (! is_null($customer)) {
            return 'true';
        }

        return 'false';
    }

    /**
     * Login for checkout.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForCheckout()
    {
        $this->validate(request(), [
            'email' => 'required|email',
        ]);

        if (! auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => trans('shop::app.customer.login-form.invalid-creds')]);
        }

        Cart::mergeCart();

        return response()->json(['success' => 'Login successfully']);
    }

    /**
     * To apply couponable rule requested.
     *
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon()
    {
        $this->validate(request(), [
            'code' => 'string|required',
        ]);

        if ($result = $this->coupon->apply(request()->input('code'))) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('shop::app.checkout.total.coupon-applied'),
                'result'  => $result,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans('shop::app.checkout.total.cannot-apply-coupon'),
            'result'  => null,
        ], 422);
    }

    /**
     * Initiates the removal of couponable cart rule.
     *
     * @return array
     */
    public function removeCoupon()
    {
        if ($this->coupon->remove()) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('admin::app.promotion.status.coupon-removed'),
                'data'    => [
                    'grand_total' => core()->currency(Cart::getCart()->grand_total),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans('admin::app.promotion.status.coupon-remove-failed'),
            'data'    => null,
        ], 422);
    }
}
