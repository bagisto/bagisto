<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SellerOrder;

class SellerOrderRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SellerOrder::class;
    }

    /**
     * Get orders for a seller.
     */
    public function getSellerOrders(int $sellerId)
    {
        return $this->findByField('seller_id', $sellerId);
    }

    /**
     * Get seller earnings summary.
     */
    public function getEarningsSummary(int $sellerId): array
    {
        $result = DB::table('marketplace_seller_orders')
            ->where('seller_id', $sellerId)
            ->selectRaw('
                SUM(base_grand_total) as total_sales,
                SUM(base_commission) as total_commission,
                SUM(base_seller_total) as total_earnings,
                COUNT(*) as total_orders
            ')
            ->first();

        return [
            'total_sales'      => (float) ($result->total_sales ?? 0),
            'total_commission'  => (float) ($result->total_commission ?? 0),
            'total_earnings'    => (float) ($result->total_earnings ?? 0),
            'total_orders'      => (int) ($result->total_orders ?? 0),
        ];
    }
}
