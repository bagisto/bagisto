<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Webkul\Marketplace\Enums\PayoutStatus;
use Webkul\Marketplace\Repositories\PayoutRepository;
use Webkul\Marketplace\Services\StripeConnectService;

class PayoutController extends Controller
{
    public function __construct(
        protected PayoutRepository $payoutRepository,
        protected StripeConnectService $stripe,
    ) {}

    public function index(): View
    {
        $payouts = $this->payoutRepository->with('seller')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('marketplace::admin.payouts.index', compact('payouts'));
    }

    public function approve(int $id): RedirectResponse
    {
        $payout = $this->payoutRepository->findOrFail($id);

        $transactionId = request('transaction_id', '');

        // Money routing is decided per-seller by the admin (payout_mode), or by the
        // payout's own method. When 'stripe', push funds automatically to the seller's
        // connected account and use the Stripe transfer id as the reference.
        $useStripe = ($payout->seller?->payout_mode?->value === 'stripe')
            || $payout->payment_method === 'stripe';

        if ($useStripe) {
            if (! $this->stripe->isConfigured()) {
                session()->flash('error', trans('marketplace::app.seller.connect.not-configured'));

                return redirect()->route('admin.marketplace.payouts.index');
            }

            try {
                $transfer = $this->stripe->transfer(
                    $payout->seller,
                    (float) $payout->amount,
                    $payout->currency ?: config('marketplace.default_currency', 'BRL'),
                    ['payout_id' => $payout->id, 'seller_id' => $payout->seller_id],
                );

                $transactionId = $transfer->id;
            } catch (\Throwable $e) {
                Log::error('[Marketplace] Stripe transfer failed for payout '.$payout->id.': '.$e->getMessage());

                session()->flash('error', trans('marketplace::app.admin.payouts.transfer-failed').' '.$e->getMessage());

                return redirect()->route('admin.marketplace.payouts.index');
            }
        }

        $payout->markAsPaid($transactionId);

        // settle the seller's pending commissions covered by this payout
        $this->settleCommissions($payout);

        session()->flash('success', trans('marketplace::app.admin.payouts.approve-success'));

        return redirect()->route('admin.marketplace.payouts.index');
    }

    /**
     * Mark the seller's pending commissions as paid once a payout clears.
     */
    protected function settleCommissions(object $payout): void
    {
        \Webkul\Marketplace\Models\MarketplaceOrder::where('seller_id', $payout->seller_id)
            ->where('commission_status', \Webkul\Marketplace\Enums\CommissionStatus::Pending)
            ->update([
                'commission_status' => \Webkul\Marketplace\Enums\CommissionStatus::Paid,
                'paid_at'           => now(),
            ]);
    }

    public function reject(int $id): RedirectResponse
    {
        $payout = $this->payoutRepository->findOrFail($id);
        $payout->update([
            'status' => PayoutStatus::Rejected,
            'notes'  => request('notes'),
        ]);

        session()->flash('error', trans('marketplace::app.admin.payouts.reject-success'));

        return redirect()->route('admin.marketplace.payouts.index');
    }
}
