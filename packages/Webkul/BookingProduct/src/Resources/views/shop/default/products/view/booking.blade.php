@if ($product->type == 'booking')

    @if ($bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id))

        @push('css')
            <link rel="stylesheet" href="{{ bagisto_asset('css/default-booking.css') }}">
        @endpush

        <booking-information></booking-information>        

        @push('scripts')

            <script type="text/x-template" id="booking-information-template">
                <div class="booking-information">

                    @if ($bookingProduct->location != '')
                        <div class="booking-info-row">
                            <span class="icon bp-location-icon"></span>
                            <span class="title">{{ __('bookingproduct::app.shop.products.location') }}</span>
                            <span class="value">{{ $bookingProduct->location }}</span>
                            <a href="https://maps.google.com/maps?q={{ $bookingProduct->location }}" target="_blank">View on Map</a>
                        </div>
                    @endif

                    @include ('bookingproduct::shop.products.view.booking.' . $bookingProduct->type, ['bookingProduct' => $bookingProduct])

                </div>
            </script>

            <script>
                Vue.component('booking-information', {
                    template: '#booking-information-template',

                    inject: ['$validator'],

                    data: function() {
                        return {
                            showDaysAvailability: false
                        }
                    }
                });
            </script>
        
        @endpush

    @endif

@endif