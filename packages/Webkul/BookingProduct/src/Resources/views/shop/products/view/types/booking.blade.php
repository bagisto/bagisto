@if (
    $product->type == 'booking'
    && $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id)
)
    <v-booking-information></v-booking-information>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-booking-information-template"
        >
            @if ($bookingProduct->location)
                <div class="grid grid-cols-2 gap-2.5 py-4">
                    <div class="flex gap-3">
                        <span class="icon-location font-bold"></span>

                        <div class="grid grid-cols-1 gap-2.5 text-sm font-medium text-[#6E6E6E]">
                            <div>
                                @lang('booking::app.shop.products.view.types.booking.location')
                            </div>

                            <div>
                                {{ $bookingProduct->location }}
                            </div>

                            <a
                                href="https://maps.google.com/maps?q={{ $bookingProduct->location }}"
                                target="_blank"
                                class="text-blue-600"
                            >
                                @lang('booking::app.shop.products.view.types.booking.view-on-map')
                            </a>
                        </div>
                    </div>

                    <div>
                        @switch($bookingProduct->type)
                            @case('default')
                                @if ($bookingProduct->default_slot->duration)
                                    <span class="icon-calendar font-bold"></span>
                            
                                    <span class="text-[#6E6E6E]">
                                        @lang('booking::app.shop.products.view.types.booking.slot-duration') :
                            
                                        @lang('booking::app.shop.products.view.types.booking.slot-duration-in-minutes', ['minutes' => $bookingProduct->default_slot->duration])
                                    </span>
                                @endif

                                @break
                            @case('appointment')
                                <span class="icon-calendar font-bold"></span>
                            
                                <span class="text-[#6E6E6E]">
                                    @lang('booking::app.shop.products.view.types.booking.slot-duration') :
                            
                                    @lang('booking::app.shop.products.view.types.booking.slot-duration-in-minutes', ['minutes' => $bookingProduct->appointment_slot->duration])
                                </span>

                                @break
                            @case('event')
                                <span class="icon-calendar font-bold"></span>
                            
                                <span class="text-[#6E6E6E]">
                                    @lang('booking::app.shop.products.view.types.booking.event-on') :
                                </span>

                                @break
                            @case('table')
                                <span class="icon-calendar font-bold"></span>
                            
                                <span class="text-[#6E6E6E]">
                                    @lang('booking::app.shop.products.view.types.booking.slot-duration') :
                            
                                    @lang('booking::app.shop.products.view.types.booking.slot-duration-in-minutes', ['minutes' => $bookingProduct->table_slot->duration])
                                </span>

                                @break
                        @endswitch
                    </div>
                </div>
            @endif

            <div class="w-full max-w-[470px] mt-5">
                @include ('booking::shop.products.view.booking.' . $bookingProduct->type, ['bookingProduct' => $bookingProduct])
            </div>
        </script>

        <script type="module">
            app.component('v-booking-information', {
                template: '#v-booking-information-template',

            });
        </script>
    @endpushOnce
@endif