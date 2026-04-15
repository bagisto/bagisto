<?php

namespace Webkul\BookingProduct\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Webkul\BookingProduct\Contracts\BookingProduct;
use Webkul\BookingProduct\Contracts\BookingProductEventTicket;
use Webkul\Core\Eloquent\Repository;

class BookingProductEventTicketRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return BookingProductEventTicket::class;
    }

    /**
     * Summary of save Event Tickets.
     */
    public function saveEventTickets(array $data, BookingProduct $bookingProduct): void
    {
        Event::dispatch('booking_product.booking.event-ticket.save.before', [
            'data' => $data,
            'bookingProduct' => $bookingProduct,
        ]);

        $previousTicketIds = $bookingProduct->event_tickets()->pluck('id')->toArray();

        $savedTickets = [];

        if (! empty($data['tickets'])) {
            foreach ($data['tickets'] as $ticketId => &$ticketInputs) {
                $this->sanitizeInput('special_price', $ticketInputs);

                $this->sanitizeInput('special_price_from', $ticketInputs);

                $this->sanitizeInput('special_price_to', $ticketInputs);

                $this->validateTicketDates($ticketInputs, $bookingProduct);

                if (Str::contains($ticketId, 'ticket_')) {
                    $ticket = $this->create(array_merge([
                        'booking_product_id' => $bookingProduct->id,
                    ], $ticketInputs));
                } else {
                    if (($index = array_search($ticketId, $previousTicketIds)) !== false) {
                        unset($previousTicketIds[$index]);
                    }

                    $ticket = $this->update($ticketInputs, $ticketId);
                }

                $savedTickets[$ticketId] = [
                    'ticket' => $ticket,
                    'ticketInputs' => $ticketInputs,
                ];
            }

            Event::dispatch('booking_product.booking.event-ticket.save.after', ['tickets' => $savedTickets]);
        }

        if (! empty($previousTicketIds)) {
            $this->destroy($previousTicketIds);
        }
    }

    /**
     * Summary of sanitize Input.
     *
     * @param  string  $fieldName
     * @param  array  $inputs
     */
    private function sanitizeInput($fieldName, &$inputs)
    {
        $fieldValue = $inputs[$fieldName] ?? null;

        if (
            ! isset($fieldValue)
            || empty($fieldValue)
            || $fieldValue === '0.0000'
            || $fieldValue === '0000-00-00 00:00:00'
        ) {
            $inputs[$fieldName] = null;
        }
    }

    /**
     * Ensure ticket validity dates stay within the event availability window.
     */
    private function validateTicketDates(array $ticketInputs, BookingProduct $bookingProduct): void
    {
        if (
            empty($ticketInputs['special_price_to'])
            || ! $bookingProduct->available_to
        ) {
            return;
        }

        if (Carbon::parse($ticketInputs['special_price_to'])->gt($bookingProduct->available_to)) {
            throw ValidationException::withMessages([
                'booking.tickets' => trans('admin::app.catalog.products.edit.types.booking.event.validations.ticket-end-date-before-event-end-date'),
            ]);
        }
    }
}
