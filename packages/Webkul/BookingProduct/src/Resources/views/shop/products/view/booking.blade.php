@if ($product->type == 'booking')

    @if ($bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->product_id))

        @push('css')
            <link rel="stylesheet" href="{{ bagisto_asset('css/booking-product.css') }}">
        @endpush

        <booking-information></booking-information>        

        @push('scripts')

            <script type="text/x-template" id="booking-information-template">
                <div class="booking-information">

                    <div class="booking-info-row">
                        <span class="icon bp-location-icon"></span>
                        <span class="title">{{ __('bookingproduct::app.shop.products.location') }}</span>
                        <span class="value">New Ashok Nagar</span>
                        <a href="" target="_blank">View on Map</a>
                    </div>

                    <div class="booking-info-row">
                        <span class="icon bp-phone-icon"></span>
                        <span class="title">
                            {{ __('bookingproduct::app.shop.products.contact') }} :

                            <a href="">{{ __('bookingproduct::app.shop.products.email') }}</a>
                        </span>
                    </div>

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
                    },

                    created: function() {
                    }

                });
            </script>
        
        @endpush

    @endif

@endif