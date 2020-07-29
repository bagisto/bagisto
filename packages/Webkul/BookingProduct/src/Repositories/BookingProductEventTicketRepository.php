<?php

namespace Webkul\BookingProduct\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Str;

class BookingProductEventTicketRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\BookingProduct\Contracts\BookingProductEventTicket';
    }

    /**
     * @param  array  $data
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return void
     */
    public function saveEventTickets($data, $bookingProduct)
    {
        $previousTicketIds = $bookingProduct->event_tickets()->pluck('id');

        if (isset($data['tickets'])) {
            foreach ($data['tickets'] as $ticketId => $ticketInputs) {

                if (
                    ! array_key_exists('special_price', $ticketInputs)
                    || empty($ticketInputs['special_price'])
                    || $ticketInputs['special_price'] === '0.0000'
                ) {
                    $ticketInputs['special_price'] = null;
                }

                if (
                    ! array_key_exists('special_price_from', $ticketInputs)
                    || empty($ticketInputs['special_price_from'])
                    || $ticketInputs['special_price_from'] === '0000-00-00 00:00:00'
                ) {
                    $ticketInputs['special_price_from'] = null;
                }

                if (
                    ! array_key_exists('special_price_to', $ticketInputs)
                    || empty($ticketInputs['special_price_to'])
                    || $ticketInputs['special_price_to'] === '0000-00-00 00:00:00'
                ) {
                    $ticketInputs['special_price_to'] = null;
                }

                if (Str::contains($ticketId, 'ticket_')) {
                    $this->create(array_merge([
                        'booking_product_id' => $bookingProduct->id,
                    ], $ticketInputs));
                } else {
                    if (is_numeric($index = $previousTicketIds->search($ticketId))) {
                        $previousTicketIds->forget($index);
                    }

                    $this->update($ticketInputs, $ticketId);
                }
            }
        }

        foreach ($previousTicketIds as $previousTicketId) {
            $this->delete($previousTicketId);
        }
    }
}