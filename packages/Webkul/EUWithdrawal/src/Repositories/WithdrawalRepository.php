<?php

namespace Webkul\EUWithdrawal\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\EUWithdrawal\Contracts\Withdrawal;

class WithdrawalRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Withdrawal::class;
    }

    /**
     * Return the single withdrawal record for the given order, if any.
     *
     * A withdrawal is a one-time declaration: at most one exists per order
     * (enforced by the service-layer Cache::lock + idempotent find-or-create).
     * Once declined, the customer cannot re-submit; admin contesting
     * entitlement is terminal and the record blocks any further withdrawal
     * attempt for that order.
     */
    public function findForOrder(int $orderId): ?\Webkul\EUWithdrawal\Models\Withdrawal
    {
        return $this->model
            ->where('order_id', $orderId)
            ->first();
    }
}
