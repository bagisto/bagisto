<?php

namespace Webkul\RMA\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Webkul\Sales\Contracts\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\RMA\Repositories\RMARepository;

class Helper
{
    /**
     * Rma in declined status.
     *
     * @var string
     */
    public const DECLINED = 'Declined';

    /**
     * rma refund-related statuses
     */
    public const REFUND_EXCLUDED_STATUSES = ['Received Package', 'Declined', 'Canceled', 'Solved', 'Item Canceled'];

    /**
     * Rma in canceled status.
     *
     * @var string
     */
    public const CANCELED = 'Canceled';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RMAItemRepository $rmaItemsRepository,
        protected OrderItemRepository $orderItemRepository,
        protected ProductRepository $productRepository,
        protected RMARepository $rmaRepository,
    ) {
    }

    /**
     * Get html for option details.
     */
    public function getOptionDetailHtml(array $attributes): string
    {
        $attributeValue = '';

        foreach ($attributes as $attribute) {
            if (! empty($attribute)) {
                $attributeValue .= $attribute['attribute_name'] . ': ' . $attribute['option_label']. " </br> ";
            }
        }

        return $attributeValue != '' ? "($attributeValue)" : '';
    }

    /**
     * Get rma status
     */
    public function getRMAStatus(int $orderItemId): array
    {
        $orderItem = $this->orderItemRepository->find($orderItemId);

        if (! $orderItem) {
            return [
                'qty' => 0,
                'message' => trans('admin::app.rma.sales.invoice.create.order-item-not-found')
            ];
        }

        // Fetch RMA items for the order item and eager load their RMA parent
        $rmaItems = $this->rmaItemsRepository
            ->with('rma') // Eager load the RMA relation
            ->where('order_item_id', $orderItemId)
            ->get();

        $rmaQty = $rmaItems->reduce(function ($carry, $rmaItem) {
            $rmaStatus = $rmaItem->rma->rma_status ?? null;

            if (!in_array($rmaStatus, [self::DECLINED, self::CANCELED])) {
                return $carry + $rmaItem->quantity;
            }

            return $carry;
        }, 0);

        $remainingQty = $orderItem->qty_ordered - $rmaQty;

        return [
            'qty'     => $remainingQty,
            'message' => $rmaQty > 0
                ? trans('admin::app.rma.sales.invoice.create.rma-created-message', ['qty' => $rmaQty])
                : '',
        ];
    }

    /**
     * Get refund status
     */
    public function getRefundStatus(int $orderItemId): bool
    {
        $rmaItems = $this->rmaItemsRepository
            ->with('rma')
            ->where('order_item_id', $orderItemId)
            ->get();

        if ($rmaItems->isEmpty()) {
            return false;
        }

        return $rmaItems->every(function ($rmaItem) {
            return in_array($rmaItem->rma->rma_status ?? null, self::REFUND_EXCLUDED_STATUSES);
        });
    }

    /**
     * Get order details
     */
    public function getOrderProduct(int $orderId): OrderItem|Collection
    {
        $allowedProductTypes = explode(',', core()->getConfigData('sales.rma.setting.select_allowed_product_type'));

        $orderItems = $this->orderItemRepository->where('order_id', $orderId)
            ->addSelect(
                'product_flat.product_id',
                'product_flat.name',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'product_flat.sku',
                'product_flat.type',
                'order_items.price',
                'order_items.order_id',
                'order_items.id as order_item_id',
                'order_items.qty_ordered as qty_ordered',
                'order_items.qty_shipped',
                'order_items.qty_invoiced',
                'order_items.created_at',
                'order_items.additional',
                'product_images.path as base_image',
                'orders.status as order_status',
                'orders.id as order_id',
                'products.type as product_type',
                'products.allow_rma',
                'products.rma_rules',
                'rma_rules.id as rule_id',
                'rma_rules.status as rma_rule_status',
                'rma_rules.exchange_period as rma_exchange_period',
                'rma_rules.return_period as rma_return_period',
                'parent_products.id as parentId',
            )
            ->leftJoin('product_flat', 'order_items.product_id', '=', 'product_flat.product_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('rma_rules', 'products.rma_rules', '=', 'rma_rules.id')
            ->leftJoin('products as parent_products', 'products.parent_id', '=', 'parent_products.id')
            ->leftJoin('product_images', 'product_flat.product_id', '=', 'product_images.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNull('order_items.parent_id')
            ->where(function ($query) use ($allowedProductTypes) {
                $query->where(function ($subQuery) use ($allowedProductTypes) {
                    $subQuery->whereNull('products.parent_id')
                             ->whereIn('products.type', $allowedProductTypes);
                })->orWhere(function ($subQuery) use ($allowedProductTypes) {
                    $subQuery->whereNotNull('products.parent_id')
                        ->whereIn('parent_products.type', $allowedProductTypes);
                });
            })
            ->where('product_flat.locale', app()->getLocale())
            ->whereRaw('order_items.qty_ordered > (order_items.qty_refunded + order_items.qty_canceled)')
            ->groupBy('order_items.id')
            ->get();

        $orderItems = $orderItems->filter(function ($orderItem) {
            $createdAt = Carbon::parse($orderItem->created_at);
            $today = Carbon::now();

            if ($orderItem->allow_rma && $orderItem->rma_rule_status) {
                $returnWindow = (int) $orderItem->rma_return_period;
            } else {
                $returnWindow = (int) core()->getConfigData('sales.rma.setting.default_allow_days');
            }

            $returnLastDate = $createdAt->copy()->addDays($returnWindow);

            return $returnLastDate->gte($today);
        })->values();

        foreach ($orderItems as $orderItem) {
            $attributes = '';

            if (! empty($orderItem->additional['attributes'])) {
                $attributes = $orderItem->additional['attributes'];
            }

            $rmaQuantity = $this->rmaItemsRepository->where('order_item_id', $orderItem->order_item_id)->pluck('quantity')->sum();

            $orderItem->rma_quantity = $rmaQuantity;

            $orderItem->attributes = $attributes;

            $orderItem->currentQuantity = $orderItem->qty_ordered - $rmaQuantity;
        }

        return $orderItems;
    }
}
