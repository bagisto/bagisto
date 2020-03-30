<?php

namespace Webkul\BookingProduct\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\Checkout\Facades\Cart;

class RentalSlot extends Booking
{
    /**
     * Returns slots for a perticular day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @param  string  $date
     * @return array
     */
    public function getSlotsByDate($bookingProduct, $date)
    {
        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        if (! is_array($bookingProductSlot->slots) || ! count($bookingProductSlot->slots)) {
            return [];
        }

        $requestedDate = Carbon::createFromTimeString($date . " 00:00:00");

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                         ? Carbon::createFromTimeString($bookingProduct->available_from)
                         : Carbon::createFromTimeString($currentTime->format('Y-m-d 00:00:00'));

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                       ? Carbon::createFromTimeString($bookingProduct->available_to)
                       : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
                         ? $bookingProductSlot->slots
                         : $bookingProductSlot->slots[$requestedDate->format('w')] ?? [];

        if ($requestedDate < $availableFrom
            || $requestedDate > $availableTo
        ) {
            return [];
        }

        $slots = [];

        foreach ($timeDurations as $index => $timeDuration) {
            $fromChunks = explode(':', $timeDuration['from']);
            $toChunks = explode(':', $timeDuration['to']);

            $startDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $startDayTime->addMinutes(($fromChunks[0] * 60) + $fromChunks[1]);
            $tempStartDayTime = clone $startDayTime;

            $endDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $endDayTime->addMinutes(($toChunks[0] * 60) + $toChunks[1]);

            while (1) {
                $from = clone $tempStartDayTime;
                $tempStartDayTime->addMinutes(60);

                $to = clone $tempStartDayTime;

                if (($startDayTime <= $from && $from <= $availableTo)
                    && ($availableTo >= $to && $to >= $startDayTime)
                    && ($startDayTime <= $from && $from <= $endDayTime)
                    && ($endDayTime >= $to && $to >= $startDayTime)
                ) {
                    // Get already ordered qty for this slot
                    $orderedQty = 0;

                    $qty = isset($timeDuration['qty']) ? ( $timeDuration['qty'] - $orderedQty ) : 1;

                    if ($qty && $currentTime <= $from) {
                        if (! isset($slots[$index])) {
                            $slots[$index]['time'] = $startDayTime->format('h:i A') . ' - ' . $endDayTime->format('h:i A');
                        }

                        $slots[$index]['slots'][] = [
                            'from'           => $from->format('h:i A'),
                            'to'             => $to->format('h:i A'),
                            'from_timestamp' => $from->getTimestamp(),
                            'to_timestamp'   => $to->getTimestamp(),
                            'qty'            => $qty,
                        ];
                    }
                } else {
                    break;
                }
            }
        }

        return $slots;
    }

    /**
     * @param  array  $data
     * @return int
     */
    public function getBookedQuantity($data)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $data['product_id']);

        $rentingType = $products[0]['additional']['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::createFromTimeString($data['additional']['booking']['date_from'] . ' 00:00:01')->getTimestamp();

            $to = Carbon::createFromTimeString($data['additional']['booking']['date_to'] . ' 23:59:59')->getTimestamp();
        } else {
            $from = Carbon::createFromTimestamp($data['additional']['booking']['slot']['from'])->getTimestamp();

            $to = Carbon::createFromTimestamp($data['additional']['booking']['slot']['to'])->getTimestamp();
        }

        $result = $this->bookingRepository->getModel()
                       ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                       ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                       ->where('bookings.product_id', $data['product_id'])
                       ->where(function ($query) use($from, $to) {
                           $query->where(function ($query) use($from) {
                               $query->where('bookings.from', '<=', $from)->where('bookings.to', '>=', $from);
                           })
                           ->orWhere(function($query) use($to) {
                               $query->where('bookings.from', '<=', $to)->where('bookings.to', '>=', $to);
                           });
                       })
                       ->first();

        return ! is_null($result->total_qty_booked) ? $result->total_qty_booked : 0;
    }

    /**
     * @param  \Webkul\Ceckout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isSlotExpired($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        if (isset($cartItem['additional']['booking']['date'])) {
            $timeIntervals = $this->getSlotsByDate($bookingProduct, $cartItem['additional']['booking']['date']);

            $isExpired = true;

            foreach ($timeIntervals as $timeInterval) {
                foreach ($timeInterval['slots'] as $slot) {
                    if ($slot['from_timestamp'] == $cartItem['additional']['booking']['slot']['from']
                        && $slot['to_timestamp'] == $cartItem['additional']['booking']['slot']['to']
                    ) {
                        $isExpired = false;
                    }
                }
            }

            return $isExpired;
        } else {
            $currentTime = Carbon::now();
            
            $requestedFromDate = Carbon::createFromTimeString($cartItem['additional']['booking']['date_from'] . " 00:00:00");

            $requestedToDate = Carbon::createFromTimeString($cartItem['additional']['booking']['date_to'] . " 23:59:59");

            $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                        ? Carbon::createFromTimeString($bookingProduct->available_from->format('Y-m-d') . ' 00:00:00')
                        : Carbon::createFromTimeString($currentTime->format('Y-m-d 00:00:00'));

            $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                    ? Carbon::createFromTimeString($bookingProduct->available_to->format('Y-m-d') . ' 23:59:59')
                    : Carbon::createFromTimeString('2080-01-01 00:00:00');

            if ($requestedFromDate < $availableFrom
                || $requestedFromDate > $availableTo
                || $requestedToDate < $availableFrom
                || $requestedToDate > $availableTo
            ) {
                return true;
            }

            return false;
        }
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param  array  $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $products[0]['product_id']);

        $rentingType = $products[0]['additional']['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::createFromTimeString($products[0]['additional']['booking']['date_from'] . " 00:00:00");
            $to = Carbon::createFromTimeString($products[0]['additional']['booking']['date_to'] . " 24:00:00");

            $price = $bookingProduct->rental_slot->daily_price * $to->diffInDays($from);
        } else {
            $from = Carbon::createFromTimestamp($products[0]['additional']['booking']['slot']['from']);
            $to = Carbon::createFromTimestamp($products[0]['additional']['booking']['slot']['to']);

            $price = $bookingProduct->rental_slot->hourly_price * $to->diffInHours($from);
        }

        $products[0]['price'] += core()->convertPrice($price);
        $products[0]['base_price'] += $price;
        $products[0]['total'] += (core()->convertPrice($price) * $products[0]['quantity']);
        $products[0]['base_total'] += ($price * $products[0]['quantity']);

        return $products;
    }

    /**
     * Validate cart item product price
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return void|null
     */
    public function validateCartItem($item)
    {
        $price = $item->product->getTypeInstance()->getFinalPrice();

        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $item->product_id);

        $rentingType = $item->additional['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            if (! isset($item->additional['booking']['date_from'])
                || ! isset($item->additional['booking']['date_to'])
            ) {
                Cart::removeItem($item->id);

                return true;
            }
            
            $from = Carbon::createFromTimeString($item->additional['booking']['date_from'] . " 00:00:00");
            $to = Carbon::createFromTimeString($item->additional['booking']['date_to'] . " 24:00:00");

            $price += $bookingProduct->rental_slot->daily_price * $to->diffInDays($from);
        } else {
            if (! isset($item->additional['booking']['slot']['from'])
                || ! isset($item->additional['booking']['slot']['to'])
            ) {
                Cart::removeItem($item->id);

                return true;
            }

            $from = Carbon::createFromTimestamp($item->additional['booking']['slot']['from']);
            $to = Carbon::createFromTimestamp($item->additional['booking']['slot']['to']);

            $price += $bookingProduct->rental_slot->hourly_price * $to->diffInHours($from);
        }

        if ($price == $item->base_price) {
            return;
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}