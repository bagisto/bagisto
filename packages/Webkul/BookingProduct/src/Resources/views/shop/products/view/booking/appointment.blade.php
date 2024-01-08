
@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\AppointmentSlot')

<div class="grid grid-cols-1 gap-2.5">
    <div>
        <span class="icon-calendar font-bold"></span>

        <span>
            @lang('booking::app.shop.products.view.booking.appointment.today-availability')
        </span>

        <span>
            {!! $bookingSlotHelper->getTodaySlotsHtml($bookingProduct) !!}
        </span>
    </div>

    <v-toggler></v-toggler>

    @include ('booking::shop.products.view.booking.slots', ['bookingProduct' => $bookingProduct])
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-toggler-template"
    >
        <div class="grid gap-2.5 w-max select-none">
            <!-- Details Toggler -->
            <p
                class="flex gap-x-[15px] items-center text-base cursor-pointer"
                @click="showDaysAvailability = ! showDaysAvailability"
            >
                @lang('shop::app.checkout.cart.mini-cart.see-details')

                <span
                    class="text-2xl"
                    :class="{'icon-arrow-up': showDaysAvailability, 'icon-arrow-down': ! showDaysAvailability}"
                >
                </span>
            </p>

            <!-- Option Details -->
            <div
                class="grid gap-2"
                v-show="showDaysAvailability"
            >
                <template v-for="day in days">
                    <p
                        class="text-sm text-gray font-medium"
                        v-text="day.name"
                    >
                    </p>

                    <p class="text-sm">
                        <div v-if="day.slots && day.slots?.length">
                            <div v-for="slot in day.slots">
                                @{{ slot.from }} - @{{ slot.to }}
                            </div>
                        </div>

                        <div v-else class="text-danger">
                            @lang('booking::app.shop.products.view.booking.appointment.closed')
                        </div>
                    </p>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toggler', {
            template: '#v-toggler-template',

            data() {
                return{
                    showDaysAvailability: '',

                    days: @json($bookingSlotHelper->getWeekSlotDurations($bookingProduct)),
                }
            },
        })
    </script>
@endpushOnce