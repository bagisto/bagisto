<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\BookingProduct\Helpers\AppointmentSlot as AppointmentSlotHelper;
use Webkul\BookingProduct\Helpers\Booking as BookingHelper;
use Webkul\BookingProduct\Helpers\DefaultSlot as DefaultSlotHelper;
use Webkul\BookingProduct\Helpers\EventTicket as EventTicketHelper;
use Webkul\BookingProduct\Helpers\RentalSlot as RentalSlotHelper;
use Webkul\BookingProduct\Helpers\TableSlot as TableSlotHelper;
use Webkul\BookingProduct\Repositories\BookingProductRepository;

class BookingProductController extends Controller
{
    /**
     * Booking type helpers, keyed by booking product type. Used to retrieve slot.
     */
    protected array $bookingHelpers = [];

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected BookingProductRepository $bookingProductRepository,
        protected BookingHelper $bookingHelper,
        protected DefaultSlotHelper $defaultSlotHelper,
        protected AppointmentSlotHelper $appointmentSlotHelper,
        protected RentalSlotHelper $rentalSlotHelper,
        protected EventTicketHelper $eventTicketHelper,
        protected TableSlotHelper $tableSlotHelper
    ) {
        $this->bookingHelpers = [
            'default' => $this->defaultSlotHelper,
            'appointment' => $this->appointmentSlotHelper,
            'rental' => $this->rentalSlotHelper,
            'event' => $this->eventTicketHelper,
            'table' => $this->tableSlotHelper,
        ];
    }

    /**
     * Return the full booking-product configuration needed by the admin
     * create-order booking drawer: sub-type, availability window, slot
     * metadata for the date-picker, and event tickets where applicable.
     */
    public function config(int $productId): JsonResource
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $productId);

        if (! $bookingProduct) {
            return new JsonResource(['data' => null]);
        }

        $data = [
            'id' => $bookingProduct->id,
            'type' => $bookingProduct->type,
            'available_every_week' => (bool) $bookingProduct->available_every_week,
            'available_from' => $bookingProduct->available_from?->format('Y-m-d'),
            'available_to' => $bookingProduct->available_to?->format('Y-m-d'),
            'calendar' => $this->bookingHelper->getCalendarAvailability($bookingProduct),
        ];

        switch ($bookingProduct->type) {
            case 'rental':
                $data['rental_slot'] = [
                    'renting_type' => $bookingProduct->rental_slot?->renting_type,
                    'daily_price' => $bookingProduct->rental_slot?->daily_price,
                    'hourly_price' => $bookingProduct->rental_slot?->hourly_price,
                ];
                break;

            case 'event':
                $data['event_tickets'] = $this->eventTicketHelper->getTickets($bookingProduct);
                break;

            case 'table':
                $data['table_slot'] = [
                    'guest_capacity' => $bookingProduct->table_slot?->guest_capacity,
                    'guest_limit' => $bookingProduct->table_slot?->guest_limit,
                    'price_type' => $bookingProduct->table_slot?->price_type,
                ];
                break;
        }

        return new JsonResource(['data' => $data]);
    }

    /**
     * Return the available slots for a given date. Used by the admin create-order
     * booking drawer for default / appointment / table / rental-hourly flows.
     */
    public function slots(int $productId): JsonResource
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $productId);

        if (! $bookingProduct || ! isset($this->bookingHelpers[$bookingProduct->type])) {
            return new JsonResource(['data' => []]);
        }

        return new JsonResource([
            'data' => $this->bookingHelpers[$bookingProduct->type]->getSlotsByDate($bookingProduct, request()->date),
        ]);
    }
}
