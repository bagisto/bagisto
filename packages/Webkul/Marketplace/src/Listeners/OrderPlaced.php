<?php

namespace Webkul\Marketplace\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\Marketplace\Repositories\MarketplaceOrderRepository;
use Webkul\Marketplace\Repositories\SellerProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Sales\Models\Order;

class OrderPlaced
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerProductRepository $sellerProductRepository,
        protected MarketplaceOrderRepository $marketplaceOrderRepository,
    ) {}

    /**
     * Fired by: checkout.order.save.after (Bagisto event).
     * Groups order items by seller and records commission splits.
     */
    public function handle(Order $order): void
    {
        if (! config('marketplace.enabled', false)) {
            return;
        }

        try {
            $sellerTotals = [];

            foreach ($order->items as $item) {
                $sellerProduct = $this->sellerProductRepository->findByProduct($item->product_id);

                if (! $sellerProduct || ! $sellerProduct->isApproved()) {
                    continue;
                }

                $sellerId = $sellerProduct->seller_id;
                $sellerTotals[$sellerId] = ($sellerTotals[$sellerId] ?? 0) + $item->base_total;
            }

            foreach ($sellerTotals as $sellerId => $total) {
                $seller = $this->sellerRepository->find($sellerId);

                if (! $seller?->isApproved()) {
                    continue;
                }

                // Use subscription plan commission rate if available, else seller's default
                $commissionRate = $seller->subscription?->plan?->commission_rate
                    ?? $seller->commission_rate;

                $this->marketplaceOrderRepository->create([
                    'order_id'          => $order->id,
                    'seller_id'         => $sellerId,
                    'base_total'        => $total,
                    'commission_amount' => round($total * ($commissionRate / 100), 4),
                    'seller_total'      => round($total * (1 - $commissionRate / 100), 4),
                    'commission_rate'   => $commissionRate,
                    'commission_status' => 'pending',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('[Marketplace] OrderPlaced listener failed: '.$e->getMessage());
        }
    }
}
