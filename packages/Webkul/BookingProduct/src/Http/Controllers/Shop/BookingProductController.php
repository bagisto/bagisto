<?php

namespace Webkul\BookingProduct\Http\Controllers\Shop;

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
    public function __construct(protected BookingProductRepository $bookingProductRepository)
    {
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
