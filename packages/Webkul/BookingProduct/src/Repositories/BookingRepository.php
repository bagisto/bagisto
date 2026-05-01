<?php

namespace Webkul\BookingProduct\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Webkul\BookingProduct\Contracts\Booking;
use Webkul\Core\Eloquent\Repository;

class BookingRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Booking::class;
    }

    /**
     * Create Booking Product.
     */
    public function create(array $data): void
    {
        $order = $data['order'];

        foreach ($order->items()->get() as $item) {
            if ($item->type != 'booking') {
                continue;
            }

            Event::dispatch('booking_product.booking.save.before', $item);

            $from = $to = null;

            $bookingItem = $item->additional['booking'];

            if (isset($bookingItem['slot'])) {
                if (isset($bookingItem['slot']['from'], $bookingItem['slot']['to'])) {
                    $from = $bookingItem['slot']['from'];

                    $to = $bookingItem['slot']['to'];
                } else {
                    $timestamps = explode('-', $bookingItem['slot']);

                    $from = $timestamps[0];

                    $to = $timestamps[1];
                }
            } elseif (isset($bookingItem['date_from'], $bookingItem['date_to'])) {
                $from = Carbon::createFromTimeString($bookingItem['date_from'].' 00:00:00')->getTimestamp();

                $to = Carbon::createFromTimeString($bookingItem['date_to'].' 23:59:59')->getTimestamp();
            }

            $bookingProduct = $item->product?->booking_products()->first();

            $allowCancellation = $bookingProduct
                ? (bool) ($bookingProduct->allow_cancellation ?? true)
                : true;

            $booking = parent::create([
                'qty' => $item->qty_ordered,
                'from' => $from,
                'to' => $to,
                'allow_cancellation' => $allowCancellation,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'product_id' => $item->product_id,
                'booking_product_event_ticket_id' => $bookingItem['ticket_id'] ?? null,
            ]);

            Event::dispatch('booking_product.booking.save.after', $booking);
        }
    }

    /**
     * Get all bookings for the given date and time range.
     */
    public function getBookings(array $dateRange): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->select(
            'bookings.id',
            'bookings.order_id',
            'bookings.from as start',
            'bookings.to as end',
            'orders.status as status',
            'orders.customer_email as email',
            'orders.created_at as created_at',
            'products.sku as product_sku',
            'product_flat.name as product_name',
            'order_items.additional as item_additional',
            'addresses.address as address',
            'addresses.phone as contact',
            'addresses.city as city',
            'addresses.state as state',
            'addresses.country as country',
            'addresses.postcode as postcode',
        )
            ->addSelect(DB::raw('CONCAT('.$tablePrefix.'orders.customer_first_name, " ", '.$tablePrefix.'orders.customer_last_name) as full_name'))
            ->leftJoin('orders', 'bookings.order_id', '=', 'orders.id')
            ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
            ->leftJoin('products', 'bookings.product_id', '=', 'products.id')
            ->leftJoin('product_flat', function ($join) {
                $join->on('product_flat.product_id', '=', 'products.id')
                    ->where('product_flat.locale', core()->getCurrentLocale()->code)
                    ->where('product_flat.channel', core()->getCurrentChannelCode());
            })
            ->leftJoin('addresses', 'bookings.order_id', '=', 'addresses.order_id')
            ->where('bookings.from', '<=', $dateRange[1])
            ->where('bookings.to', '>=', $dateRange[0])
            ->distinct()
            ->get();
    }
}
