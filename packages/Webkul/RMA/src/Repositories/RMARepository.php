<?php

namespace Webkul\RMA\Repositories;

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
        return core()->getConfigData('sales.rma.setting.allowed_new_rma_request_for_cancelled_request') === 'yes'
            && $rma->rma_status_id === DefaultRMAStatusEnum::CANCELED->value;
    }

    /**
     * Check if RMA is expired.
     */
    public function isRmaExpired($rma): bool
    {
        $expireDays = (int) core()->getConfigData('sales.rma.setting.default_allow_days');

        $daysSinceCreation = now()->diffInDays($rma->created_at);

        return $daysSinceCreation > $expireDays && $daysSinceCreation !== 0;
    }
}
