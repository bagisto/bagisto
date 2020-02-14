<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;
use Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductAppointmentSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductEventSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductTableSlotRepository;

/**
 * Booking Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Booking
{
    /**
     * @return array
     */
    protected $typeRepositories = [];

    /**
     * @return array
     */
    protected $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    /**
     * Create a new helper instance.
     *
     * @param Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository     $bookingProductDefaultSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductEventSlotRepository       $bookingProductEventSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository      $bookingProductRentalSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductTableSlotRepository       $bookingProductTableSlotRepository
     * @return void
     */
    public function __construct(
        BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository,
        BookingProductEventSlotRepository $bookingProductEventSlotRepository,
        BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        BookingProductTableSlotRepository $bookingProductTableSlotRepository
    )
    {
        $this->typeRepositories['default'] = $bookingProductDefaultSlotRepository;

        $this->typeRepositories['appointment'] = $bookingProductAppointmentSlotRepository;

        $this->typeRepositories['event'] = $bookingProductEventSlotRepository;

        $this->typeRepositories['rental'] = $bookingProductRentalSlotRepository;

        $this->typeRepositories['table'] = $bookingProductTableSlotRepository;
    }

    /**
     * Returns the booking information
     *
     * @param BookingProduct $bookingProduct
     * @return array
     */
    public function getWeekSlotDurations($bookingProduct)
    {
        $slotsByDays = [];

        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        $availabileDays = $this->getAvailableWeekDays($bookingProductSlot);

        foreach ($this->daysOfWeek as $index => $isOpen) {
            $slots = [];

            if ($isOpen)
                $slots = $bookingProductSlot->same_slot_all_days ? $bookingProductSlot->slots : ($bookingProductSlot->slots[$index] ?? []);

            $slotsByDays[] = [
                'name' => trans($this->daysOfWeek[$index]),
                'slots' => isset($availabileDays[$index]) ? $this->conver24To12Hours($slots) : []
            ];
        }

        return $slotsByDays;
    }

    /**
     * Returns html of slots for a current day
     *
     * @param BookingProduct $bookingProduct
     * @return string
     */
    public function getTodaySlotsHtml($bookingProduct)
    {
        $slots = [];

        foreach ($this->getTodaySlots($bookingProduct) as $slot) {
            $slots[] = $slot['from'] . ' - ' . $slot['to'];
        }

        return count($slots)
            ? implode(' | ', $slots)
            : '<span class="text-danger">' . trans('bookingproduct::app.shop.products.closed') . '</span>';
    }

    /**
     * Returns slots for a current day
     *
     * @param BookingProduct $bookingProduct
     * @return array
     */
    public function getTodaySlots($bookingProduct)
    {
        $weekSlots = $this->getWeekSlotDurations($bookingProduct);

        return $weekSlots[Carbon::now()->format('w')]['slots'];
    }

    /**
     * Returns the available week days
     *
     * @param Object $bookingProductSlot
     * @return array
     */
    public function getAvailableWeekDays($bookingProductSlot)
    {
        if ($bookingProductSlot->available_every_week)
            return $this->daysOfWeek;

        $days = [];

        $currentTime = Carbon::now();

        $availableFrom = Carbon::createFromTimeString($bookingProductSlot->available_from . " 00:00:01");

        $availableTo = Carbon::createFromTimeString($bookingProductSlot->available_to . " 23:59:59");

        for ($i = 0; $i < 7; $i++) {
            $date = clone $currentTime;
            $date->addDays($i);

            if ($date >= $availableFrom && $date <= $availableTo)
                $days[$i] = $date->format('l');
        }

        return $this->sortDaysOfWeek($days);
    }

    /**
     * Sort days
     *
     * @param array $days
     * @return array
     */
    public function sortDaysOfWeek($days)
    {
        $daysAux = [];

        foreach ($days as $day) {
            $key = array_search($day, $this->daysOfWeek);

            if ($key !== FALSE) {
                $daysAux[$key] = $day;
            }
        }

        ksort($daysAux);

        return $daysAux;
    }

    /**
     * Convert time from 24 to 12 hour format
     *
     * @param array $slots
     * @return array
     */
    public function conver24To12Hours($slots)
    {
        if (! $slots)
            return [];

        foreach ($slots as $index => $slot) {
            $slots[$index]['from']  = Carbon::createFromTimeString($slot['from'])->format("h:i a");
            $slots[$index]['to']  = Carbon::createFromTimeString($slot['to'])->format("h:i a");
        }

        return $slots;
    }

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
                ? Carbon::createFromTimeString($bookingProductSlot->available_from . " 00:00:00")
                : Carbon::createFromTimeString($currentTime->format('Y-m-d') . ' 00:00:00');

        $availableTo = ! $bookingProductSlot->available_every_week
                ? Carbon::createFromTimeString($bookingProductSlot->available_to . ' 23:59:59')
                : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
                ? $bookingProductSlot->slots
                : ($bookingProductSlot->slots[$requestedDate->format('w')] ?? []);

        $slots = [];

        foreach ($timeDurations as $timeDuration) {
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
                    && ($endDayTime >= $to && $to >= $startDayTime)) {
                        
                    // Get already ordered qty for this slot
                    $orderedQty = 0;

                    $qty = isset($timeDuration['qty']) ? ( $timeDuration['qty'] - $orderedQty ) : 1;

                    if ($qty && $currentTime <= $from) {
                        $slots[] = [
                                'from' => $from->format('h:i A'), 
                                'to' => $to->format('h:i A'), 
                                'timestamp' => $from->getTimestamp() . '-' . $to->getTimestamp(), 
                                'qty' => $qty, 
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