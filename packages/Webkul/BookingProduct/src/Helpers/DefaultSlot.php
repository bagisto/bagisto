<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

class DefaultSlot extends Booking
{
    /**
     * @return array
     */
    protected $daysOfWeek = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    /**
     * Returns slots for a particular day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     */
    public function getSlotsByDate($bookingProduct, string $date): array
    {
        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        if (empty($bookingProductSlot->slots)) {
            return [];
        }

        $requestedDate = Carbon::createFromTimeString($date.' 00:00:00');

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
            ? Carbon::createFromTimeString($bookingProduct->available_from)
            : Carbon::now()->copy()->startOfDay();

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
            ? Carbon::createFromTimeString($bookingProduct->available_to)
            : Carbon::createFromTimeString('2080-01-01 00:00:00');

        if (
            $requestedDate < $availableFrom
            || $requestedDate > $availableTo
        ) {
            return [];
        }

        return $bookingProductSlot->booking_type == 'one'
            ? $this->getOneBookingForManyDaysSlots($bookingProductSlot, $requestedDate)
            : $this->getManyBookingsForOneDaySlots($bookingProductSlot, $requestedDate);
    }

    /**
     * Returns slots for One Booking For Many Days
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProductTableSlot  $bookingProductSlot
     */
    public function getOneBookingForManyDaysSlots($bookingProductSlot, object $requestedDate)
    {
        $slots = [];

        foreach ($bookingProductSlot->slots as $key => $timeDuration) {
            if ($requestedDate->dayOfWeek != $timeDuration['from_day']) {
                continue;
            }

            $startDate = (clone $requestedDate)->modify('this '.$this->daysOfWeek[$timeDuration['from_day']]);

            $endDate = (clone $requestedDate)->modify('this '.$this->daysOfWeek[$timeDuration['to_day']]);

            $startDate = Carbon::createFromTimeString($startDate->format('Y-m-d').' '.$timeDuration['from'].':00');

            $endDate = Carbon::createFromTimeString($endDate->format('Y-m-d').' '.$timeDuration['to'].':00');

            $slots[] = [
                'from'      => $startDate->format('h:i A'),
                'to'        => $endDate->format('h:i A'),
                'timestamp' => $startDate->getTimestamp().'-'.$endDate->getTimestamp(),
            ];
        }

        return $slots;
    }

    /**
     * Returns slots for Many Bookings for One Day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProductTableSlot  $bookingProductSlot
     */
    public function getManyBookingsForOneDaySlots($bookingProductSlot, object $requestedDate)
    {
        return $this->slotsCalculation($bookingProductSlot->booking_product, $requestedDate, $bookingProductSlot);
    }
}
