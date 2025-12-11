<?php

namespace Webkul\Shop\Http\Controllers\Customer\Guest;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Http\Controllers\Controller;

class GuestAuthenticationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected OrderRepository $orderRepository) {}

    /**
     * Login for the guest user
     */
    public function index(): View|RedirectResponse
    {
        if (! empty(session()->get('guestOrderId'))) {
            return view('shop::guest.rma.index');
        }

        if (! auth()->guard('customer')->check()) {
            return view('shop::guest.rma.login');
        }

        return redirect()->route('shop.guest.account.rma.index');
    }

    /**
     * Get the requested data for the guest
     */
    public function store(): RedirectResponse
    {
        $guestUserData = request()->only(
            'order_id',
            'email',
        );

        $order = $this->orderRepository->findOneWhere([
            'id'             => $guestUserData['order_id'],
            'customer_email' => $guestUserData['email'],
            'is_guest'       => 1,
        ]);

        if ($order) {
            session()->put('guestOrderId', $guestUserData['order_id']);

            session()->put('guestEmail', $guestUserData['email']);

            return redirect()->route('shop.guest.account.rma.index')->with('guestUserData');
        }

        return redirect()->back()->with('error', 'Invalid details for guest');
    }

    /**
     * Logout for the guest user
     */
    public function destroy(): RedirectResponse
    {
        session()->forget(['guestOrderId', 'guestEmail']);

        return redirect()->route('shop.rma.guest.session.index');
    }
}
