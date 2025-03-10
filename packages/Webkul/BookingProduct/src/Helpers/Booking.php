<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\BookingProduct\Contracts\BookingProduct;
use Webkul\BookingProduct\Repositories\BookingProductAppointmentSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository;
use Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Repositories\BookingProductTableSlotRepository;
use Webkul\BookingProduct\Repositories\BookingRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\DataTypes\CartItemValidationResult;

class Booking
{
    /**
     * Summary of typeRepositories
     *
     * @var array
     */
    protected $typeRepositories = [];

    /**
     * Summary of typeHelpers
     *
     * @var array
     */
    protected $typeHelpers = [
        'default'     => DefaultSlot::class,
        'appointment' => AppointmentSlot::class,
        'event'       => EventTicket::class,
        'rental'      => RentalSlot::class,
        'table'       => TableSlot::class,
    ];

    /**
     * Summary of daysOfWeek
     *
     * @var array
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
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected BookingProductRepository $bookingProductRepository,
        protected BookingRepository $bookingRepository,
        protected BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        protected BookingProductAppointmentSlotRepository $bookingProductAppointmentSlotRepository,
        protected BookingProductEventTicketRepository $bookingProductEventTicketRepository,
        protected BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        protected BookingProductTableSlotRepository $bookingProductTableSlotRepository,
    ) {
        $this->typeRepositories = [
            'default'     => $this->bookingProductDefaultSlotRepository,
            'event'       => $this->bookingProductEventTicketRepository,
            'appointment' => $this->bookingProductAppointmentSlotRepository,
            'table'       => $this->bookingProductTableSlotRepository,
            'rental'      => $this->bookingProductRentalSlotRepository,
        ];
    }

    /**
     * Returns the booking type helper instance.
     *
     * @return mixed
     */
    public function getTypeHelper(string $type)
    {
        return $this->typeHelpers[$type];
    }

    /**
     * Returns the booking information.
     */
    public function getWeekSlotDurations(BookingProduct $bookingProduct): array
    {
        $slotsByDays = [];

        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        $availableDays = $this->getAvailableWeekDays($bookingProduct);

        foreach ($this->daysOfWeek as $index => $isOpen) {
            $slots = [];

            if ($isOpen) {
                $slots = $bookingProductSlot->same_slot_all_days ? ($bookingProductSlot->slots ?? []) : ($bookingProductSlot->slots[$index] ?? []);
            }

            $slotsByDays[] = [
                'name'  => trans($this->daysOfWeek[$index]),
                'slots' => isset($availableDays[$index]) ? $this->convert24To12Hours($slots) : [],
            ];
        }

        return $slotsByDays;
    }

    /**
     * Returns html of slots for a current day.
     */
    public function getTodaySlotsHtml(BookingProduct $bookingProduct)
    {
        $slots = [];

        $weekSlots = $this->getWeekSlotDurations($bookingProduct);

        foreach ($weekSlots[Carbon::now()->format('w')]['slots'] as $slot) {
            $slots[] = $slot['from'].' - '.$slot['to'];
        }

        return count($slots)
            ? implode(' | ', $slots)
            : '<span class="text-danger">'.trans('shop::app.products.booking.closed').'</span>';
    }

    /**
     * Sort days.
     */
    public function sortDaysOfWeek(array $days): array
    {
        $daysAux = array_intersect($this->daysOfWeek, $days);

        usort($daysAux, function ($a, $b) {
            return array_search($a, $this->daysOfWeek) - array_search($b, $this->daysOfWeek);
        });

        return $daysAux;
    }

    /**
     * Returns slots for a particular day.
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

        return $this->slotsCalculation($bookingProduct, $requestedDate, $bookingProductSlot);
    }

    /**
     * Returns is item have quantity.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        if (
            $bookingProduct->qty - $this->getBookedQuantity($cartItem) < $cartItem['quantity']
            || $this->isSlotExpired($cartItem)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Return slot if it is available.
     */
    public function isSlotAvailable(array $cartProducts): bool
    {
        foreach ($cartProducts as $cartProduct) {
            if (! $this->isItemHaveQuantity($cartProduct)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns slots that are going to expire.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     */
    public function isSlotExpired($cartItem): bool
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        $typeHelper = app($this->getTypeHelper($bookingProduct->type));

        $slots = $typeHelper->getSlotsByDate($bookingProduct, $cartItem['additional']['booking']['date']);

        $slotExists = collect($slots)->contains(function ($slot) use ($cartItem) {
            return $slot['timestamp'] == $cartItem['additional']['booking']['slot'];
        });

        return ! $slotExists;
    }

    /**
     * Returns get booked quantity.
     *
     * @param  array  $data
     */
    public function getBookedQuantity($data): int
    {
        $timestamps = explode('-', $data['additional']['booking']['slot']);

        $result = $this->bookingRepository->getModel()
            ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
            ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
            ->where('bookings.product_id', $data['product_id'])
            ->where('bookings.from', $timestamps[0])
            ->where('bookings.to', $timestamps[1])
            ->first();

        return $result->total_qty_booked ?? 0;
    }

    /**
     * Returns additional cart item information.
     */
    public function getCartItemOptions(array $data): array
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $data['product_id']);

        if ($bookingProduct) {
            $data['attributes'] = $this->getBookingAttributes($bookingProduct, $data);
        }

        return $data;
    }

    /**
     * Get booking attributes based on booking type.
     */
    protected function getBookingAttributes($bookingProduct, $data): array
    {
        switch ($bookingProduct->type) {
            case 'event':
                return $this->getEventAttributes($bookingProduct, $data);

            case 'rental':
                return $this->getRentalAttributes($bookingProduct, $data);

            case 'table':
                return $this->getTableAttributes($data);

            default:
                return $this->getDefaultAttributes($data);
        }
    }

    /**
     * Returns the available week days.
     */
    private function getAvailableWeekDays(BookingProduct $bookingProduct)
    {
        if ($bookingProduct->available_every_week ?? true) {
            return $this->daysOfWeek;
        }

        $availableFrom = $bookingProduct->available_from
            ? Carbon::createFromTimeString($bookingProduct->available_from)
            : Carbon::now()->startOfDay();

        $availableTo = $bookingProduct->available_to
            ? Carbon::createFromTimeString($bookingProduct->available_to)
            : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $days = collect(range(0, 6))
            ->map(function ($i) use ($availableFrom, $availableTo) {
                $date = Carbon::now()->addDays($i);

                return ($date >= $availableFrom && $date <= $availableTo) ? $date->format('l') : null;
            })
            ->filter()
            ->values()
            ->toArray();

        return $this->sortDaysOfWeek($days);
    }

    /**
     * Add booking additional prices to cart item.
     */
    public function addAdditionalPrices(array $products): array
    {
        return $products;
    }

    /**
     * Validate cart item product price.
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult;

        if ($this->isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $this->updateCartItemPrice($item);

        return $result;
    }

    /**
     * Returns true if the cart item is inactive.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     */
    public function isCartItemInactive($item): bool
    {
        return ! $item->product->status;
    }

    /**
     * Slots Calculation for all types of booking products.
     */
    public function slotsCalculation(object $bookingProduct, object $requestedDate, object $bookingProductSlot): array
    {
        if ($bookingProduct->type == 'default') {
            [$availableFrom, $availableTo, $timeDurations] = $this->getDefaultSlotDetails($bookingProduct, $bookingProductSlot, $requestedDate);

            if (
                ! count($timeDurations)
                || ! $timeDurations[0]['status']
            ) {
                return [];
            }
        } else {
            [$availableFrom, $availableTo, $timeDurations] = $this->getSlotDetails($bookingProduct, $bookingProductSlot, $requestedDate);

            if (
                $requestedDate < $availableFrom
                || $requestedDate > $availableTo
            ) {
                return [];
            }
        }

        $slots = [];

        foreach ($timeDurations as $index => $timeDuration) {
            $fromChunks = explode(':', $timeDuration['from']);
            $toChunks = explode(':', $timeDuration['to']);

            $startDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d').' 00:00:00')
                ->addMinutes($fromChunks[0] * 60 + $fromChunks[1]);

            $tempStartDayTime = clone $startDayTime;

            $endDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d').' 00:00:00')
                ->addMinutes($toChunks[0] * 60 + $toChunks[1]);

            $isFirstIteration = true;

            while (1) {
                $from = clone $tempStartDayTime;

                if ($bookingProduct->type == 'rental') {
                    $tempStartDayTime->addMinutes(60);
                } else {
                    $tempStartDayTime->addMinutes($bookingProductSlot->duration);

                    if ($isFirstIteration) {
                        $isFirstIteration = false;
                    } else {
                        $from->modify('+'.$bookingProductSlot->break_time.' minutes');
                        $tempStartDayTime->modify('+'.$bookingProductSlot->break_time.' minutes');
                    }
                }

                $to = clone $tempStartDayTime;

                if (
                    $startDayTime <= $from
                    && $from <= $availableTo
                    && $availableTo >= $to
                    && $to >= $startDayTime
                    && $startDayTime <= $from
                    && $from <= $endDayTime
                    && $endDayTime >= $to
                    && $to >= $startDayTime
                ) {
                    if (
                        $qty = $timeDuration['qty'] ?? 1
                        && Carbon::now() <= $from
                    ) {
                        if ($bookingProduct->type == 'rental') {
                            if (! isset($slots[$index])) {
                                $slots[$index]['time'] = $startDayTime->format('h:i A').' - '.$endDayTime->format('h:i A');
                            }

                            $slots[$index]['slots'][] = [
                                'from'           => $from->format('h:i A'),
                                'to'             => $to->format('h:i A'),
                                'from_timestamp' => $from->getTimestamp(),
                                'to_timestamp'   => $to->getTimestamp(),
                                'qty'            => $qty,
                            ];
                        } else {
                            $slots[] = [
                                'from'      => $from->format('h:i A'),
                                'to'        => $to->format('h:i A'),
                                'timestamp' => $from->getTimestamp().'-'.$to->getTimestamp(),
                                'qty'       => $qty,
                            ];
                        }
                    }
                } else {
                    break;
                }
            }
        }

        return $slots;
    }

    /**
     * Convert time from 24 to 12 hour format
     */
    private function convert24To12Hours(array $slots): array
    {
        return array_map(function ($slot) {
            return [
                'from' => Carbon::createFromTimeString($slot['from'])->format('h:i a'),
                'to'   => Carbon::createFromTimeString($slot['to'])->format('h:i a'),
            ];
        }, $slots);
    }

    /**
     * Update the cart item price.
     */
    private function updateCartItemPrice(CartItem $item): void
    {
        $price = $item->product->getTypeInstance()->getFinalPrice($item->quantity);

        if ($price != $item->base_price) {
            $item->base_price = $price;
            $item->price = core()->convertPrice($price);

            $item->base_total = $price * $item->quantity;
            $item->total = core()->convertPrice($price * $item->quantity);

            $item->save();
        }
    }

    /**
     * Get default slot details.
     */
    private function getDefaultSlotDetails($bookingProduct, $bookingProductSlot, $requestedDate): array
    {
        $availableFrom = $bookingProductSlot->available_from
            ? Carbon::createFromTimeString($bookingProductSlot->available_from)
            : Carbon::now()->startOfDay();

        $availableTo = $bookingProductSlot->available_to
            ? Carbon::createFromTimeString($bookingProductSlot->available_to)
            : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
            ? $bookingProductSlot->slots
            : ($bookingProductSlot->slots[$requestedDate->format('w')] ?? []);

        return [$availableFrom, $availableTo, $timeDurations];
    }

    /**
     * Get slot details based on booking type.
     */
    private function getSlotDetails($bookingProduct, $bookingProductSlot, $requestedDate): array
    {
        if ($bookingProduct->type == 'default') {
            return $this->getDefaultSlotDetails($bookingProduct, $bookingProductSlot, $requestedDate);
        }

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
            ? Carbon::createFromTimeString($bookingProduct->available_from)
            : Carbon::now()->copy()->startOfDay();

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
            ? Carbon::createFromTimeString($bookingProduct->available_to)
            : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
            ? $bookingProductSlot->slots
            : ($bookingProductSlot->slots[$requestedDate->format('w')] ?? []);

        return [$availableFrom, $availableTo, $timeDurations];
    }

    /**
     * Get event booking attributes.
     */
    private function getEventAttributes($bookingProduct, $data): array
    {
        $ticket = $bookingProduct->event_tickets()->find($data['booking']['ticket_id']);

        return [
            [
                'attribute_name' => trans('shop::app.products.booking.cart.event-ticket'),
                'option_id'      => 0,
                'option_label'   => $ticket->name,
            ], [
                'attribute_name' => trans('shop::app.products.booking.cart.event-from'),
                'option_id'      => 0,
                'option_label'   => Carbon::createFromTimeString($bookingProduct->available_from)->format('d F, Y'),
            ], [
                'attribute_name' => trans('shop::app.products.booking.cart.event-till'),
                'option_id'      => 0,
                'option_label'   => Carbon::createFromTimeString($bookingProduct->available_to)->format('d F, Y'),
            ],
        ];
    }

    /**
     * Get rental booking attributes.
     */
    private function getRentalAttributes($bookingProduct, $data): array
    {
        $rentingType = $data['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::createFromTimeString($data['booking']['date_from'].' 00:00:01')->format('d F, Y');

            $to = Carbon::createFromTimeString($data['booking']['date_to'].' 23:59:59')->format('d F, Y');
        } else {
            $from = Carbon::createFromTimestamp($data['booking']['slot']['from'])->format('d F, Y h:i A');

            $to = Carbon::createFromTimestamp($data['booking']['slot']['to'])->format('d F, Y h:i A');
        }

        return [
            [
                'attribute_name' => trans('shop::app.products.booking.cart.rent-type'),
                'option_id'      => 0,
                'option_label'   => trans('shop::app.products.booking.cart.'.$rentingType),
            ], [
                'attribute_name' => trans('shop::app.products.booking.cart.rent-from'),
                'option_id'      => 0,
                'option_label'   => $from,
            ], [
                'attribute_name' => trans('shop::app.products.booking.cart.rent-till'),
                'option_id'      => 0,
                'option_label'   => $to,
            ],
        ];
    }

    /**
     * Get table booking attributes.
     */
    private function getTableAttributes($data): array
    {
        $timestamps = explode('-', $data['booking']['slot']);

        $attributes = [
            [
                'attribute_name' => trans('shop::app.products.booking.cart.booking-from'),
                'option_id'      => 0,
                'option_label'   => Carbon::createFromTimestamp($timestamps[0])->isoFormat('Do MMM, YYYY h:mm A'),
            ], [
                'attribute_name' => trans('shop::app.products.booking.cart.booking-till'),
                'option_id'      => 0,
                'option_label'   => Carbon::createFromTimestamp($timestamps[1])->isoFormat('Do MMM, YYYY h:mm A'),
            ],
        ];

        if ($data['booking']['note'] !== '') {
            $attributes[] = [
                'attribute_name' => trans('shop::app.products.booking.cart.special-note'),
                'option_id'      => 0,
                'option_label'   => $data['booking']['note'],
            ];
        }

        return $attributes;
    }

    /**
     * Get default booking attributes.
     */
    private function getDefaultAttributes($data): array
    {
        $timestamps = explode('-', $data['booking']['slot']);

        return [
            [
                'attribute_name' => trans('shop::app.products.booking.cart.booking-from'),
                'option_id'      => 0,
                'option_label'   => Carbon::createFromTimestamp($timestamps[0])->format('d F, Y h:i A'),
            ], [
                'attribute_name' => trans('shop::app.products.booking.cart.booking-till'),
                'option_id'      => 0,
                'option_label'   => Carbon::createFromTimestamp($timestamps[1])->format('d F, Y h:i A'),
            ],
        ];
    }
}
