@if ($bookingProduct->default_slot->duration)
    <div class="booking-info-row">
        <span class="icon-calendar font-bold"></span>

        <span class="title">
            @lang('booking::app.shop.products.slot-duration') :

            @lang('booking::app.shop.products.slot-duration-in-minutes', ['minutes' => $bookingProduct->default_slot->duration])
        </span>
    </div>
@endif

@include('booking::shop.products.view.booking.slots', ['bookingProduct' => $bookingProduct])