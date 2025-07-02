@if ($product->type == 'booking')

    @php
        $bookingProduct = $product->booking_products()->first();
    @endphp
    
    {!! view_render_event('bagisto.shop.products.view.booking.before', ['product' => $product]) !!}

    <v-booking-information></v-booking-information>

    {!! view_render_event('bagisto.shop.products.view.booking.before', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-booking-information-template"
        >
            <div class="mt-6 grid w-full max-w-[470px] grid-cols-1 gap-6">
                @if ($bookingProduct->location)
                    <div class="flex gap-4">
                        <span class="icon-location text-2xl"></span>

                        <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
                            <p>
                                @lang('shop::app.products.view.type.booking.location')
                            </p>

                            <div class="grid grid-cols-1 gap-3">
                                <p class="text-[#6E6E6E]">{{ $bookingProduct->location }}</p>

                                <a
                                    href="https://maps.google.com/maps?q={{ $bookingProduct->location }}"
                                    target="_blank"
                                    class="w-fit text-blue-600 hover:text-blue-800"
                                >
                                    @lang('shop::app.products.view.type.booking.view-on-map')
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="w-full max-w-[470px]">
                    @include ('shop::products.view.types.booking.' . $bookingProduct->type, ['bookingProduct' => $bookingProduct])
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-booking-information', {
                template: '#v-booking-information-template',

            });
        </script>
    @endpushOnce
@endif
