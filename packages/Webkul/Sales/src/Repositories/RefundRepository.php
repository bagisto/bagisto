<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Contracts\Refund;
use Webkul\Core\Eloquent\Repository;

/**
 * Refund Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RefundRepository extends Repository
{
    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * RefundItemRepository object
     *
     * @var Object
     */
    protected $refundItemRepository;

    /**
     * Create a new repository instance.
     *
     * @param \Webkul\Sales\Repositories\OrderRepository      $orderRepository
     * @param \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param \Webkul\Sales\Repositories\RefundItemRepository $refundItemRepository
     * @param \Illuminate\Container\Container                 $app
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        RefundItemRepository $refundItemRepository,
        App $app
    )
    {
        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->refundItemRepository = $refundItemRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return Mixed
     */
    function model()
    {
        return Refund::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            Event::fire('sales.refund.save.before', $data);

            $order = $this->orderRepository->find($data['order_id']);

            $totalQty = array_sum($data['refund']['items']);

            $refund = parent::create([
                    'order_id' => $order->id,
                    'total_qty' => $totalQty,
                    'state' => 'refunded',
                    'base_currency_code' => $order->base_currency_code,
                    'channel_currency_code' => $order->channel_currency_code,
                    'order_currency_code' => $order->order_currency_code,
                    'adjustment_refund' => core()->convertPrice($data['refund']['adjustment_refund'], $order->order_currency_code),
                    'base_adjustment_refund' => $data['refund']['adjustment_refund'],
                    'adjustment_fee' => core()->convertPrice($data['refund']['adjustment_fee'], $order->order_currency_code),
                    'base_adjustment_fee' => $data['refund']['adjustment_fee'],
                    'shipping_amount' => core()->convertPrice($data['refund']['shipping'], $order->order_currency_code),
                    'base_shipping_amount' => $data['refund']['shipping']
                ]);

            foreach ($data['refund']['items'] as $itemId => $qty) {
                if (! $qty)
                    continue;

                $orderItem = $this->orderItemRepository->find($itemId);

                if ($qty > $orderItem->qty_to_refund)
                    $qty = $orderItem->qty_to_refund;

                $refundItem = $this->refundItemRepository->create([
                        'refund_id' => $refund->id,
                        'order_item_id' => $orderItem->id,
                        'name' => $orderItem->name,
                        'sku' => $orderItem->sku,
                        'qty' => $qty,
                        'price' => $orderItem->price,
                        'base_price' => $orderItem->base_price,
                        'total' => $orderItem->price * $qty,
                        'base_total' => $orderItem->base_price * $qty,
                        'tax_amount' => ( ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty ),
                        'base_tax_amount' => ( ($orderItem->base_tax_amount / $orderItem->qty_ordered) * $qty ),
                        'discount_amount' => ( ($orderItem->discount_amount / $orderItem->qty_ordered) * $qty ),
                        'base_discount_amount' => ( ($orderItem->base_discount_amount / $orderItem->qty_ordered) * $qty ),
                        'product_id' => $orderItem->product_id,
                        'product_type' => $orderItem->product_type,
                        'additional' => $orderItem->additional
                    ]);

                if ($orderItem->getTypeInstance()->isComposite()) {
                    foreach ($orderItem->children as $childOrderItem) {
                        $finalQty = $childOrderItem->qty_ordered
                                ? ($childOrderItem->qty_ordered / $orderItem->qty_ordered) * $qty
                                : $childOrderItem->qty_ordered;

                        $refundItem->child = $this->refundItemRepository->create([
                                'refund_id' => $refund->id,
                                'order_item_id' => $childOrderItem->id,
                                'parent_id' => $refundItem->id,
                                'name' => $childOrderItem->name,
                                'sku' => $childOrderItem->sku,
                                'qty' => $finalQty,
                                'price' => $childOrderItem->price,
                                'base_price' => $childOrderItem->base_price,
                                'total' => $childOrderItem->price * $finalQty,
                                'base_total' => $childOrderItem->base_price * $finalQty,
                                'tax_amount' => 0,
                                'base_tax_amount' => 0,
                                'discount_amount' => 0,
                                'base_discount_amount' => 0,
                                'product_id' => $childOrderItem->product_id,
                                'product_type' => $childOrderItem->product_type,
                                'additional' => $childOrderItem->additional
                            ]);
                        
                        if ($childOrderItem->product->getTypeInstance()->showQuantityBox())
                            $this->refundItemRepository->returnQtyToProductInventory($childOrderItem, $finalQty);

                        $this->orderItemRepository->collectTotals($childOrderItem);
                    }

                } else {
                    $this->refundItemRepository->returnQtyToProductInventory($orderItem, $qty);
                }

                $this->orderItemRepository->collectTotals($orderItem);
            }

            $this->collectTotals($refund);

            $this->orderRepository->collectTotals($order);

            $this->orderRepository->updateOrderStatus($order);

            Event::fire('sales.refund.save.after', $refund);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $refund;
    }

    /**
     * @param Refund $refund
     * @return mixed
     */
    public function collectTotals($refund)
    {
        $refund->sub_total = $refund->base_sub_total = 0;
        $refund->tax_amount = $refund->base_tax_amount = 0;
        $refund->discount_amount = $refund->base_discount_amount = 0;

        foreach ($refund->items as $refundItem) {
            $refund->sub_total += $refundItem->total;
            $refund->base_sub_total += $refundItem->base_total;

            $refund->tax_amount += $refundItem->tax_amount;
            $refund->base_tax_amount += $refundItem->base_tax_amount;

            $refund->discount_amount += $refundItem->discount_amount;
            $refund->base_discount_amount += $refundItem->base_discount_amount;
        }

        $refund->grand_total = $refund->sub_total + $refund->tax_amount + $refund->shipping_amount + $refund->adjustment_refund - $refund->adjustment_fee - $refund->discount_amount;
        $refund->base_grand_total = $refund->base_sub_total + $refund->base_tax_amount + $refund->base_shipping_amount + $refund->base_adjustment_refund - $refund->base_adjustment_fee - $refund->base_discount_amount;

        $refund->save();

        return $refund;
    }

    /**
     * @param array   $data
     * @param integer $orderId
     * @return array
     */
    public function getOrderItemsRefundSummary($data, $orderId)
    {
        $order = $this->orderRepository->find($orderId);

        $summary = [
            'subtotal' => ['price' => 0],
            'discount' => ['price' => 0],
            'tax' => ['price' => 0],
            'shipping' => ['price' => 0],
            'grand_total' => ['price' => 0]
        ];

        foreach ($data as $orderItemId => $qty) {
            if (! $qty)
                continue;

            $orderItem = $this->orderItemRepository->find($orderItemId);

            if ($qty > $orderItem->qty_to_refund)
                return false;

            $summary['subtotal']['price'] += $orderItem->base_price * $qty;

            $summary['discount']['price'] += ($orderItem->base_discount_amount / $orderItem->qty_ordered) * $qty;

            $summary['tax']['price'] += ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty;
        }

        $summary['shipping']['price'] += $order->base_shipping_invoiced - $order->base_shipping_refunded;

        $summary['grand_total']['price'] += $summary['subtotal']['price'] + $summary['tax']['price'] + $summary['shipping']['price'] - $summary['discount']['price'];

        $summary['subtotal']['formated_price'] = core()->formatBasePrice($summary['subtotal']['price']);

        $summary['discount']['formated_price'] = core()->formatBasePrice($summary['discount']['price']);

        $summary['tax']['formated_price'] = core()->formatBasePrice($summary['tax']['price']);

        $summary['shipping']['formated_price'] = core()->formatBasePrice($summary['shipping']['price']);

        $summary['grand_total']['formated_price'] = core()->formatBasePrice($summary['grand_total']['price']);

        return $summary;
    }
}