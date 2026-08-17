<?php

namespace Webkul\EUWithdrawal\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\EUWithdrawal\Enums\WithdrawalStatus;
use Webkul\EUWithdrawal\Events\WithdrawalReceived;
use Webkul\EUWithdrawal\Models\Withdrawal;
use Webkul\EUWithdrawal\Repositories\WithdrawalRepository;
use Webkul\Sales\Contracts\Order;

class WithdrawalService
{
    /**
     * Create a new service instance.
     */
    public function __construct(protected WithdrawalRepository $withdrawals) {}

    /**
     * Submit a withdrawal declaration for an order.
     *
     * Idempotent: if a withdrawal already exists for the order, the existing
     * row is returned and no second row is created. A short Cache::lock guards
     * the find-or-create against concurrent double-submits (double-click,
     * back-button replay).
     *
     * Fires WithdrawalReceived after commit so listeners (confirmation email
     * in Shop, custom merchant integrations) run against persisted state.
     */
    public function submit(Order $order, ?string $reasonText, string $locale): Withdrawal
    {
        $lock = Cache::lock("eu-withdraw-order-{$order->id}", 5);

        try {
            $lock->block(5);

            $existing = $this->withdrawals->findForOrder($order->id);

            if ($existing !== null) {
                return $existing;
            }

            $withdrawal = DB::transaction(function () use ($order, $reasonText, $locale) {
                return $this->withdrawals->create([
                    'uuid' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'is_guest' => (bool) $order->is_guest,
                    'customer_email' => $order->customer_email,
                    'channel_id' => $order->channel_id,
                    'locale' => $locale,
                    'reason_text' => $reasonText !== null ? mb_substr($reasonText, 0, 5000) : null,
                    'received_at' => now(),
                    'status' => WithdrawalStatus::RECEIVED,
                ]);
            });
        } finally {
            optional($lock)->release();
        }

        event(new WithdrawalReceived($withdrawal));

        return $withdrawal->fresh();
    }
}
