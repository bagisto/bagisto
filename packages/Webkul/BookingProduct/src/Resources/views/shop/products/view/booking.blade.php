@if (
    $product->type == 'booking'
    && $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id)
)
    <v-booking-information></v-booking-information>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-booking-information-template">
            @if ($bookingProduct->location)
                <div class="grid grid-cols-2 gap-2.5 my-2.5">
                    <div class="flex">
                        <div class="icon-location font-bold"></div>

                        <div>
                            <div>
                                @lang('booking::app.shop.products.location')
                            </div>

                            <div>
                                {{ $bookingProduct->location }}
                            </div>

                            <a
                                href="https://maps.google.com/maps?q={{ $bookingProduct->location }}"
                                target="_blank"
                            >
                                View on Map
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @include ('booking::shop.products.view.booking.' . $bookingProduct->type, ['bookingProduct' => $bookingProduct])
        </script>

        <script type="module">
            app.component('v-booking-information', {
                template: '#v-booking-information-template',

            });
        </script>
    @endpushOnce
@endif