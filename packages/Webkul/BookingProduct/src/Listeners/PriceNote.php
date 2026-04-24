<?php

namespace Webkul\BookingProduct\Listeners;

use Webkul\Theme\ViewRenderEventManager;

class PriceNote
{
    /**
     * Append a customer-facing note below the product price on the product
     * detail page when a booking product has a sub-type whose final price is
     * composed at add-to-cart time (rental, event).
     */
    public function addNote(ViewRenderEventManager $eventManager): void
    {
        $product = $eventManager->getParam('product');

        if (! $product || $product->type !== 'booking') {
            return;
        }

        $bookingProduct = $product->booking_products()->first();

        $noteKey = match ($bookingProduct?->type) {
            'rental' => 'shop::app.products.view.type.booking.rental.rental-fee-note',
            'event' => 'shop::app.products.view.type.booking.event.base-fee-note',
            default => null,
        };

        if (! $noteKey) {
            return;
        }

        $html = '<span class="mt-1 block text-xs font-normal text-zinc-500 max-sm:text-[11px]">'
            .e(trans($noteKey))
            .'</span>';

        $eventManager->addTemplate($html);
    }
}
