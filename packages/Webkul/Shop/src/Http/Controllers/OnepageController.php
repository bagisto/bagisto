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
     * Show the checkout page, or send the shopper to sign in, to the cart or to an order already placed.
     *
     * @return View|RedirectResponse
     */
    public function index(OrderRepository $orderRepository)
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        Event::dispatch('checkout.load.index', Cart::getCart());

        if (
            ! auth()->guard('customer')->check()
            && ! core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')
        ) {
            return $this->redirectToSignIn();
        }

        if (auth()->guard('customer')->user()?->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (
            $cart?->is_buy_now
            && ($order = $orderRepository->findOneWhere(['cart_id' => $cart->id]))
        ) {
            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        }

        if (Cart::requiresSignInToCheckout()) {
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
     * Send a guest to sign in, holding on to the checkout they were sent away from — the buy now token
     * included, so signing in returns them to that buy now rather than to the whole basket.
     */
    protected function redirectToSignIn(): RedirectResponse
    {
        session()->put('shop.url.intended', route('shop.checkout.onepage.index', request()->only('buy_now')));

        return redirect()->route('shop.customer.session.index');
    }
}
