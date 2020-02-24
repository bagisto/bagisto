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

            Event::dispatch('booking_product.booking.save.before', $item);

            $from = $to = null;

            if (isset($item->additional['booking']['slot'])) {
                $timestamps = explode('-', $item->additional['booking']['slot']);

                $from = current($timestamps);

                $to = end($timestamps);
            }

            $booking = parent::create([
                    'qty'           => $item->qty_ordered,
                    'from'          => $from,
                    'to'            => $to,
                    'order_id'      => $order->id,
                    'order_item_id' => $item->id
                ]);

            Event::dispatch('marketplace.booking.save.after', $booking);
        }
    }
}