<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\BookingProduct\Contracts\BookingProduct;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\DataTypes\CartItemValidationResult;

class EventTicket extends Booking
{
    /**
     * Returns event date
     *
     * @param  BookingProduct  $bookingProduct
     */
    public function getEventDate($bookingProduct): string
    {
        $from = Carbon::createFromTimeString($bookingProduct->available_from)->format('d F, Y h:i A');

        $to = Carbon::createFromTimeString($bookingProduct->available_to)->format('d F, Y h:i A');

        return $from.' - '.$to;
    }

    /**
     * Returns the cheapest ticket price (special price if on sale, else regular price).
     */
    public function getCheapestTicketPrice($bookingProduct): float
    {
        if (! $bookingProduct || ! $bookingProduct->event_tickets()->count()) {
            return 0;
        }

        $cheapest = null;

        foreach ($bookingProduct->event_tickets as $ticket) {
            $price = $this->isInSale($ticket) ? $ticket->special_price : $ticket->price;

            if ($cheapest === null || $price < $cheapest) {
                $cheapest = $price;
            }
        }

        return (float) ($cheapest ?? 0);
    }

    /**
     * Returns tickets
     *
     * @param  BookingProduct  $bookingProduct
     */
    public function getTickets($bookingProduct)
    {
        if (! $bookingProduct->event_tickets()->count()) {
            return [];
        }

        $basePrice = $bookingProduct->product?->getTypeInstance()?->getFinalPrice() ?? 0;

        return $this->formatPrice($bookingProduct->event_tickets, $basePrice);
    }

    /**
     * Format ticket price.
     *
     * @param  array  $tickets
     * @param  float  $basePrice
     */
    public function formatPrice($tickets, $basePrice = 0)
    {
        foreach ($tickets as $index => $ticket) {
            $price = $ticket->price;

            if ($this->isInSale($ticket)) {
                $price = $ticket->special_price;

                $tickets[$index]['original_converted_price'] = core()->convertPrice($ticket->price);
                $tickets[$index]['original_formatted_price'] = core()->currency($ticket->price);
            }

            $tickets[$index]['id'] = $ticket->id;
            $tickets[$index]['converted_price'] = core()->convertPrice($price);
            $tickets[$index]['formatted_price'] = $formattedPrice = core()->currency($price);
            $tickets[$index]['formatted_price_text'] = trans('shop::app.products.booking.per-ticket-price', ['price' => $formattedPrice]);

            $totalPrice = $basePrice + $price;
            $tickets[$index]['total_price'] = core()->convertPrice($totalPrice);
            $tickets[$index]['formatted_total_price'] = core()->currency($totalPrice);
        }

        return $tickets;
    }

    /**
     * Return the item if it has a quantity.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     */
    public function isItemHaveQuantity($cartItem): bool
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        $ticket = $bookingProduct->event_tickets()->find($cartItem['additional']['booking']['ticket_id']);

        if (! $ticket) {
            return false;
        }

        if ($ticket->qty - $this->getBookedQuantity($cartItem) < $cartItem['quantity']) {
            return false;
        }

        return true;
    }

    /**
     * Returns the remaining available quantity for the event ticket.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     */
    public function getAvailableTicketQuantity($cartItem): int
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        $ticket = $bookingProduct->event_tickets()->find($cartItem['additional']['booking']['ticket_id']);

        if (! $ticket) {
            return 0;
        }

        return max(0, $ticket->qty - $this->getBookedQuantity($cartItem));
    }

    /**
     * Returns the quantity of booked product.
     *
     * @param  array  $data
     */
    public function getBookedQuantity($data): int
    {
        $result = $this->bookingRepository->getModel()
            ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
            ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
            ->where('bookings.product_id', $data['product_id'])
            ->where('bookings.booking_product_event_ticket_id', $data['additional']['booking']['ticket_id'])
            ->first();

        return ! is_null($result->total_qty_booked) ? $result->total_qty_booked : 0;
    }

    /**
     * Add booking additional prices to cart item.
     */
    public function addAdditionalPrices(array $products): array
    {
        foreach ($products as $key => $product) {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $product['product_id']);

            $ticket = $bookingProduct->event_tickets()->find($product['additional']['booking']['ticket_id']);

            if (! $ticket) {
                continue;
            }

            $price = $ticket->price;

            if ($this->isInSale($ticket)) {
                $price = $ticket->special_price;
            }

            $products[$key]['price'] += core()->convertPrice($price);
            $products[$key]['base_price'] += $price;
            $products[$key]['total'] += (core()->convertPrice($price) * $products[$key]['quantity']);
            $products[$key]['base_total'] += ($price * $products[$key]['quantity']);
        }

        return $products;
    }

    /**
     * Validate cart item product price.
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult;

        if (parent::isCartItemInactive($item)) {
            $result->itemIsInactive();

            return $result;
        }

        $price = $item->product->getTypeInstance()->getFinalPrice($item->quantity);

        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $item->product_id);

        $ticket = $bookingProduct->event_tickets()->find($item->additional['booking']['ticket_id']);

        if (! $ticket) {
            $result->itemIsInactive();

            return $result;
        }

        if ($this->isInSale($ticket)) {
            $price += $ticket->special_price;
        } else {
            $price += $ticket->price;
        }

        if ($price === $item->base_price) {
            return $result;
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();

        return $result;
    }

    /**
     * Determines whether a single ticket is in Sale, i.e. has a valid sale price.
     */
    public function isInSale($ticket): bool
    {
        return $ticket->special_price !== null
            && $ticket->special_price > 0.0
            && (
                $ticket->special_price_from === null
                || $ticket->special_price_from === '0000-00-00 00:00:00'
                || $ticket->special_price_from <= Carbon::now()
            )
            && (
                $ticket->special_price_to === null
                || $ticket->special_price_to === '0000-00-00 00:00:00'
                || $ticket->special_price_to > Carbon::now()
            );
    }
}
