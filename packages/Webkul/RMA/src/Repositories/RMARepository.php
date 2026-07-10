<?php

namespace Webkul\RMA\Repositories;

use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMA;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\Sales\Models\Order;

class RMARepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMA::class;
    }

    /**
     * Check if RMA can be closed.
     */
    public function canCloseRma($rma): bool
    {
        if (empty($rma->rma_status_id)) {
            return false;
        }

        $nonCloseableStatuses = [
            DefaultRMAStatusEnum::RECEIVED_PACKAGE->value,
            DefaultRMAStatusEnum::SOLVED->value,
            DefaultRMAStatusEnum::ITEM_CANCELED->value,
            DefaultRMAStatusEnum::DECLINED->value,
            DefaultRMAStatusEnum::CANCELED->value,
        ];

        if (in_array($rma->rma_status_id, $nonCloseableStatuses, true)) {
            return false;
        }

        $nonCloseableOrderStatuses = [Order::STATUS_CANCELED, Order::STATUS_CLOSED];

        return ! in_array($rma->order->status, $nonCloseableOrderStatuses, true);
    }

    /**
     * Check if RMA can be reopened.
     */
    public function canReopenRma($rma): bool
    {
        if (
            $rma->rma_status_id === DefaultRMAStatusEnum::CANCELED->value
            && core()->getConfigData('sales.rma.setting.allowed_new_rma_request_for_cancelled_request') === 'yes'
        ) {
            return true;
        }

        if (
            $rma->rma_status_id === DefaultRMAStatusEnum::DECLINED->value
            && core()->getConfigData('sales.rma.setting.allowed_new_rma_request_for_declined_request') === 'yes'
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if the RMA is past its return window.
     *
     * The window is the same one that governs whether an item is returnable in
     * the first place - the order item's snapshotted return period from its
     * order date. This keeps "can I still act on this RMA" consistent with "was
     * this item returnable", instead of an unrelated countdown. When the
     * originating order item / snapshot is unavailable it falls back to the
     * configured default window measured from the RMA's own creation date.
     */
    public function isRmaExpired($rma): bool
    {
        $orderItem = $rma->item?->orderItem;

        if (! $orderItem || is_null($orderItem->rma_return_period)) {
            $expireDays = (int) core()->getConfigData('sales.rma.setting.default_allow_days');

            return Carbon::parse($rma->created_at)->addDays($expireDays)->isPast();
        }

        return Carbon::parse($orderItem->created_at)
            ->addDays((int) $orderItem->rma_return_period)
            ->isPast();
    }
}
