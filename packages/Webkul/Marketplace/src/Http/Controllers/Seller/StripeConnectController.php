<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Services\StripeConnectService;

class StripeConnectController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected StripeConnectService $stripe,
    ) {}

    /**
     * Connect status page.
     */
    public function index(): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        if ($seller->stripe_account_id) {
            try {
                $this->stripe->refreshStatus($seller);
                $seller->refresh();
            } catch (\Throwable $e) {
                Log::warning('[Marketplace] Stripe status refresh failed: '.$e->getMessage());
            }
        }

        return view('marketplace::seller.connect.index', [
            'seller'       => $seller,
            'configured'   => $this->stripe->isConfigured(),
            'onboarded'    => $this->stripe->isOnboarded($seller),
        ]);
    }

    /**
     * Start (or resume) Stripe Express onboarding.
     */
    public function onboard(): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        if (! $this->stripe->isConfigured()) {
            return back()->with('error', trans('marketplace::app.seller.connect.not-configured'));
        }

        try {
            $url = $this->stripe->onboardingLink(
                $seller,
                route('marketplace.connect.return'),
                route('marketplace.connect.index'),
            );

            return redirect()->away($url);
        } catch (\Throwable $e) {
            Log::error('[Marketplace] Stripe onboarding failed: '.$e->getMessage());

            return back()->with('error', trans('marketplace::app.seller.connect.error').' '.$e->getMessage());
        }
    }

    /**
     * Return URL after onboarding — refresh status and inform the seller.
     */
    public function return(): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        try {
            $status = $this->stripe->refreshStatus($seller);

            $message = $status['payouts_enabled']
                ? trans('marketplace::app.seller.connect.success')
                : trans('marketplace::app.seller.connect.pending');

            return redirect()->route('marketplace.connect.index')->with('info', $message);
        } catch (\Throwable $e) {
            return redirect()->route('marketplace.connect.index')
                ->with('error', $e->getMessage());
        }
    }

    protected function getSeller(): ?object
    {
        $customer = auth()->guard('customer')->user();

        return $customer ? $this->sellerRepository->findByCustomer($customer->id) : null;
    }
}
