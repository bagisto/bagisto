<?php

namespace Webkul\BookingProduct\Http\Controllers\Admin;

use Carbon\Carbon;
use Webkul\BookingProduct\Http\Controllers\Controller;
use Webkul\BookingProduct\DataGrids\Admin\BookingDataGrid;
use Webkul\BookingProduct\Repositories\BookingRepository;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected BookingRepository $bookingRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        if (request('view_type')) {
            $startDate = request()->get('startDate')
                ? Carbon::createFromTimeString(request()->get('startDate') . " 00:00:01")
                : Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');

            $endDate = request()->get('endDate')
                ? Carbon::createFromTimeString(request()->get('endDate') . " 23:59:59")
                : Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

            $bookings = $this->bookingRepository->getBookings([strtotime($startDate), strtotime($endDate)])
                ->map(function ($booking) {
                    $booking['start'] = Carbon::createFromTimestamp($booking->start)->format('Y-m-d H:i:s');
                    
                    $booking['end'] = Carbon::createFromTimestamp($booking->end)->format('Y-m-d H:i:s');

                    return $booking;
                });

            return response()->json([
                'bookings' => $bookings,
            ]);
        } else {
            return app(BookingDataGrid::class)->toJson();
        }
    }
}