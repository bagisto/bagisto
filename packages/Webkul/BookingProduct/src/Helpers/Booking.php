<?php

namespace Webkul\BookingProduct\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductAppointmentSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository;
use Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductTableSlotRepository;
use Webkul\BookingProduct\Repositories\BookingRepository;

/**
 * Booking Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Booking
{
    /**
     * BookingProductRepository
     * 
     * @return object
     */
    protected $bookingProductRepository;

    /**
     * @return array
     */
    protected $typeRepositories = [];

    /**
     * BookingRepository
     * 
     * @return object
     */
    protected $bookingRepository;

    /**
     * @return array
     */
    protected $typeHelpers = [
        'default'     => DefaultSlot::class,
        'appointment' => AppointmentSlot::class,
        'event'       => EventTicket::class,
        'rental'      => RentalSlot::class,
        'table'       => TableSlot::class,
    ];

    /**
     * @return array
     */
    protected $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    /**
     * Create a new helper instance.
     *
     * @param Webkul\BookingProduct\Repositories\BookingProductRepository                $bookingProductRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository     $bookingProductDefaultSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository     $bookingProductEventTicketRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository      $bookingProductRentalSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingProductTableSlotRepository       $bookingProductTableSlotRepository
     * @param Webkul\BookingProduct\Repositories\BookingRepository                       $bookingRepository
     * @return void
     */
    public function __construct(
        BookingProductRepository $bookingProductRepository,
        BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository,
        BookingProductEventTicketRepository $bookingProductEventTicketRepository,
        BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        BookingProductTableSlotRepository $bookingProductTableSlotRepository,
        BookingRepository $bookingRepository
    )
    {
        $this->bookingProductRepository = $bookingProductRepository;

        $this->typeRepositories['default'] = $bookingProductDefaultSlotRepository;

        $this->typeRepositories['appointment'] = $bookingProductAppointmentSlotRepository;

        $this->typeRepositories['event'] = $bookingProductEventTicketRepository;

        $this->typeRepositories['rental'] = $bookingProductRentalSlotRepository;

        $this->typeRepositories['table'] = $bookingProductTableSlotRepository;

        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Returns the booking type hepler instance
     *
     * @param string $type
     * @return array
     */
    public function getTypeHepler($type)
    {
        return $this->typeHelpers[$type];
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

        $availabileDays = $this->getAvailableWeekDays($bookingProduct);

        foreach ($this->daysOfWeek as $index => $isOpen) {
            $slots = [];

            if ($isOpen) {
                $slots = $bookingProductSlot->same_slot_all_days ? $bookingProductSlot->slots : ($bookingProductSlot->slots[$index] ?? []);
            }

            $slotsByDays[] = [
                'name'  => trans($this->daysOfWeek[$index]),
                'slots' => isset($availabileDays[$index]) ? $this->conver24To12Hours($slots) : [],
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
     * @param Object $bookingProduct
     * @return array
     */
    public function getAvailableWeekDays($bookingProduct)
    {
        if ($bookingProduct->available_every_week) {
            return $this->daysOfWeek;
        }

        $days = [];

        $currentTime = Carbon::now();

        $availableFrom = Carbon::createFromTimeString($bookingProduct->available_from->format('Y-m-d') . " 00:00:01");

        $availableTo = Carbon::createFromTimeString($bookingProduct->available_to->format('Y-m-d') . " 23:59:59");

        for ($i = 0; $i < 7; $i++) {
            $date = clone $currentTime;
            $date->addDays($i);

            if ($date >= $availableFrom && $date <= $availableTo) {
                $days[$i] = $date->format('l');
            }
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
        if (! $slots) {
            return [];
        }

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

        if (! is_array($bookingProductSlot->slots) || ! count($bookingProductSlot->slots)) {
            return [];
        }

        $requestedDate = Carbon::createFromTimeString($date . " 00:00:00");

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                        ? Carbon::createFromTimeString($bookingProduct->available_from->format('Y-m-d') . " 00:00:00")
                        : Carbon::createFromTimeString($currentTime->format('Y-m-d') . ' 00:00:00');

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                        ? Carbon::createFromTimeString($bookingProduct->available_to->format('Y-m-d') . ' 23:59:59')
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
        }

        return $slots;
    }

    /**
     * @param CartItem $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem->product_id);

        if ($bookingProduct->qty - $this->getBookedQuantity($cartItem) < $cartItem->quantity) {
            return false;
        }

        return true;
    }

    /**
     * @param array $cartProducts
     * @return bool
     */
    public function isSlotAvailable($cartProducts)
    {
        foreach ($cartProducts as $cartProduct) {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartProduct['product_id']);

            if ($bookingProduct->qty - $this->getBookedQuantity($cartProduct) < $cartProduct['quantity']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @return integer
     */
    public function getBookedQuantity($data)
    {
        $timestamps = explode('-', $data['additional']['booking']['slot']);

        $result = $this->bookingRepository->getModel()
                ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                ->where('bookings.product_id', $data['product_id'])
                ->where('bookings.from', $timestamps[0])
                ->where('bookings.to', $timestamps[1])
                ->first();

        return $result->total_qty_booked;
    }

    /**
     * Returns additional cart item information
     *
     * @param array $data
     * @return array
     */
    public function getCartItemOptions($data)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $data['product_id']);

        if (! $bookingProduct) {
            return $data;
        }

        switch ($bookingProduct->type) {
            case 'event':
                $ticket = $bookingProduct->event_tickets()->find($data['booking']['ticket_id']);

                $data['attributes'] = [
                    [
                        'attribute_name' => 'Event Ticket',
                        'option_id'      => 0,
                        'option_label'   => $ticket->name,
                    ], [
                        'attribute_name' => 'Rent From',
                        'option_id'      => 0,
                        'option_label'   => Carbon::createFromTimeString($bookingProduct->available_from->format('Y-m-d'))->format('d F, Y'),
                    ], [
                        'attribute_name' => 'Rent Till',
                        'option_id'      => 0,
                        'option_label'   => Carbon::createFromTimeString($bookingProduct->available_to->format('Y-m-d'))->format('d F, Y'),
                    ]];
                
                break;

            case 'rental':
                $rentingType = $data['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

                if ($rentingType == 'daily') {
                    $from = Carbon::createFromTimeString($data['booking']['date_from'] . " 00:00:01")->format('d F, Y');

                    $to = Carbon::createFromTimeString($data['booking']['date_to'] . " 23:59:59")->format('d F, Y');
                } else {
                    $from = Carbon::createFromTimestamp($data['booking']['slot']['from'])->format('d F, Y h:i A');

                    $to = Carbon::createFromTimestamp($data['booking']['slot']['to'])->format('d F, Y h:i A');
                }

                $data['attributes'] = [
                    [
                        'attribute_name' => 'Rent Type',
                        'option_id'      => 0,
                        'option_label'   => trans('bookingproduct::app.shop.cart.' . $rentingType),
                    ], [
                        'attribute_name' => 'Rent From',
                        'option_id'      => 0,
                        'option_label'   => $from,
                    ], [
                        'attribute_name' => 'Rent Till',
                        'option_id'      => 0,
                        'option_label'   => $to,
                    ]];

                break;
            
            default:
                $timestamps = explode('-', $data['booking']['slot']);

                $data['attributes'] = [
                    [
                        'attribute_name' => 'Booking From',
                        'option_id'      => 0,
                        'option_label'   => Carbon::createFromTimestamp($timestamps[0])->format('d F, Y h:i A'),
                    ], [
                        'attribute_name' => 'Booking Till',
                        'option_id'      => 0,
                        'option_label'   => Carbon::createFromTimestamp($timestamps[1])->format('d F, Y h:i A'),
                    ]];

                break;
        }

        return $data;
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param array $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        return $products;
    }

    /**
     * Validate cart item product price
     *
     * @param CartItem $item
     * @return float
     */
    public function validateCartItem($item)
    {
        $price = $item->product->getTypeInstance()->getFinalPrice();

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