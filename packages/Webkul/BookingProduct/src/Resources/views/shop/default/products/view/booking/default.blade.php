<div class="booking-info-row">
    <span class="icon bp-slot-icon"></span>
    <span class="title">
        {{ __('bookingproduct::app.shop.products.slot-duration') }} :

        {{ __('bookingproduct::app.shop.products.slot-duration-in-minutes', ['minutes' => 30]) }}
    </span>
</div>

@include ('bookingproduct::shop.products.view.booking.slots', ['bookingProduct' => $bookingProduct])