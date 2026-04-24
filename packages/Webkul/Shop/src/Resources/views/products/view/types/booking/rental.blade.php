@php
    $calendarAvailability = app(\Webkul\BookingProduct\Helpers\Booking::class)->getCalendarAvailability($bookingProduct);
@endphp

<v-rental-slots
    :booking-product="{{ $bookingProduct }}"
    :availability="{{ json_encode($calendarAvailability) }}"
    :base-price="{{ $product->getTypeInstance()->getFinalPrice() ?? 0 }}"
></v-rental-slots>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-rental-slots-template"
    >
        <div class="grid grid-cols-1 gap-2.5">
            <template v-if="renting_type == 'daily_hourly'">
                <x-shop::form.control-group.label class="w-full required">
                    @lang('shop::app.products.view.type.booking.rental.choose-rent-option')
                </x-shop::form.control-group.label>

                <div class="mb-3 grid grid-cols-2 gap-2.5">
                    <!-- Daily Radio Button -->
                    <span class="flex gap-x-4">
                        <input
                            type="radio"
                            class="hidden peer"
                            id="booking[daily]"
                            name="booking[renting_type]"
                            value="daily"
                            v-model="sub_renting_type"
                        >

                        <label
                            class="text-2xl icon-radio-unselect peer-checked:icon-radio-select text-navyBlue"
                            for="booking[daily]"
                        >
                        </label>

                        <label
                            class="cursor-pointer text-[#6E6E6E]"
                            for="booking[daily]"
                        >
                            @lang('shop::app.products.view.type.booking.rental.daily-basis')
                        </label>
                    </span>

                    <!-- Hourly Radio Button -->
                    <span class="flex gap-x-4">
                        <input
                            type="radio"
                            class="hidden peer"
                            id="booking[hourly]"
                            name="booking[renting_type]"
                            value="hourly"
                            v-model="sub_renting_type"
                        >

                        <label
                            class="text-2xl icon-radio-unselect peer-checked:icon-radio-select text-navyBlue"
                            for="booking[hourly]"
                        >
                        </label>

                        <label
                            class="cursor-pointer text-[#6E6E6E]"
                            for="booking[hourly]"
                        >
                            @lang('shop::app.products.view.type.booking.rental.hourly-basis')
                        </label>
                    </span>
                </div>
            </template>

            <div class="flex flex-col gap-2.5" v-if="renting_type != 'daily' && sub_renting_type == 'hourly'">
                <div  class="grid gap-1.5">
                    <label class="required">
                        @lang('shop::app.products.view.type.booking.rental.select-slot')
                    </label>

                    <div class="flex gap-2.5">
                        <!-- Select Slot Date -->
                        <x-shop::form.control-group class="!mb-0 w-full">
                            <x-shop::form.control-group.label class="hidden">
                                @lang('shop::app.products.view.type.booking.rental.select-date')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="date"
                                name="booking[date]"
                                class="max-sm!px-2 max-sm!text-xs"
                                rules="required"
                                :label="trans('shop::app.products.view.type.booking.rental.select-date')"
                                :placeholder="trans('shop::app.products.view.type.booking.rental.select-date')"
                                ::min-date="minDate"
                                ::max-date="maxDate"
                                ::disable="disabledDates"
                                @change="dateSelected($event)"
                            />

                            <x-shop::form.control-group.error control-name="booking[date]" />
                        </x-shop::form.control-group>

                        <!-- Select Slot -->
                        <x-shop::form.control-group class="!mb-0 w-full">
                            <x-shop::form.control-group.label class="hidden">
                                @lang('shop::app.products.view.type.booking.rental.select-slot')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                class="!mb-1"
                                name="booking[slot]"
                                rules="required"
                                v-model="selected_slot"
                                :label="trans('shop::app.products.view.type.booking.rental.select-date')"
                                :placeholder="trans('shop::app.products.view.type.booking.rental.select-date')"
                            >
                                <option value="">
                                    @lang('shop::app.products.view.type.booking.rental.select-slot')
                                </option>
                                
                                <option v-if="! slots?.length">
                                    @lang('shop::app.products.view.type.booking.rental.no-slots-available')
                                </option>

                                <option
                                    v-for="(slot, index) in slots"
                                    :value="index"
                                    v-text="slot.time"
                                >
                                </option>
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error control-name="booking[slot]" />
                        </x-shop::form.control-group>
                    </div>
                </div>

                <div
                    class="grid gap-1.5"
                    v-if="parseInt(slots[selected_slot] && slots[selected_slot]?.slots?.length)"
                >
                    <label class="required">
                        @lang('shop::app.products.view.type.booking.rental.select-rent-time')
                    </label>

                    <div class="flex gap-2.5">
                        <!-- Select Time Slot From -->
                        <x-shop::form.control-group class="!mb-0 w-full">
                            <x-shop::form.control-group.label class="hidden">
                                @lang('shop::app.products.view.type.booking.rental.select-date')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                name="booking[slot][from]"
                                rules="required"
                                v-model="booking.slot_from"
                                :label="trans('shop::app.products.view.type.booking.rental.select-date')"
                                :placeholder="trans('shop::app.products.view.type.booking.rental.select-date')"
                            >
                                <option value="">
                                    @lang('shop::app.products.view.type.booking.rental.select-time-slot')
                                </option>

                                <option
                                    v-for="slot in slots[selected_slot]?.slots"
                                    :value="slot.from_timestamp"
                                    v-text="slot.from"
                                >
                                </option>
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error control-name="booking[slot][from]" />
                        </x-shop::form.control-group>

                        <!-- Select Time Slot To -->
                        <x-shop::form.control-group class="!mb-0 w-full">
                            <x-shop::form.control-group.label class="hidden">
                                @lang('shop::app.products.view.type.booking.rental.slot')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                name="booking[slot][to]"
                                rules="required"
                                v-model="booking.slot_to"
                                :label="trans('shop::app.products.view.type.booking.rental.slot')"
                                :placeholder="trans('shop::app.products.view.type.booking.rental.slot')"
                            >
                                <option value="">
                                    @lang('shop::app.products.view.type.booking.rental.select-time-slot')
                                </option>

                                <option
                                    v-for="slot in availableToSlots"
                                    :value="slot?.to_timestamp"
                                    v-text="slot.to"
                                >
                                </option>
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error control-name="booking[slot][to]" />
                        </x-shop::form.control-group>
                    </div>
                </div>
            </div>

            <div v-else>
                <label class="required">
                    @lang('shop::app.products.view.type.booking.rental.select-date')
                </label>

                <div class="flex gap-2.5">
                    <!-- Select Date From -->
                    <x-shop::form.control-group class="!mb-0 w-full">
                        <x-shop::form.control-group.label class="hidden">
                            @lang('shop::app.products.view.type.booking.rental.from')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="date"
                            name="booking[date_from]"
                            rules="required"
                            v-model="booking.date_from"
                            :label="trans('shop::app.products.view.type.booking.rental.from')"
                            :placeholder="trans('shop::app.products.view.type.booking.rental.from')"
                            ::min-date="minDate"
                            ::max-date="maxDate"
                            ::disable="disabledDates"
                            @change="dateSelected($event)"
                        />

                        <x-shop::form.control-group.error control-name="booking[date_from]" />
                    </x-shop::form.control-group>

                    <!-- Select Date To -->
                    <x-shop::form.control-group class="!mb-0 w-full">
                        <x-shop::form.control-group.label class="hidden">
                            @lang('shop::app.products.view.type.booking.rental.to')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="date"
                            name="booking[date_to]"
                            ::rules="'required|after:' + booking.date_from"
                            v-model="booking.date_to"
                            :label="trans('shop::app.products.view.type.booking.rental.to')"
                            :placeholder="trans('shop::app.products.view.type.booking.rental.to')"
                            ::min-date="booking.date_from || minDate"
                            ::max-date="maxDate"
                            ::disable="disabledDates"
                            @change="dateSelected($event)"
                        />

                        <x-shop::form.control-group.error control-name="booking[date_to]" />
                    </x-shop::form.control-group>
                </div>
            </div>

            <!-- Rental Price Breakdown -->
            <div class="mt-3 rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="mb-3 font-semibold">
                    @lang('shop::app.products.view.type.booking.rental.summary-title')
                </p>

                <template v-if="hasSelection">
                    <div class="flex items-center justify-between py-1">
                        <span class="text-zinc-600 dark:text-zinc-400">
                            @lang('shop::app.products.view.type.booking.rental.base-rental-fee')
                        </span>

                        <span
                            class="font-medium"
                            v-text="formattedBasePrice"
                        >
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1">
                        <span
                            class="text-zinc-600 dark:text-zinc-400"
                            v-text="rateLineLabel"
                        >
                        </span>

                        <span
                            class="font-medium"
                            v-text="formattedRateTotal"
                        >
                        </span>
                    </div>

                    <div class="mt-3 flex items-center justify-between border-t border-zinc-200 pt-3 dark:border-zinc-800">
                        <span class="font-semibold">
                            @lang('shop::app.products.view.type.booking.rental.total')
                        </span>

                        <span
                            class="text-base font-semibold"
                            v-text="formattedGrandTotal"
                        >
                        </span>
                    </div>
                </template>

                <template v-else>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        @lang('shop::app.products.view.type.booking.rental.select-dates-hint')
                    </p>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        defineRule('after', (value, [target]) => {
            if (! value || ! target) {
                return false;
            }

            return new Date(value) >= new Date(target);
        });

        app.component('v-rental-slots', {
            template: '#v-rental-slots-template',

            props: ['bookingProduct', 'availability', 'basePrice'],

            data() {
                return {
                    renting_type: "{{ $bookingProduct->rental_slot?->renting_type ?? 'daily' }}",

                    sub_renting_type: 'hourly',

                    slots: [],

                    selected_slot: '',

                    booking: {
                        date_from: '',

                        date_to: '',

                        slot_from: '',

                        slot_to: '',
                    },
                }
            },

            computed: {
                minDate() {
                    const today = new Date();

                    const availableFrom = this.availability?.available_from
                        ? new Date(this.availability.available_from + 'T00:00:00')
                        : null;

                    const effective = availableFrom && availableFrom > today
                        ? availableFrom
                        : today;

                    return this.formatDate(effective);
                },

                maxDate() {
                    if (this.availability?.available_every_week) {
                        return '';
                    }

                    return this.availability?.available_to ?? '';
                },

                disabledDates() {
                    const disabledDates = this.availability?.disabled_dates ?? [];

                    const predicates = [];

                    /**
                     * The `valid_weekdays` array reflects the days that have
                     * *hourly* slot configuration. For a daily rental, every
                     * day within the availability window should be selectable
                     * regardless of the hourly schedule, so we only apply the
                     * weekday filter when the user is actually booking an
                     * hourly slot.
                     */
                    const activeType = this.renting_type === 'daily_hourly'
                        ? this.sub_renting_type
                        : this.renting_type;

                    if (activeType === 'hourly') {
                        const validWeekdays = this.availability?.valid_weekdays ?? [0, 1, 2, 3, 4, 5, 6];

                        if (validWeekdays.length < 7) {
                            predicates.push((date) => ! validWeekdays.includes(date.getDay()));
                        }
                    }

                    if (disabledDates.length) {
                        predicates.push(...disabledDates);
                    }

                    return predicates;
                },

                activeRentingType() {
                    return this.renting_type === 'daily_hourly'
                        ? this.sub_renting_type
                        : this.renting_type;
                },

                availableToSlots() {
                    const subSlots = this.slots[this.selected_slot]?.slots ?? [];

                    const from = Number(this.booking.slot_from);

                    if (! from) {
                        return subSlots;
                    }

                    return subSlots.filter(slot => Number(slot.to_timestamp) > from);
                },

                durationDays() {
                    if (! this.booking.date_from || ! this.booking.date_to) {
                        return 0;
                    }

                    const from = new Date(this.booking.date_from + 'T00:00:00');
                    const to = new Date(this.booking.date_to + 'T00:00:00');
                    const diff = Math.round((to - from) / (1000 * 60 * 60 * 24)) + 1;

                    return Math.max(1, diff);
                },

                durationHours() {
                    if (! this.booking.slot_from || ! this.booking.slot_to) {
                        return 0;
                    }

                    const diff = Number(this.booking.slot_to) - Number(this.booking.slot_from);

                    return Math.max(0, Math.round(diff / 3600));
                },

                duration() {
                    return this.activeRentingType === 'hourly'
                        ? this.durationHours
                        : this.durationDays;
                },

                rateUnit() {
                    const slot = this.bookingProduct?.rental_slot ?? {};

                    return this.activeRentingType === 'hourly'
                        ? Number(slot.hourly_price ?? 0)
                        : Number(slot.daily_price ?? 0);
                },

                rateTotal() {
                    return this.rateUnit * this.duration;
                },

                grandTotal() {
                    return Number(this.basePrice ?? 0) + this.rateTotal;
                },

                hasSelection() {
                    return this.duration > 0;
                },

                rateLineLabel() {
                    const durationWord = this.activeRentingType === 'hourly'
                        ? (this.duration === 1
                            ? "@lang('shop::app.products.view.type.booking.rental.hour')"
                            : "@lang('shop::app.products.view.type.booking.rental.hours')")
                        : (this.duration === 1
                            ? "@lang('shop::app.products.view.type.booking.rental.day')"
                            : "@lang('shop::app.products.view.type.booking.rental.days')");

                    const template = this.activeRentingType === 'hourly'
                        ? "@lang('shop::app.products.view.type.booking.rental.hourly-rate-line')"
                        : "@lang('shop::app.products.view.type.booking.rental.daily-rate-line')";

                    return template
                        .replace(':count', `${this.duration} ${durationWord}`)
                        .replace(':rate', this.$shop.formatPrice(this.rateUnit));
                },

                formattedBasePrice() {
                    return this.$shop.formatPrice(Number(this.basePrice ?? 0));
                },

                formattedRateTotal() {
                    return this.$shop.formatPrice(this.rateTotal);
                },

                formattedGrandTotal() {
                    return this.$shop.formatPrice(this.grandTotal);
                },
            },

            watch: {
                'booking.slot_from'(newVal) {
                    const from = Number(newVal);

                    if (from && Number(this.booking.slot_to) <= from) {
                        this.booking.slot_to = '';
                    }
                },
            },

            methods: {
                formatDate(d) {
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');

                    return `${year}-${month}-${day}`;
                },

                dateSelected(params) {
                    let date = params.target.value;

                    this.$axios.get(`{{ route('shop.booking-product.slots.index', '') }}/${this.bookingProduct.id}`, {
                        params: { date }
                    })
                        .then((response) => {
                            this.selected_slot = '';

                            this.slots = response.data.data;
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            },
        });
    </script>
@endpushOnce