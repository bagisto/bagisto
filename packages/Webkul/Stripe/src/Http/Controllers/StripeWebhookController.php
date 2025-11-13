<?php

namespace Webkul\Stripe\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;

class StripeWebhookController extends CashierController
{
    /**
     * Create a new controller instance.
     *
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected RefundRepository $refundRepository
    ) {}

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function handleChargeRefunded($payload)
    {
        $orderId = $payload['data']['object']['metadata']['order_id'];

        $amount_captured = $payload['data']['object']['amount_refunded'];

        $amount_captured = $amount_captured / 100;

        $order = $this->orderRepository->findOrFail($orderId);

        $response = '';

        $orderItem = $this->orderItemRepository->where('order_id', $order->id)->get();

        $items = [];

        foreach ($orderItem as $item) {
            $items[$item->id] = $item->qty_ordered;
        }

        $data['refund'] = [
            'items'             => $items,
            'shipping'          => $order->shipping_amount,
            'adjustment_refund' => 0,
            'adjustment_fee'    => ($order->grand_total - $amount_captured),
        ];

        if ($order->canRefund()) {

            if (! $data['refund']['shipping']) {
                $data['refund']['shipping'] = 0;
            }

            $response = $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));
        }

        if ($response) {
            return trans('admin::app.sales.refunds.create.create-success');
        }
    }
}
