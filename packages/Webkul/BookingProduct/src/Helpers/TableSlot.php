<?php

namespace Webkul\BookingProduct\Helpers;

class TableSlot extends Booking
{
    /**
     * @param  \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        $bookedQty = $this->getBookedQuantity($cartItem);

        $requestedQty = $cartItem['quantity'];

        if ($bookingProduct->table_slot->price_type == 'table') {
            $requestedQty *= $bookingProduct->table_slot->guest_limit;

            $bookedQty *= $bookingProduct->table_slot->guest_limit;
        }

        if (
            $bookingProduct->qty - $bookedQty < $requestedQty
            || $this->isSlotExpired($cartItem)
        ) {
            return false;
        }

        return true;
    }
}