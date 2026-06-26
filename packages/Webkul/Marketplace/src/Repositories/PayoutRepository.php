<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Payout;
use Webkul\Marketplace\Enums\PayoutStatus;

class PayoutRepository extends Repository
{
    public function model(): string
    {
        return Payout::class;
    }

    public function requestPayout(int $sellerId, float $amount, string $method, array $details): object
    {
        return $this->create([
            'seller_id'      => $sellerId,
            'amount'         => $amount,
            'currency'       => config('marketplace.default_currency', 'BRL'),
            'status'         => PayoutStatus::Requested,
            'payment_method' => $method,
            'payment_details'=> $details,
        ]);
    }

    public function pendingForSeller(int $sellerId): object
    {
        return $this->model
            ->where('seller_id', $sellerId)
            ->whereIn('status', [PayoutStatus::Requested->value, PayoutStatus::Processing->value])
            ->get();
    }
}
