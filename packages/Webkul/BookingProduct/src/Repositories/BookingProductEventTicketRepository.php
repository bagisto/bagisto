<?php

namespace Webkul\BookingProduct\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Str;

/**
 * BookingProductEventTicket Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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
     * @param  array                                            $data
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return void
     */
    public function saveEventTickets($data, $bookingProduct)
    {
        $previousTicketIds = $bookingProduct->event_tickets()->pluck('id');

        if (isset($data['tickets'])) {
            foreach ($data['tickets'] as $ticketId => $ticketInputs) {
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