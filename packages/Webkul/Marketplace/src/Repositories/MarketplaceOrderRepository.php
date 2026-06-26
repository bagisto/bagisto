<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\MarketplaceOrder;
use Webkul\Marketplace\Enums\CommissionStatus;

class MarketplaceOrderRepository extends Repository
{
    public function model(): string
    {
        return MarketplaceOrder::class;
    }

    public function createFromOrder(object $order, object $seller, float $commissionRate): object
    {
        $baseTotal        = $order->base_grand_total;
        $commissionAmount = round($baseTotal * ($commissionRate / 100), 4);
        $sellerTotal      = $baseTotal - $commissionAmount;

        return $this->create([
            'order_id'          => $order->id,
            'seller_id'         => $seller->id,
            'base_total'        => $baseTotal,
            'commission_amount' => $commissionAmount,
            'seller_total'      => $sellerTotal,
            'commission_rate'   => $commissionRate,
            'commission_status' => CommissionStatus::Pending,
        ]);
    }

    public function pendingForSeller(int $sellerId): object
    {
        return $this->model
            ->where('seller_id', $sellerId)
            ->where('commission_status', CommissionStatus::Pending)
            ->get();
    }

    public function totalEarningsBySeller(int $sellerId): float
    {
        return $this->model
            ->where('seller_id', $sellerId)
            ->where('commission_status', CommissionStatus::Paid)
            ->sum('seller_total');
    }
}
