<div class="booking-info-row">
    <span class="icon-calendar font-bold"></span>

    <span class="title">
        @lang('booking::app.shop.products.slot-duration') :

        @lang('booking::app.shop.products.slot-duration-in-minutes', ['minutes' => $bookingProduct->table_slot->duration])
    </span>
</div>

@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\TableSlot')

<div class="booking-info-row">
    <span class="icon-calendar font-bold"></span>

    <span class="title">
        @lang('booking::app.shop.products.today-availability')
    </span>

    <span class="value">
        {!! $bookingSlotHelper->getTodaySlotsHtml($bookingProduct) !!}
    </span>

    <v-toggler></v-toggler>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-toggler-template"
    >
        <div class="grid gap-x-2.5 gap-y-1.5 select-none">
            <!-- Details Toggler -->
            <p
                class="flex gap-x-[15px] items-center text-base cursor-pointer"
                @click="showDaysAvailability = ! showDaysAvailability"
            >
                @lang('booking::app.shop.products.slots-for-all-days')

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
                        class="text-sm font-medium"
                        v-text="day.name"
                    ></p>

                    <p class="text-sm">
                        <div v-if="day.slots && day.slots?.length">
                            <div v-for="slot in day.slots">
                                @{{ slot.from }} - @{{ slot.to }}
                            </div>
                        </div>

                        <div v-else class="text-danger">
                            @lang('booking::app.shop.products.closed')
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

@include ('booking::shop.products.view.booking.slots', [
    'bookingProduct' => $bookingProduct, 
    'title' => trans('booking::app.shop.products.book-a-table')
])

<!-- Notes -->
<x-shop::form.control-group class="w-full">
    <x-shop::form.control-group.label class="required">
        @lang('booking::app.shop.products.special-notes')
    </x-shop::form.control-group.label>

    <x-shop::form.control-group.control
        type="textarea"
        name="booking[note]"
        rules="required"
        :label="trans('booking::app.shop.products.special-notes')"
        :placeholder="trans('booking::app.shop.products.special-notes')"
    >
    </x-shop::form.control-group.control>

    <x-shop::form.control-group.error
        control-name="booking[note]"
    >
    </x-shop::form.control-group.error>
</x-shop::form.control-group>