<?php

namespace Webkul\Marketplace\Services;

use Stripe\StripeClient;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SubscriptionPlan;

/**
 * Stripe Connect integration for the marketplace.
 *
 * Money model: the platform collects the full order amount on its own Stripe
 * account (standard Checkout). Seller earnings are later pushed to the seller's
 * CONNECTED (Express) account via Stripe Transfers when a payout is approved.
 *
 * Seller subscriptions (recurring plan fees the seller pays the platform) are
 * handled with Stripe Checkout in "subscription" mode against the plan's price.
 *
 * Every method degrades gracefully when no Stripe secret key is configured, so
 * the marketplace keeps working with the manual payout model until you add keys.
 */
class StripeConnectService
{
    /**
     * Resolve the platform Stripe secret key.
     * Order: marketplace config (env STRIPE_CONNECT_SECRET) -> Bagisto Stripe gateway config.
     */
    public function secretKey(): ?string
    {
        $key = config('marketplace.stripe_secret');

        if (! $key) {
            $key = core()->getConfigData('sales.payment_methods.stripe.api_test_key');
        }

        return ($key && $key !== 'API_TEST_KEY') ? $key : null;
    }

    public function isConfigured(): bool
    {
        return (bool) $this->secretKey();
    }

    public function client(): ?StripeClient
    {
        $key = $this->secretKey();

        return $key ? new StripeClient($key) : null;
    }

    protected function clientOrFail(): StripeClient
    {
        if (! $client = $this->client()) {
            throw new \RuntimeException('Stripe secret key is not configured. Set it in Admin → Configure → Stripe, or STRIPE_CONNECT_SECRET in .env.');
        }

        return $client;
    }

    /* ===================== CONNECTED ACCOUNTS (split payouts) ===================== */

    /**
     * Create (or reuse) an Express connected account for the seller.
     */
    public function createAccount(Seller $seller): string
    {
        if ($seller->stripe_account_id) {
            return $seller->stripe_account_id;
        }

        $account = $this->clientOrFail()->accounts->create([
            'type'             => 'express',
            'email'            => $seller->customer?->email,
            'business_type'    => 'individual',
            'business_profile' => ['name' => $seller->shop_name],
            'capabilities'     => [
                'card_payments' => ['requested' => true],
                'transfers'     => ['requested' => true],
            ],
            'metadata'         => ['seller_id' => $seller->id],
        ]);

        $seller->update(['stripe_account_id' => $account->id]);

        return $account->id;
    }

    /**
     * Generate a hosted onboarding link for the seller to finish KYC.
     */
    public function onboardingLink(Seller $seller, string $returnUrl, string $refreshUrl): string
    {
        $accountId = $this->createAccount($seller);

        return $this->clientOrFail()->accountLinks->create([
            'account'     => $accountId,
            'refresh_url' => $refreshUrl,
            'return_url'  => $returnUrl,
            'type'        => 'account_onboarding',
        ])->url;
    }

    /**
     * Refresh and persist the seller's onboarding/charges/payouts status.
     */
    public function refreshStatus(Seller $seller): array
    {
        if (! $client = $this->client()) {
            return ['charges_enabled' => false, 'payouts_enabled' => false, 'details_submitted' => false];
        }

        if (! $seller->stripe_account_id) {
            return ['charges_enabled' => false, 'payouts_enabled' => false, 'details_submitted' => false];
        }

        $account = $client->accounts->retrieve($seller->stripe_account_id);

        $seller->update([
            'stripe_charges_enabled' => (bool) $account->charges_enabled,
            'stripe_payouts_enabled' => (bool) $account->payouts_enabled,
        ]);

        return [
            'charges_enabled'   => (bool) $account->charges_enabled,
            'payouts_enabled'   => (bool) $account->payouts_enabled,
            'details_submitted' => (bool) $account->details_submitted,
        ];
    }

    public function isOnboarded(Seller $seller): bool
    {
        return (bool) $seller->stripe_account_id && $seller->stripe_payouts_enabled;
    }

    /**
     * Push funds to a seller's connected account (used on payout approval).
     */
    public function transfer(Seller $seller, float $amount, string $currency, array $metadata = []): \Stripe\Transfer
    {
        if (! $seller->stripe_account_id) {
            throw new \RuntimeException('Seller has not connected a Stripe account yet.');
        }

        return $this->clientOrFail()->transfers->create([
            'amount'      => (int) round($amount * 100),
            'currency'    => strtolower($currency),
            'destination' => $seller->stripe_account_id,
            'metadata'    => $metadata,
        ]);
    }

    /* ===================== SELLER SUBSCRIPTIONS (recurring plan fees) ===================== */

    /**
     * Ensure the seller has a Stripe customer (billed entity) and return its id.
     */
    public function ensureCustomer(Seller $seller): string
    {
        if ($seller->stripe_customer_id) {
            return $seller->stripe_customer_id;
        }

        $customer = $this->clientOrFail()->customers->create([
            'email'    => $seller->customer?->email,
            'name'     => $seller->shop_name,
            'metadata' => ['seller_id' => $seller->id],
        ]);

        $seller->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }

    /**
     * Start a hosted Checkout session (subscription mode) for a plan.
     * Free plans (no stripe_price_id / price 0) are handled by the caller without Stripe.
     */
    public function subscriptionCheckout(Seller $seller, SubscriptionPlan $plan, string $successUrl, string $cancelUrl): string
    {
        if (! $plan->stripe_price_id) {
            throw new \RuntimeException('This plan has no Stripe price id configured.');
        }

        $customerId = $this->ensureCustomer($seller);

        return $this->clientOrFail()->checkout->sessions->create([
            'mode'        => 'subscription',
            'customer'    => $customerId,
            'line_items'  => [['price' => $plan->stripe_price_id, 'quantity' => 1]],
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
            'metadata'    => ['seller_id' => $seller->id, 'plan_id' => $plan->id],
        ])->url;
    }

    public function cancelSubscription(string $stripeSubscriptionId): void
    {
        $this->clientOrFail()->subscriptions->cancel($stripeSubscriptionId);
    }
}
