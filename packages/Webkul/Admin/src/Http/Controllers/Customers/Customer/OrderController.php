<?php

namespace Webkul\Admin\Http\Controllers\Customers\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\OrderItemResource;
use Webkul\Sales\Repositories\OrderItemRepository;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected OrderItemRepository $orderItemRepository) {}

    /**
     * Retrieve the recent order items for the given customer, one per product.
     */
    public function recentItems(int $id): JsonResource
    {
        $prefix = DB::getTablePrefix();

        $orderItems = $this->orderItemRepository
            ->leftJoin('orders', 'order_items.order_id', 'orders.id')
            ->whereNull('order_items.parent_id')
            ->where('orders.customer_id', $id)
            ->whereIn('order_items.id', function ($query) use ($id, $prefix) {
                $query->selectRaw("MAX({$prefix}order_items.id)")
                    ->from('order_items')
                    ->leftJoin('orders', 'order_items.order_id', 'orders.id')
                    ->whereNull('order_items.parent_id')
                    ->where('orders.customer_id', $id)
                    ->groupBy('order_items.product_id');
            })
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();

        return OrderItemResource::collection($orderItems);
    }
}
