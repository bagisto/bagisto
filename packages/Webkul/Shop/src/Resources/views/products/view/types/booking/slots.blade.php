@php
    $calendarAvailability = app(\Webkul\BookingProduct\Helpers\Booking::class)->getCalendarAvailability($bookingProduct);
@endphp

<v-book-slots
    :booking-product="{{ $bookingProduct }}"
    :availability="{{ json_encode($calendarAvailability) }}"
/>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-book-slots-template"
    >
        <div>
            <x-shop::form.control-group.label class="required">
                {{ $title  ?? trans('shop::app.products.view.type.booking.slots.book-an-appointment') }}
            </x-shop::form.control-group.label>

            <div class="grid grid-cols-2 gap-x-4">
                <!-- Select Date -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label class="hidden">
                        @lang('shop::app.products.view.type.booking.slots.date')
                    </x-shop::form.control-group.label>
                    
                    <x-shop::form.control-group.control
                        type="date"
                        class="py-4"
                        name="booking[date]"
                        rules="required"
                        :label="trans('shop::app.products.view.type.booking.slots.date')"
                        :placeholder="trans('YYYY-MM-DD')"
                        ::min-date="minDate"
                        ::max-date="maxDate"
                        ::disable="disabledDates"
                        @change="getAvailableSlots"
                    />

                    <x-shop::form.control-group.error control-name="booking[date]" />
                </x-shop::form.control-group>

                <!-- Select Slots -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label class="hidden">
                        @lang('shop::app.products.view.type.booking.slots.title')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="py-4"
                        name="booking[slot]"
                        rules="required"
                        v-model="selectedSlot"
                        :label="trans('shop::app.products.view.type.booking.slots.title')"
                        :placeholder="trans('shop::app.products.view.type.booking.slots.title')"
                    >
                        <option value="">
                            @lang('shop::app.products.view.type.booking.slots.select-slot')
                        </option>
                        
                        <option v-if="! slots?.length">
                            @lang('shop::app.products.view.type.booking.slots.no-slots-available')
                        </option>

                        <option
                            v-for="slot in slots"
                            :value="slot.timestamp"
                            v-text="slot.from + ' - ' + slot.to"
                        >
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error control-name="booking[slot]" />
                </x-shop::form.control-group>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-book-slots', {
            template: '#v-book-slots-template',

            props: ['bookingProduct', 'availability', 'title'],

            data() {
                return {
                    slots: [],

                    selectedSlot: '',
                }
            },

            computed: {
                preventDays() {
                    return parseInt(this.availability?.prevent_scheduling_before) || 0;
                },

                minDate() {
                    const today = new Date();

                    const minAllowed = new Date(today);
                    minAllowed.setDate(today.getDate() + this.preventDays);

                    const availableFrom = this.availability?.available_from
                        ? new Date(this.availability.available_from + 'T00:00:00')
                        : null;

                    const effective = availableFrom && availableFrom > minAllowed
                        ? availableFrom
                        : minAllowed;

                    return this.formatDate(effective);
                },

                maxDate() {
                    if (this.availability?.available_every_week) {
                        return '';
                    }

                    if (! this.availability?.available_to) {
                        return '';
                    }

                    return this.availability.available_to;
                },

                disabledDates() {
                    const validWeekdays = this.availability?.valid_weekdays ?? [0, 1, 2, 3, 4, 5, 6];

                    if (validWeekdays.length === 7) {
                        return [];
                    }

                    return [
                        (date) => ! validWeekdays.includes(date.getDay()),
                    ];
                },
            },

            methods: {
                formatDate(d) {
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');

                    return `${year}-${month}-${day}`;
                },

                getAvailableSlots(params) {
                    let date = params.target.value;

                    this.$axios.get(`{{ route('shop.booking-product.slots.index', '') }}/${this.bookingProduct.id}`, {
                        params: { date }
                    })
                        .then((response) => {
                            this.slots = response.data.data;

                            this.selectedSlot = '';
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            }
        });
    </script>
@endpushOnce
