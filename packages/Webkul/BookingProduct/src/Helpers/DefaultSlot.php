<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

/**
 * DefaultSlot Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class DefaultSlot extends Booking
{
    /**
     * @return array
     */
    protected $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    
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

        $requestedDate = Carbon::createFromTimeString($date . ' 00:00:00');

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProduct->available_from && $bookingProduct->available_from
                         ? Carbon::createFromTimeString($bookingProduct->available_from)
                         : clone $currentTime;

        $availableTo = ! $bookingProduct->available_from && $bookingProduct->available_to
                       ? Carbon::createFromTimeString($bookingProduct->available_to)
                       : Carbon::createFromTimeString('2080-01-01 00:00:00');

        if ($requestedDate < $currentTime
            || $requestedDate < $availableFrom
            || $requestedDate > $availableTo
        ) {
            return [];
        }

        $slots = [];

        return $bookingProductSlot->booking_type == 'one'
               ? $this->getOneBookingForManyDaysSlots($bookingProductSlot, $requestedDate)
               : $this->getManyBookingsforOneDaySlots($bookingProductSlot, $requestedDate);
    }

    /**
     * Returns slots for One Booking For Many Days
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @param  string   $requestedDate
     * @return array
     */
    public function getOneBookingForManyDaysSlots($bookingProductSlot, $requestedDate)
    {
        $slots = [];

        foreach ($bookingProductSlot->slots as $timeDuration) {
            if ($requestedDate->dayOfWeek != $timeDuration['from_day']) {
                continue;
            }

            $startDate = clone $requestedDate->modify('this ' . $this->daysOfWeek[$timeDuration['from_day']]);
            
            $endDate = clone $requestedDate->modify('this ' . $this->daysOfWeek[$timeDuration['to_day']]);

            $slots[] = [
                'from'      => $startDate->format('d F, Y h:i A'),
                'to'        => $endDate->format('d F, Y h:i A'),
                'timestamp' => $startDate->getTimestamp() . '-' . $endDate->getTimestamp(),
            ];
        }

        return $slots;
    }

    /**
     * Returns slots for Many Bookings for One Day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProductSlot  $bookingProductSlot
     * @param  string  $requestedDate
     * @return array
     */
    public function getManyBookingsforOneDaySlots($bookingProductSlot, $requestedDate)
    {
        $bookingProduct = $bookingProductSlot->booking_product;

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProduct->available_from && $bookingProduct->available_from
                         ? Carbon::createFromTimeString($bookingProduct->available_from)
                         : clone $currentTime;

        $availableTo = ! $bookingProduct->available_from && $bookingProduct->available_to
                       ? Carbon::createFromTimeString($bookingProduct->available_to)
                       : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDuration = $bookingProductSlot->slots[$requestedDate->format('w')] ?? [];

        if (! count($timeDuration) || ! $timeDuration['status']) {
            return [];
        }

        $slots = [];

        $fromChunks = explode(':', $timeDuration['from']);
        $toChunks = explode(':', $timeDuration['to']);

        $startDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
        $startDayTime->addMinutes(($fromChunks[0] * 60) + $fromChunks[1]);
        $tempStartDayTime = clone $startDayTime;

        $endDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
        $endDayTime->addMinutes(($toChunks[0] * 60) + $toChunks[1]);

        $isFirstIteration = true;

        while (1) {
            $from = clone $tempStartDayTime;
            $tempStartDayTime->addMinutes($bookingProductSlot->duration);

            if ($isFirstIteration) {
                $isFirstIteration = false;
            } else {
                $from->modify('+' . $bookingProductSlot->break_time . ' minutes');
                $tempStartDayTime->modify('+' . $bookingProductSlot->break_time . ' minutes');
            }

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
                    $slots[] = [
                        'from'      => $from->format('h:i A'), 
                        'to'        => $to->format('h:i A'), 
                        'timestamp' => $from->getTimestamp() . '-' . $to->getTimestamp(), 
                        'qty'       => $qty, 
                    ];
                }
            } else {
                break;
            }
        }

        return $slots;
    }
}