<div class="grid grid-cols-1 gap-6">
    <div class="flex gap-3">
        <span class="icon-calendar text-2xl"></span>

        <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
            <p class="text-[#6E6E6E]">
                @lang('shop::app.products.view.type.booking.appointment.slot-duration') :
            </p>

            <div>
                @lang('shop::app.products.view.type.booking.appointment.slot-duration-in-minutes', [
                    'minutes' => $bookingProduct->appointment_slot->duration
                ])
            </div>
        </div>
    </div>

    @inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\AppointmentSlot')

    <div class="flex gap-3">
        <span class="icon-calendar text-2xl"></span>

        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
                <p class="text-[#6E6E6E]">
                    @lang('shop::app.products.view.type.booking.appointment.today-availability')
                </p>
    
                <span>
                    {!! $bookingSlotHelper->getTodaySlotsHtml($bookingProduct) !!}
                </span>
            </div>

            <!-- Toggler Vue Component -->
            <v-toggler />
        </div>
    </div>
    
    @include ('shop::products.view.types.booking.slots', ['bookingProduct' => $bookingProduct])
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-toggler-template"
    >
        <div class="grid w-max select-none gap-3">
            <!-- Details Toggler -->
            <p
                class="flex cursor-pointer items-center gap-x-[15px] text-sm font-medium text-blue-600"
                @click="showDaysAvailability = ! showDaysAvailability"
            >
                @lang('shop::app.products.view.type.booking.appointment.see-details')

                <span
                    class="text-xl font-bold"
                    :class="{'icon-arrow-up': showDaysAvailability, 'icon-arrow-down': ! showDaysAvailability}"
                >
                </span>
            </p>

            <!-- Option Details -->
            <div
                class="grid grid-cols-2 gap-3"
                v-show="showDaysAvailability"
                v-for="day in days"
            >
                <!-- Name -->
                <p
                    class="text-gray text-sm font-medium"
                    v-text="day.name"
                >
                </p>

                <!-- Slot Duration -->
                <p class="grid gap-y-2.5 text-sm text-gray-600">
                    <template v-if="day.slots && day.slots?.length">
                        <div v-for="slot in day.slots">
                            @{{ slot.from }} - @{{ slot.to }}
                        </div>
                    </template>

                    <div v-else>
                        @lang('shop::app.products.view.type.booking.appointment.closed')
                    </div>
                </p>
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