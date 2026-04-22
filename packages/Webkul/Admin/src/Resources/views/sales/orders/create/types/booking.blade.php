{!! view_render_event('bagisto.admin.sales.order.create.types.booking.before') !!}

<v-booking-product-options
    :errors="errors"
    :product-options="selectedProductOptions"
></v-booking-product-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.booking.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-booking-product-options-template"
    >
        <div class="p-4">
            <!-- Loading -->
            <template v-if="isLoading">
                <p class="text-sm text-gray-500 dark:text-gray-300">
                    @lang('admin::app.sales.orders.create.types.booking.loading')
                </p>
            </template>

            <!-- Loaded -->
            <template v-else-if="config">
                <!-- Default / Appointment / Table -->
                <template v-if="['default', 'appointment', 'table'].includes(config.type)">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.sales.orders.create.types.booking.date')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="booking[date]"
                            rules="required"
                            :label="trans('admin::app.sales.orders.create.types.booking.date')"
                            ::min-date="minDate"
                            ::max-date="maxDate"
                            ::disable="disabledDates"
                            @change="onDateChange"
                        />

                        <x-admin::form.control-group.error control-name="booking[date]" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.sales.orders.create.types.booking.slot')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="booking[slot]"
                            rules="required"
                            v-model="selectedSlot"
                            :label="trans('admin::app.sales.orders.create.types.booking.slot')"
                        >
                            <option value="">
                                @lang('admin::app.sales.orders.create.types.booking.select-slot')
                            </option>

                            <option v-if="! slots.length" disabled>
                                @lang('admin::app.sales.orders.create.types.booking.no-slots-available')
                            </option>

                            <option
                                v-for="slot in slots"
                                :value="slot.timestamp"
                                v-text="slot.from + ' - ' + slot.to"
                            ></option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="booking[slot]" />
                    </x-admin::form.control-group>

                    <!-- Optional note (table only) -->
                    <x-admin::form.control-group v-if="config.type === 'table'">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.sales.orders.create.types.booking.note')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="booking[note]"
                            :label="trans('admin::app.sales.orders.create.types.booking.note')"
                        />
                    </x-admin::form.control-group>
                </template>

                <!-- Rental -->
                <template v-else-if="config.type === 'rental'">
                    <x-admin::form.control-group v-if="rentingTypeIsSelectable">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.sales.orders.create.types.booking.renting-type')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="booking[renting_type]"
                            rules="required"
                            v-model="rentingType"
                            :label="trans('admin::app.sales.orders.create.types.booking.renting-type')"
                        >
                            <option value="daily">@lang('admin::app.sales.orders.create.types.booking.daily')</option>
                            <option value="hourly">@lang('admin::app.sales.orders.create.types.booking.hourly')</option>
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>

                    <!-- Hidden fallback when type is fixed -->
                    <input
                        v-if="! rentingTypeIsSelectable"
                        type="hidden"
                        name="booking[renting_type]"
                        :value="rentingType"
                    >

                    <!-- Daily -->
                    <template v-if="rentingType === 'daily'">
                        <div class="grid grid-cols-2 gap-4">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.sales.orders.create.types.booking.date-from')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="booking[date_from]"
                                    rules="required"
                                    :label="trans('admin::app.sales.orders.create.types.booking.date-from')"
                                    ::min-date="minDate"
                                    ::max-date="maxDate"
                                    ::disable="disabledDates"
                                    @change="onDateFromChange"
                                />

                                <x-admin::form.control-group.error control-name="booking[date_from]" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group v-if="dateFrom">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.sales.orders.create.types.booking.date-to')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="booking[date_to]"
                                    rules="required"
                                    :label="trans('admin::app.sales.orders.create.types.booking.date-to')"
                                    ::min-date="dateFrom"
                                    ::max-date="maxDate"
                                    ::disable="disabledDates"
                                />

                                <x-admin::form.control-group.error control-name="booking[date_to]" />
                            </x-admin::form.control-group>
                        </div>
                    </template>

                    <!-- Hourly -->
                    <template v-if="rentingType === 'hourly'">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.sales.orders.create.types.booking.date')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                name="booking[date]"
                                rules="required"
                                :label="trans('admin::app.sales.orders.create.types.booking.date')"
                                ::min-date="minDate"
                                ::max-date="maxDate"
                                ::disable="disabledDates"
                                @change="onDateChange"
                            />

                            <x-admin::form.control-group.error control-name="booking[date]" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.sales.orders.create.types.booking.slot')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="booking[slot][from]"
                                rules="required"
                                v-model="hourlyFrom"
                                :label="trans('admin::app.sales.orders.create.types.booking.slot')"
                            >
                                <option value="">@lang('admin::app.sales.orders.create.types.booking.select-slot')</option>

                                <option
                                    v-for="slot in hourlySlotOptions"
                                    :value="slot.from_timestamp"
                                    v-text="slot.from + ' - ' + slot.to"
                                ></option>
                            </x-admin::form.control-group.control>

                            <input
                                type="hidden"
                                name="booking[slot][to]"
                                :value="hourlyTo"
                            >

                            <x-admin::form.control-group.error control-name="booking[slot][from]" />
                        </x-admin::form.control-group>
                    </template>
                </template>

                <!-- Event -->
                <template v-else-if="config.type === 'event'">
                    <div class="grid gap-4">
                        <div
                            v-for="ticket in config.event_tickets"
                            :key="ticket.id"
                            class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700"
                        >
                            <div class="grid gap-1">
                                <p class="font-medium dark:text-white" v-text="ticket.name"></p>
                                <p class="text-sm text-gray-500 dark:text-gray-300" v-text="ticket.formatted_price_text"></p>
                            </div>

                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.control
                                    type="text"
                                    ::name="'booking[qty][' + ticket.id + ']'"
                                    rules="numeric|min_value:0"
                                    ::value="0"
                                    ::max="ticket.qty || 999"
                                    :label="trans('admin::app.sales.orders.create.types.booking.quantity')"
                                />
                            </x-admin::form.control-group>
                        </div>
                    </div>
                </template>
            </template>

            <template v-else>
                <p class="text-sm text-red-600">
                    @lang('admin::app.sales.orders.create.types.booking.config-missing')
                </p>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-booking-product-options', {
            template: '#v-booking-product-options-template',

            props: ['errors', 'productOptions'],

            data() {
                return {
                    isLoading: true,
                    config: null,
                    slots: [],
                    selectedDate: '',
                    selectedSlot: '',
                    rentingType: 'daily',
                    dateFrom: '',
                    hourlyFrom: '',
                };
            },

            mounted() {
                this.loadConfig();
            },

            computed: {
                rentingTypeIsSelectable() {
                    return this.config?.rental_slot?.renting_type === 'daily_hourly';
                },

                minDate() {
                    const today = new Date();

                    const availableFrom = this.config?.calendar?.available_from
                        ? new Date(this.config.calendar.available_from + 'T00:00:00')
                        : null;

                    const effective = availableFrom && availableFrom > today ? availableFrom : today;

                    return this.formatDate(effective);
                },

                maxDate() {
                    if (this.config?.calendar?.available_every_week) {
                        return '';
                    }

                    return this.config?.calendar?.available_to ?? '';
                },

                disabledDates() {
                    const validWeekdays = this.config?.calendar?.valid_weekdays ?? [0, 1, 2, 3, 4, 5, 6];
                    const disabled = this.config?.calendar?.disabled_dates ?? [];

                    const predicates = [];

                    if (validWeekdays.length < 7) {
                        predicates.push((date) => ! validWeekdays.includes(date.getDay()));
                    }

                    if (disabled.length) {
                        predicates.push(...disabled);
                    }

                    return predicates;
                },

                hourlySlotOptions() {
                    return this.slots.flatMap((entry) => entry.slots ?? []);
                },

                hourlyTo() {
                    if (! this.hourlyFrom) {
                        return '';
                    }

                    const match = this.hourlySlotOptions.find((s) => String(s.from_timestamp) === String(this.hourlyFrom));

                    return match?.to_timestamp ?? '';
                },
            },

            methods: {
                formatDate(d) {
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');

                    return `${year}-${month}-${day}`;
                },

                loadConfig() {
                    this.isLoading = true;

                    this.$axios
                        .get("{{ route('admin.sales.booking-product.config', ':id') }}".replace(':id', this.productOptions.product.id))
                        .then((response) => {
                            this.config = response.data.data;

                            if (this.config?.type === 'rental') {
                                const type = this.config.rental_slot?.renting_type;

                                this.rentingType = type === 'hourly' ? 'hourly' : 'daily';
                            }

                            this.isLoading = false;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                },

                onDateChange(event) {
                    const date = event?.target?.value ?? '';

                    this.selectedDate = date;

                    this.fetchSlots(date);
                },

                onDateFromChange(event) {
                    this.dateFrom = event?.target?.value ?? '';
                },

                fetchSlots(date) {
                    if (! date) {
                        this.slots = [];

                        return;
                    }

                    this.$axios
                        .get("{{ route('admin.sales.booking-product.slots', ':id') }}".replace(':id', this.productOptions.product.id), {
                            params: { date },
                        })
                        .then((response) => {
                            this.slots = response.data.data ?? [];
                            this.selectedSlot = '';
                            this.hourlyFrom = '';
                        })
                        .catch(() => {
                            this.slots = [];
                        });
                },
            },
        });
    </script>
@endPushOnce
