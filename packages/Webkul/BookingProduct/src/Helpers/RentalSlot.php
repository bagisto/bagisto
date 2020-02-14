<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

/**
 * RentalSlot Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RentalSlot extends Booking
{
    /**
     * Returns slots for a perticular day
     *
     * @param BookingProduct $bookingProduct
     * @param string         $date
     * @return array
     */
    public function getSlotsByDate($bookingProduct, $date)
    {
        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        if (! is_array($bookingProductSlot->slots) || ! count($bookingProductSlot->slots))
            return [];

        $requestedDate = Carbon::createFromTimeString($date . " 00:00:00");

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProductSlot->available_every_week
                ? Carbon::createFromTimeString($bookingProductSlot->available_from . ' 00:00:00')
                : Carbon::createFromTimeString($currentTime->format('Y-m-d') . ' 00:00:00');

        $availableTo = ! $bookingProductSlot->available_every_week
                ? Carbon::createFromTimeString($bookingProductSlot->available_to . ' 23:59:59')
                : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
                ? $bookingProductSlot->slots
                : $bookingProductSlot->slots[$requestedDate->format('w')];

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
                    && ($endDayTime >= $to && $to >= $startDayTime)) {
                        
                    // Get already ordered qty for this slot
                    $orderedQty = 0;

                    $qty = isset($timeDuration['qty']) ? ( $timeDuration['qty'] - $orderedQty ) : 1;

                    if ($qty && $currentTime <= $from) {
                        if (! isset($slots[$index]))
                            $slots[$index]['time'] = $startDayTime->format('h:i A') . ' - ' . $endDayTime->format('h:i A');

                        $slots[$index]['slots'][] = [
                            'from' => $from->format('h:i A'),
                            'to' => $to->format('h:i A'),
                            'from_timestamp' => $from->getTimestamp(),
                            'to_timestamp' => $to->getTimestamp(),
                            'qty' => $qty
                        ];
                    }
                } else {
                    break;
                }
            }
        }

        return $slots;
    }
}