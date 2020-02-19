<?php

namespace Webkul\BookingProduct\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

/**
 * BookingProduct Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BookingRepository extends Repository
{
    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\BookingProduct\Repositories\BookingProductRepository $bookingProductRepository
     * @param  Illuminate\Container\Container                              $app
     * @return void
     */
    public function __construct(
        BookingProductRepository $bookingProductRepository,
        App $app
    )
    {
        $this->bookingProductRepository = $bookingProductRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\BookingProduct\Contracts\Booking';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $order = $data['order'];

        foreach ($order->items()->get() as $item) {
            if ($item->type != 'booking') {
                continue;
            }

            Event::fire('booking_product.booking.save.before', $item);

            $from = $to = null;

            $booking = $this->bookingProductRepository->create([
                    'qty'           => $item->qty_ordered,
                    'from'          => $from,
                    'to'            => $to,
                    'order_id'      => $order,
                    'order_item_id' => $item->id
                ]);

            Event::fire('marketplace.booking.save.after', $booking);
        }
    }
}