<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\Sales\Repositories\OrderRepository;

class OnepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        Event::dispatch('checkout.load.index');

        /**
         * If guest checkout is not allowed then redirect back to the cart page.
         */
        if (
            ! auth()->guard('customer')->check()
            && ! core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')
        ) {
            return $this->redirectToSignIn();
        }

        /**
         * If user is suspended then redirect back to the cart page.
         */
        if (auth()->guard('customer')->user()?->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        /**
         * If cart has errors then redirect back to the cart page.
         */
        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        /**
         * If cart is has downloadable items and customer is not logged in
         * then redirect back to the cart page.
         */
        if (
            ! auth()->guard('customer')->check()
            && (
                $cart->hasDownloadableItems()
                || ! $cart->hasGuestCheckoutItems()
            )
        ) {
            return $this->redirectToSignIn();
        }

        return view('shop::checkout.onepage.index', compact('cart'));
    }

    /**
     * Order success page.
     *
     * @return View|RedirectResponse
     */
    public function success(OrderRepository $orderRepository)
    {
        if (! $order = $orderRepository->find(session('order_id'))) {
            return redirect()->route('shop.checkout.cart.index');
        }

        if (
            core()->getConfigData('magic_ai.general.settings.enabled')
            && core()->getConfigData('magic_ai.storefront_features.checkout_message.enabled')
        ) {
            try {
                $order->checkout_message = MagicAI::checkoutMessage($order);
            } catch (\Exception $e) {
            }
        }

        return view('shop::checkout.success', compact('order'));
    }

    /**
     * Send a guest to sign in, holding on to the checkout they were sent away from.
     *
     * A guest turned back from checkout has already chosen what to buy, so signing in or
     * registering returns them to it rather than to the page the account settings would
     * otherwise pick.
     *
     * @return RedirectResponse
     */
    protected function redirectToSignIn()
    {
        session()->put('shop.url.intended', route('shop.checkout.onepage.index'));

        return redirect()->route('shop.customer.session.index');
    }
}
