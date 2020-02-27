<?php

namespace Webkul\BookingProduct\Helpers;

/**
 * TableSlot Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TableSlot extends Booking
{
    /**
     * @param CartItem|array $cartItem
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

        if ($bookingProduct->qty - $bookedQty < $requestedQty) {
            return false;
        }

        return true;
    }
}