<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Webkul\Marketplace\Enums\SubscriptionStatus;
use Webkul\Marketplace\Models\Subscription;
use Webkul\Marketplace\Models\SubscriptionPlan;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Services\StripeConnectService;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected StripeConnectService $stripe,
    ) {}

    /**
     * List available plans + the seller's current subscription.
     */
    public function index(): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();

        $current = Subscription::where('seller_id', $seller->id)
            ->whereIn('status', [SubscriptionStatus::Active->value, SubscriptionStatus::Trialing->value])
            ->latest()
            ->first();

        return view('marketplace::seller.subscriptions.index', [
            'seller'     => $seller,
            'plans'      => $plans,
            'current'    => $current,
            'configured' => $this->stripe->isConfigured(),
        ]);
    }

    /**
     * Subscribe to a plan. Free plans activate immediately; paid plans go to Stripe Checkout.
     */
    public function subscribe(int $planId): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $plan = SubscriptionPlan::findOrFail($planId);

        // Free plan -> activate directly, no Stripe needed.
        if ((float) $plan->price <= 0 || ! $plan->stripe_price_id) {
            $this->activateLocal($seller, $plan, null);

            return redirect()->route('marketplace.subscriptions.index')
                ->with('success', trans('marketplace::app.seller.subscriptions.subscribed'));
        }

        if (! $this->stripe->isConfigured()) {
            return back()->with('error', trans('marketplace::app.seller.connect.not-configured'));
        }

        try {
            $url = $this->stripe->subscriptionCheckout(
                $seller,
                $plan,
                route('marketplace.subscriptions.success', ['plan' => $plan->id]),
                route('marketplace.subscriptions.index'),
            );

            return redirect()->away($url);
        } catch (\Throwable $e) {
            Log::error('[Marketplace] Subscription checkout failed: '.$e->getMessage());

            return back()->with('error', trans('marketplace::app.seller.subscriptions.error').' '.$e->getMessage());
        }
    }

    /**
     * Stripe Checkout success callback — record the active subscription.
     */
    public function success(int $plan): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $planModel = SubscriptionPlan::find($plan);

        if ($planModel) {
            $this->activateLocal($seller, $planModel, null);
        }

        return redirect()->route('marketplace.subscriptions.index')
            ->with('success', trans('marketplace::app.seller.subscriptions.subscribed'));
    }

    public function cancel(): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $subscription = Subscription::where('seller_id', $seller->id)
            ->whereIn('status', [SubscriptionStatus::Active->value, SubscriptionStatus::Trialing->value])
            ->latest()
            ->first();

        if ($subscription) {
            try {
                if ($subscription->stripe_subscription_id) {
                    $this->stripe->cancelSubscription($subscription->stripe_subscription_id);
                }
            } catch (\Throwable $e) {
                Log::warning('[Marketplace] Stripe cancel failed: '.$e->getMessage());
            }

            $subscription->update([
                'status'       => SubscriptionStatus::Cancelled,
                'cancelled_at' => now(),
            ]);
        }

        return redirect()->route('marketplace.subscriptions.index')
            ->with('info', trans('marketplace::app.seller.subscriptions.cancelled'));
    }

    /**
     * Activate a plan locally and apply its commission rate to the seller.
     */
    protected function activateLocal(object $seller, SubscriptionPlan $plan, ?string $stripeSubId): void
    {
        // close previous active subscriptions
        Subscription::where('seller_id', $seller->id)
            ->whereIn('status', [SubscriptionStatus::Active->value, SubscriptionStatus::Trialing->value])
            ->update(['status' => SubscriptionStatus::Cancelled, 'cancelled_at' => now()]);

        Subscription::create([
            'seller_id'              => $seller->id,
            'plan_id'                => $plan->id,
            'status'                 => SubscriptionStatus::Active,
            'stripe_subscription_id' => $stripeSubId,
            'current_period_start'   => now(),
            'current_period_end'     => $plan->interval === 'yearly' ? now()->addYear() : now()->addMonth(),
        ]);

        // seller inherits the plan commission rate
        $seller->update(['commission_rate' => $plan->commission_rate]);
    }

    protected function getSeller(): ?object
    {
        $customer = auth()->guard('customer')->user();

        return $customer ? $this->sellerRepository->findByCustomer($customer->id) : null;
    }
}
