<?php

namespace Webkul\BookingProduct\Http\Controllers\Shop;

use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Helpers\DefaultSlot as DefaultSlotHelper;
use Webkul\BookingProduct\Helpers\AppointmentSlot as AppointmentSlotHelper;
use Webkul\BookingProduct\Helpers\RentalSlot as RentalSlotHelper;
use Webkul\BookingProduct\Helpers\EventTicket as EventTicketHelper;
use Webkul\BookingProduct\Helpers\TableSlot as TableSlotHelper;

/**
 * BookingProduct page controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BookingProductController extends Controller
{
    /**
     * Booking object
     *
     * @var Object
     */
    protected $bookingHelper;

    /**
     * @return array
     */
    protected $bookingHelpers = [];

    /**
     * Create a new helper instance.
     *
     * @param Webkul\BookingProduct\Repositories\BookingProductRepository $bookingProductRepository
     * @param Webkul\BookingProduct\Helpers\DefaultSlot                   $defaultSlotHelper
     * @param Webkul\BookingProduct\Helpers\AppointmentSlot               $appointmentSlotHelper
     * @param Webkul\BookingProduct\Helpers\RentalSlot                    $rentalSlotHelper
     * @param Webkul\BookingProduct\Helpers\EventTicket                     $EventTicketHelper
     * @param Webkul\BookingProduct\Helpers\TableSlot                     $tableSlotHelper
     * @return void
     */
    public function __construct(
        BookingProductRepository $bookingProductRepository,
        DefaultSlotHelper $defaultSlotHelper,
        AppointmentSlotHelper $appointmentSlotHelper,
        RentalSlotHelper $rentalSlotHelper,
        EventTicketHelper $eventTicketHelper,
        TableSlotHelper $tableSlotHelper
    )
    {
        $this->bookingProductRepository = $bookingProductRepository;
        
        $this->bookingHelpers['default'] = $defaultSlotHelper;

        $this->bookingHelpers['appointment'] = $appointmentSlotHelper;

        $this->bookingHelpers['rental'] = $rentalSlotHelper;

        $this->bookingHelpers['event'] = $eventTicketHelper;

        $this->bookingHelpers['table'] = $tableSlotHelper;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookingProduct = $this->bookingProductRepository->find(request('id'));

        return response()->json([
            'data' => $this->bookingHelpers[$bookingProduct->type]->getSlotsByDate($bookingProduct, request()->get('date')),
        ]);
    }
}