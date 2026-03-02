<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\Sales\Repositories\OrderRepository;

class OnepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        Event::dispatch('checkout.load.index');

        /**
         * If guest checkout is not allowed then redirect back to the cart page
         */
        if (
            ! auth()->guard('customer')->check()
            && ! core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        /**
         * If user is suspended then redirect back to the cart page
         */
        if (auth()->guard('customer')->user()?->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        /**
         * If cart has errors then redirect back to the cart page
         */
        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        /**
         * If cart is has downloadable items and customer is not logged in
         * then redirect back to the cart page
         */
        if (
            ! auth()->guard('customer')->check()
            && (
                $cart->hasDownloadableItems()
                || ! $cart->hasGuestCheckoutItems()
            )
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        return view('shop::checkout.onepage.index', compact('cart'));
    }

    /**
     * Order success page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success(OrderRepository $orderRepository)
    {
        if (! $order = $orderRepository->find(session('order_id'))) {
            return redirect()->route('shop.checkout.cart.index');
        }

        if (
            core()->getConfigData('general.magic_ai.settings.enabled')
            && core()->getConfigData('general.magic_ai.checkout_message.enabled')
            && ! empty(core()->getConfigData('general.magic_ai.default_prompts.checkout_message'))
        ) {
            try {
                $order->checkout_message = MagicAI::checkoutMessage($order);
            } catch (\Exception $e) {
            }
        }

        return view('shop::checkout.success', compact('order'));
    }
}
