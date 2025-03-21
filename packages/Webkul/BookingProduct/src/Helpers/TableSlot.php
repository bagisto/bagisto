<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

class TableSlot extends Booking
{
    /**
     * Return the item if it has a quantity.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $cartItem
     */
    public function isItemHaveQuantity($cartItem): bool
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        if (! $bookingProduct) {
            return false;
        }

        $tableSlot = $bookingProduct->table_slot;

        $preventDays = $tableSlot->prevent_scheduling_before ?? 0;

        $minAllowedDate = Carbon::now()->addDays($preventDays)->format('Y-m-d');

        $bookingDate = $cartItem['additional']['booking']['date'] ?? null;

        if ($bookingDate && $bookingDate < $minAllowedDate) {
            return false;
        }

        $bookedQty = $this->getBookedQuantity($cartItem);

        $requestedQty = $cartItem['quantity'];

        if ($tableSlot->price_type == 'table') {
            $multiplier = $tableSlot->guest_limit;

            $requestedQty *= $multiplier;

            $bookedQty *= $multiplier;
        }

        return $bookingProduct->qty - $bookedQty >= $requestedQty && ! $this->isSlotExpired($cartItem);
    }
}
