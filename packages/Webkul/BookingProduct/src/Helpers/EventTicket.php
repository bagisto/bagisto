<?php

namespace Webkul\BookingProduct\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\Checkout\Facades\Cart;
use Webkul\Product\Datatypes\CartItemValidationResult;
use Webkul\Checkout\Models\CartItem;

class EventTicket extends Booking
{
    /**
     * Returns event date
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return string
     */
    public function getEventDate($bookingProduct)
    {
        $from = Carbon::createFromTimeString($bookingProduct->available_from)->format('d F, Y h:i A');

        $to = Carbon::createFromTimeString($bookingProduct->available_to)->format('d F, Y h:i A');

        return $from . ' - ' . $to;
    }

    /**
     * Returns tickets
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return array
     */
    public function getTickets($bookingProduct)
    {
        if (! $bookingProduct->event_tickets()->count()) {
            return;
        }

        return $this->formatPrice($bookingProduct->event_tickets);
    }

    /**
     * Format ticket price
     *
     * @param  array  $tickets
     * @return array
     */
    public function formatPrice($tickets)
    {
        foreach ($tickets as $index => $ticket) {
            $price = $ticket->price;

            if ($this->isInSale($ticket)) {
                $price = $ticket->special_price;

                $tickets[$index]['original_converted_price'] = core()->convertPrice($ticket->price);
                $tickets[$index]['original_formated_price'] = core()->currency($ticket->price);
            }

            $tickets[$index]['id'] = $ticket->id;
            $tickets[$index]['converted_price'] = core()->convertPrice($price);
            $tickets[$index]['formated_price'] = $formatedPrice = core()->currency($price);
            $tickets[$index]['formated_price_text'] = __('bookingproduct::app.shop.products.per-ticket-price', ['price' => $formatedPrice]);
        }

        return $tickets;
    }

    /**
     * @param \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        $ticket = $bookingProduct->event_tickets()->find($cartItem['additional']['booking']['ticket_id']);

        if ($ticket->qty - $this->getBookedQuantity($cartItem) < $cartItem['quantity']) {
            return false;
        }

        return true;
    }

    /**
     * @param  array  $data
     * @return int
     */
    public function getBookedQuantity($data)
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
     * Add booking additional prices to cart item
     *
     * @param  array  $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        foreach ($products as $key => $product) {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $product['product_id']);

            $ticket = $bookingProduct->event_tickets()->find($product['additional']['booking']['ticket_id']);

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
     * Validate cart item product price
     *
     * @param \Webkul\Checkout\Models\CartItem $item
     *
     * @return \Webkul\Product\Datatypes\CartItemValidationResult
     */
    public function validateCartItem(CartItem $item): CartItemValidationResult
    {
        $result = new CartItemValidationResult();

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
     * Determines whether a single ticket is in Sale, i.e. has a valid sale price
     *
     * @return bool
     */
    public function isInSale($ticket): bool
    {
        return $ticket->special_price !== null
            && $ticket->special_price > 0.0
            && ($ticket->special_price_from === null || $ticket->special_price_from === '0000-00-00 00:00:00' || $ticket->special_price_from <= Carbon::now())
            && ($ticket->special_price_to === null || $ticket->special_price_to === '0000-00-00 00:00:00' || $ticket->special_price_to > Carbon::now());
    }
}