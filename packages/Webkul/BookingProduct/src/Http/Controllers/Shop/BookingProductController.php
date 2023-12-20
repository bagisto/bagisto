<?php

namespace Webkul\BookingProduct\Http\Controllers\Shop;

use Webkul\BookingProduct\Http\Controllers\Controller;
use Webkul\BookingProduct\Helpers\Booking as BookingHelpers;
use Webkul\BookingProduct\Repositories\BookingProductRepository;

class BookingProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BookingProductRepository $bookingProductRepository,
        protected BookingHelpers $bookingHelpers
    ) {
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $bookingProduct = $this->bookingProductRepository->find($id);

        $date = $this->bookingHelpers->getSlotsByDate($bookingProduct, request()->date);

        return response()->json([
            'data' => $this->bookingHelpers[$bookingProduct->type]->getSlotsByDate($bookingProduct, request()->date),
        ]);
    }
}
