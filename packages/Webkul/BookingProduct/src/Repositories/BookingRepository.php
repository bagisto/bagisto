<?php

namespace Webkul\BookingProduct\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;

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
     * @param  array  $data
     * @return \Webkul\BookingProduct\Contracts\Booking
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
                if (isset($item->additional['booking']['slot']['from']) && isset($item->additional['booking']['slot']['to'])) {
                    $from = $item->additional['booking']['slot']['from'];

                    $to = $item->additional['booking']['slot']['to'];
                } else {
                    $timestamps = explode('-', $item->additional['booking']['slot']);

                    $from = current($timestamps);

                    $to = end($timestamps);
                }
            } elseif (isset($item->additional['booking']['date_from']) && isset($item->additional['booking']['date_to'])) {
                $from = Carbon::createFromTimeString($item->additional['booking']['date_from'] . ' 00:00:00')->getTimestamp();

                $to = Carbon::createFromTimeString($item->additional['booking']['date_to'] . ' 23:59:59')->getTimestamp();
            }

            $booking = parent::create([
                'qty'                             => $item->qty_ordered,
                'from'                            => $from,
                'to'                              => $to,
                'order_id'                        => $order->id,
                'order_item_id'                   => $item->id,
                'product_id'                      => $item->product_id,
                'booking_product_event_ticket_id' => $item->additional['booking']['ticket_id'] ?? null,
            ]);

            Event::dispatch('marketplace.booking.save.after', $booking);
        }
    }

    /**
     * @param  string  $dateRange
     * @return mixed
     */
    public function getBookings($dateRange)
    {
        return $this->select(
                'bookings.id',
                'bookings.order_id',
                'order_items.name as title',
                'bookings.from as start',
                'bookings.to as end',
            )
            ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
            ->whereBetween('bookings.from', $dateRange)
            ->distinct()
            ->get();
    }
}