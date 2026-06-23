<?php

namespace Webkul\RMA\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\RMA\Repositories\RMARepository;
use Webkul\Sales\Repositories\OrderItemRepository;

class Helper
{
    /**
     * Statuses that exclude refund after RMA.
     */
    public const REFUND_EXCLUDED_STATUSES = [
        DefaultRMAStatusEnum::RECEIVED_PACKAGE->value,
        DefaultRMAStatusEnum::DECLINED->value,
        DefaultRMAStatusEnum::CANCELED->value,
        DefaultRMAStatusEnum::SOLVED->value,
        DefaultRMAStatusEnum::ITEM_CANCELED->value,
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMARepository $rmaRepository,
    ) {}

    /**
     * Get remaining quantity after RMA.
     */
    public function getRemainingQtyAfterRMA(int $orderItemId): array
    {
        $orderItem = $this->orderItemRepository->find($orderItemId);

        if (! $orderItem) {
            return [
                'qty' => 0,
                'message' => trans('admin::app.sales.rma.invoice.create.order-item-not-found'),
            ];
        }

        $rmaItems = $this->rmaItemsRepository
            ->with('rma')
            ->where('order_item_id', $orderItemId)
            ->get();

        $rmaQty = $rmaItems->reduce(function ($carry, $rmaItem) {
            $rmaStatus = $rmaItem->rma->rma_status_id ?? null;

            if (! in_array($rmaStatus, [DefaultRMAStatusEnum::DECLINED->value, DefaultRMAStatusEnum::CANCELED->value])) {
                return $carry + $rmaItem->quantity;
            }

            return $carry;
        }, 0);

        $remainingQty = $orderItem->qty_ordered - $rmaQty;

        return [
            'qty' => $remainingQty,
            'message' => $rmaQty > 0
                ? trans('admin::app.sales.rma.invoice.create.rma-created-message', ['qty' => $rmaQty])
                : '',
        ];
    }

    /**
     * Check if can refund after RMA.
     */
    public function canRefundAfterRMA(int $orderItemId): bool
    {
        $rmaItems = $this->rmaItemsRepository
            ->with('rma')
            ->where('order_item_id', $orderItemId)
            ->get();

        if ($rmaItems->isEmpty()) {
            return false;
        }

        return $rmaItems->every(function ($rmaItem) {
            return in_array($rmaItem->rma->rma_status_id ?? null, self::REFUND_EXCLUDED_STATUSES);
        });
    }

    /**
     * Get order items eligible for RMA.
     */
    public function getOrderItems(int $orderId): Collection
    {
        $locale = app()->getLocale();

        $today = Carbon::now();

        $tablePrefix = DB::getTablePrefix();

        $orderItems = $this->orderItemRepository
            ->where('order_id', $orderId)
            ->whereNull('order_items.parent_id')
            ->where('product_flat.locale', $locale)
            ->whereRaw("{$tablePrefix}order_items.qty_ordered > ({$tablePrefix}order_items.qty_refunded + {$tablePrefix}order_items.qty_canceled)")
            ->select([
                'product_flat.product_id',
                'product_flat.name',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'product_flat.sku',
                'product_flat.type',
                'order_items.price',
                'order_items.order_id',
                'order_items.id as order_item_id',
                'order_items.qty_ordered',
                'order_items.qty_canceled',
                'order_items.qty_shipped',
                'order_items.qty_refunded',
                'order_items.qty_invoiced',
                'order_items.created_at',
                'order_items.additional',
                'order_items.rma_return_period',
                'product_images.path as base_image',
                'orders.status as order_status',
                'orders.id as order_id',
                'products.type as product_type',
                'parent_products.id as parentId',
            ])
            ->leftJoin('product_flat', 'order_items.product_id', '=', 'product_flat.product_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('products as parent_products', 'products.parent_id', '=', 'parent_products.id')
            ->leftJoin('product_images', 'product_flat.product_id', '=', 'product_images.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('order_items.id')
            ->get();

        $orderItemIds = $orderItems->pluck('order_item_id')->toArray();

        /**
         * Fetch RMA quantities for the order items to avoid N+1 query issue.
         */
        $rmaQuantities = $this->rmaItemsRepository
            ->whereIn('order_item_id', $orderItemIds)
            ->groupBy('order_item_id')
            ->selectRaw('order_item_id, SUM(quantity) as total_quantity')
            ->pluck('total_quantity', 'order_item_id');

        /**
         * Filter items within the return window and enrich with additional data.
         */
        $filteredItems = $orderItems->filter(function ($orderItem) use ($today) {
            if (is_null($orderItem->rma_return_period)) {
                return false;
            }

            $returnWindow = $this->calculateReturnWindow($orderItem);

            $returnLastDate = Carbon::parse($orderItem->created_at)->addDays($returnWindow);

            return $returnLastDate->gte($today);
        })->map(function ($orderItem) use ($rmaQuantities) {
            $rmaQuantity = $rmaQuantities[$orderItem->order_item_id] ?? 0;

            $orderItem->rma_quantity = $rmaQuantity;
            $orderItem->attributes = $orderItem->additional['attributes'] ?? '';
            $orderItem->currentQuantity = $orderItem->qty_ordered - $rmaQuantity;
            $orderItem->forReturnQuantity = $orderItem->qty_invoiced - $orderItem->qty_refunded;
            $orderItem->forCancelQuantity = $orderItem->qty_ordered - $orderItem->qty_invoiced - $orderItem->qty_canceled;

            return $orderItem;
        })->values();

        return $filteredItems;
    }

    /**
     * Calculate return window for an order item.
     */
    private function calculateReturnWindow($orderItem): int
    {
        return (int) $orderItem->rma_return_period;
    }
}
