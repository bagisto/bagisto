<?php

namespace Webkul\BookingProduct\Http\Controllers\Shop;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\BookingProduct\Helpers\AppointmentSlot as AppointmentSlotHelper;
use Webkul\BookingProduct\Helpers\DefaultSlot as DefaultSlotHelper;
use Webkul\BookingProduct\Helpers\EventTicket as EventTicketHelper;
use Webkul\BookingProduct\Helpers\RentalSlot as RentalSlotHelper;
use Webkul\BookingProduct\Helpers\TableSlot as TableSlotHelper;
use Webkul\BookingProduct\Http\Controllers\Controller;
use Webkul\BookingProduct\Repositories\BookingProductRepository;

class BookingProductController extends Controller
{
    /**
     * @return array
     */
    protected $bookingHelpers = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BookingProductRepository $bookingProductRepository,
        protected DefaultSlotHelper $defaultSlotHelper,
        protected AppointmentSlotHelper $appointmentSlotHelper,
        protected RentalSlotHelper $rentalSlotHelper,
        protected EventTicketHelper $eventTicketHelper,
        protected TableSlotHelper $tableSlotHelper
    ) {
        $this->bookingHelpers['default'] = $defaultSlotHelper;

        $this->bookingHelpers['appointment'] = $appointmentSlotHelper;

        $this->bookingHelpers['rental'] = $rentalSlotHelper;

        $this->bookingHelpers['event'] = $eventTicketHelper;

        $this->bookingHelpers['table'] = $tableSlotHelper;
    }

    /**
     * Get available slots for the given product and the date.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(int $id)
    {
        $bookingProduct = $this->bookingProductRepository->find($id);

        return new JsonResource([
            'data' => $this->bookingHelpers[$bookingProduct->type]->getSlotsByDate($bookingProduct, request()->date),
        ]);
    }
}
