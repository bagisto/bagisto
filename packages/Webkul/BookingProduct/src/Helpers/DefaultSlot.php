<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;
use Webkul\BookingProduct\Contracts\BookingProduct;
use Webkul\BookingProduct\Contracts\BookingProductTableSlot;

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
     * @param  BookingProduct  $bookingProduct
     */
    public function getSlotsByDate($bookingProduct, string $date): array
    {
        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        if (empty($bookingProductSlot->slots)) {
            return [];
        }

        $requestedDate = Carbon::createFromTimeString($date.' 00:00:00');

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
            ? Carbon::parse($bookingProduct->available_from)->startOfDay()
            : Carbon::now()->copy()->startOfDay();

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_to
            ? Carbon::parse($bookingProduct->available_to)->endOfDay()
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
     * @param  BookingProductTableSlot  $bookingProductSlot
     */
    public function getOneBookingForManyDaysSlots($bookingProductSlot, object $requestedDate)
    {
        $slots = [];

        foreach ($bookingProductSlot->slots as $timeDuration) {
            $fromDayIndex = (int) $timeDuration['from_day'];
            $toDayIndex = (int) $timeDuration['to_day'];
            $currentDay = $requestedDate->dayOfWeek;

            $inRange = $fromDayIndex <= $toDayIndex
                ? ($currentDay >= $fromDayIndex && $currentDay <= $toDayIndex)
                : ($currentDay >= $fromDayIndex || $currentDay <= $toDayIndex);

            if (! $inRange) {
                continue;
            }

            $dayDiff = $fromDayIndex <= $currentDay
                ? $currentDay - $fromDayIndex
                : 7 - $fromDayIndex + $currentDay;

            $startDate = (clone $requestedDate)->subDays($dayDiff);

            $endDayDiff = $fromDayIndex <= $toDayIndex
                ? $toDayIndex - $fromDayIndex
                : 7 - $fromDayIndex + $toDayIndex;

            $endDate = (clone $startDate)->addDays($endDayDiff);

            $startDate = Carbon::createFromTimeString($startDate->format('Y-m-d').' '.$timeDuration['from'].':00');

            $endDate = Carbon::createFromTimeString($endDate->format('Y-m-d').' '.$timeDuration['to'].':00');

            if ($startDate <= Carbon::now()) {
                continue;
            }

            $slots[] = [
                'from' => $startDate->format('D, d M h:i A'),
                'to' => $endDate->format('D, d M h:i A'),
                'timestamp' => $startDate->getTimestamp().'-'.$endDate->getTimestamp(),
            ];
        }

        return $slots;
    }

    /**
     * Returns slots for Many Bookings for One Day
     *
     * @param  BookingProductTableSlot  $bookingProductSlot
     */
    public function getManyBookingsForOneDaySlots($bookingProductSlot, object $requestedDate)
    {
        return $this->slotsCalculation($bookingProductSlot->booking_product, $requestedDate, $bookingProductSlot);
    }
}
