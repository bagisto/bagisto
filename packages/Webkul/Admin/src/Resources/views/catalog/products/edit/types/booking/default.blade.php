{!! view_render_event('bagisto.admin.catalog.product.edit.booking.default.before', ['product' => $product]) !!}

<!-- Vue Component -->
<v-default-booking></v-default-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.booking.default.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-default-booking-template"
    >
        <!-- Type -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.default.description')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="booking[booking_type]"
                rules="required"
                v-model="default_booking.booking_type"
                :label="trans('admin::app.catalog.products.edit.types.booking.default.description')"
                @slotType="slotType"
            >
                @foreach (['one', 'many'] as $item)
                    <option value="{{ $item }}">
                        @lang('admin::app.catalog.products.edit.types.booking.default.' . $item)
                    </option>
                @endforeach
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error control-name="booking[booking_type]" />
        </x-admin::form.control-group>

        <template v-if="default_booking.booking_type == 'many'">
            <!-- Slot Duration -->
            <x-admin::form.control-group class="w-full">
                <x-admin::form.control-group.label class="required">
                    @lang('admin::app.catalog.products.edit.types.booking.default.slot-duration')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="booking[duration]"
                    rules="required|min_value:1"
                    v-model="default_booking.duration"
                    :label="trans('admin::app.catalog.products.edit.types.booking.default.slot-duration')"
                />

                <x-admin::form.control-group.error control-name="booking[duration]" />
            </x-admin::form.control-group>

            <!-- Break Time -->
            <x-admin::form.control-group class="w-full">
                <x-admin::form.control-group.label class="required">
                    @lang('admin::app.catalog.products.edit.types.booking.default.break-duration')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="booking[break_time]"
                    rules="required|min_value:1"
                    v-model="default_booking.break_time"
                    :label="trans('admin::app.catalog.products.edit.types.booking.default.break-duration')"
                />

                <x-admin::form.control-group.error control-name="booking[break_time]" />
            </x-admin::form.control-group>
        </template>

        <!-- Slots Component -->
        <div class="flex items-center justify-between gap-5 py-2">
            <div class="flex flex-col gap-2">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.catalog.products.edit.types.booking.default.slot-title')
                </p>

                <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                    @lang('admin::app.catalog.products.edit.types.booking.default.description-info')
                 </p>
            </div>

            <!-- Add Slot Button -->
            <div
                class="flex items-center gap-x-1"
                v-if="default_booking.booking_type != 'many'"
            >
                <div
                    class="secondary-button"
                    @click="$refs.drawerForm.toggle()"
                >
                    @lang('admin::app.catalog.products.edit.types.booking.default.slot-add')
                </div>
            </div>
        </div>

        <!-- Table Information -->
        <div class="overflow-x-auto py-2.5">
            <template v-if="default_booking.booking_type == 'one'">
                <template v-if="slots.one?.length">
                    <div class="flex flex-wrap gap-x-2.5">
                        <div
                            class="flex min-h-[38px] flex-wrap items-center gap-1 dark:border-gray-800"
                            v-for="(slot, index) in slots.one"
                        >
                            <!-- Hidden Inputs -->
                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][id]'"
                                :value="slot.id"
                            />

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from_day]'"
                                :value="slot.from_day"
                            />

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from]'"
                                :value="slot.from"
                            />

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to_day]'"
                                :value="slot.to_day"
                            />

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to]'"
                                :value="slot.to"
                            />

                            <!-- Panel details -->
                            <p class="flex items-center px-2 py-1 font-semibold text-white bg-gray-600 rounded">
                                @{{ convertIndexToDay(slot.from_day) }} @{{ slot.from }} - @{{ convertIndexToDay(slot.to_day) }} @{{ slot.to }}

                                <span
                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                    @click="removeIndex(index)"
                                >
                                </span>
                            </p>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <!-- For Empty Illustration -->
                    <v-empty-info ::type="bookingType"></v-empty-info>
                </template>
            </template>

             <!-- For Not Same Slot All Days -->
             <template v-else>
                <div
                    class="grid grid-cols-[0.5fr_2fr] items-center gap-2.5 border-b border-slate-300 py-2 last:border-b-0 dark:border-gray-800"
                    v-for="(day, dayIndex) in week_days"
                >
                    <div class="flex gap-2">
                        <p
                            class="font-medium dark:text-gray-300"
                            v-text="day"
                        >
                        </p>

                        <template v-for="(slot, slotIndex) in slots['many'][dayIndex]">
                            <p
                                :class="parseInt(slot.status) ? 'label-active' : 'label-canceled'"
                                v-text="parseInt(slot.status) 
                                    ? '@lang('admin::app.catalog.products.edit.types.booking.default.open')'
                                    : '@lang('admin::app.catalog.products.edit.types.booking.default.close')'"
                            >
                            </p>
                        </template>
                    </div>

                    <div class="flex items-center justify-between grid-cols-2">
                        <div class="flex min-h-[38px] flex-wrap items-center gap-1 dark:border-gray-800">
                            <template v-if="slots['many'][dayIndex]?.length">
                                <template v-for="(slot, slotIndex) in slots['many'][dayIndex]">
                                    <!-- Hidden Inputs -->
                                    <input
                                        type="hidden"
                                        :name="'booking[slots][' + dayIndex + '][' + slotIndex + '][id]'"
                                        :value="slot.id"
                                    />

                                    <input
                                        type="hidden"
                                        :name="'booking[slots][' + dayIndex + '][' + slotIndex + '][from]'"
                                        :value="slot.from"
                                    />

                                    <input
                                        type="hidden"
                                        :name="'booking[slots][' + dayIndex + '][' + slotIndex + '][to]'"
                                        :value="slot.to"
                                    />

                                    <input
                                        type="hidden"
                                        :name="'booking[slots][' + dayIndex + '][' + slotIndex + '][status]'"
                                        :value="slot.status"
                                    />

                                    <p class="flex items-center px-2 py-1 font-semibold text-white bg-gray-600 rounded">
                                        @{{ slot.from }} - @{{ slot.to }}
        
                                        <span
                                            class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                            @click="removeIndex(dayIndex,slotIndex)"
                                        >
                                        </span>
                                    </p>
                                </template>
                            </template>

                            <template v-else>
                                <p class="text-gray-500">
                                    @lang('admin::app.catalog.products.edit.types.booking.default.unavailable')
                                </p>
                            </template>
                        </div>

                        <p
                            class="text-right text-blue-600 transition-all cursor-pointer place-content-start hover:underline"
                            v-if="! slots['many'][dayIndex]?.length"
                            @click="currentIndex=dayIndex;toggle()"
                        >
                            @lang('admin::app.catalog.products.edit.types.booking.default.add')
                        </p>

                        <p
                            class="text-right text-red-600 transition-all cursor-pointer place-content-start hover:underline"
                            v-else
                            @click="currentIndex=dayIndex;toggle(dayIndex)"
                        >
                            @lang('admin::app.catalog.products.edit.types.booking.default.edit')
                        </p>    
                    </div>
                </div>
            </template>
        </div>

        <!-- Drawer Component -->
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="modelForm"
        >
            <form
                @submit.prevent="handleSubmit($event, store)"
                enctype="multipart/form-data"
            >
                <x-admin::drawer ref="drawerForm">
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-text="slots['many'][currentIndex]?.length 
                                    ? '@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.edit-title')'
                                    : '@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.add-title')'"
                            >
                            </p>

                            <div class="ltr:mr-11 rtl:ml-11">
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.save')
                                </button>
                            </div>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                       <!-- Booking Type One -->
                        <template v-if="default_booking.booking_type == 'one'">
                            <div class="mb-2.5 grid grid-cols-2 gap-4 border-b dark:border-gray-800">
                                <!-- From Day -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.from-day')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="booking_type"
                                        value="one"
                                    />

                                    <!-- Hidden Id Input -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                    />

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="from_day"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.from-day')"
                                    >
                                        <option
                                            value=""
                                            selected
                                        >
                                            @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.select')
                                        </option>

                                        <option
                                            v-for="(day, index) in week_days"
                                            :value="index"
                                            v-text="'@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.week')'.replace(':day', day)"
                                        >
                                        </option>
                                    </x-admin::form.control-group.control>
                    
                                    <x-admin::form.control-group.error control-name="from_day" />
                                </x-admin::form.control-group>
                    
                                <!-- From -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.from-time')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="time"
                                        name="from"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.from-time')"
                                        :placeholder="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.from-time')"
                                    />
                    
                                    <x-admin::form.control-group.error control-name="from" />
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- TO Day -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.to-day')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="to_day"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.to-day')"
                                    >
                                        <option
                                            value=""
                                            selected
                                        >
                                            @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.select')
                                        </option>

                                        <option
                                            v-for="(day, index) in week_days"
                                            :value="index"
                                            v-text="'@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.week')'.replace(':day', day)"
                                        >
                                        </option>
                                    </x-admin::form.control-group.control>
                    
                                    <x-admin::form.control-group.error control-name="to_day" />
                                </x-admin::form.control-group>
                    
                                <!-- To Time -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.to-time')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="time"
                                        name="to"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.to-time')"
                                        :placeholder="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.to-time')"
                                    />
                    
                                    <x-admin::form.control-group.error control-name="to" />
                                </x-admin::form.control-group>
                            </div>
                        </template>

                        <!-- Booking Type Many -->
                        <template v-if="default_booking.booking_type == 'many'">
                            <div class="grid grid-cols-3 gap-2.5 pb-3">
                                <!-- Hidden ID Field -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                />

                                <!-- Slots From -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="hidden">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.from')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="time"
                                        name="from"
                                        ::rules="selectedStatus[currentIndex] ? 'required' : ''"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.from')"
                                        :placeholder="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.from')"
                                    />

                                    <x-admin::form.control-group.error control-name="from" />
                                </x-admin::form.control-group>

                                <!-- Slots To -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="hidden">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.to')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="time"
                                        name="to"
                                        ::rules="selectedStatus[currentIndex] ? 'required' : ''"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.to')"
                                        :placeholder="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.to')"
                                    />

                                    <x-admin::form.control-group.error control-name="to" />
                                </x-admin::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="hidden">
                                        @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="status"
                                        v-model="selectedStatus[currentIndex]"
                                        ::value="selectedStatus[currentIndex]"
                                        :label="trans('admin::app.catalog.products.edit.types.booking.default.modal.slot.status')"
                                    >
                                        <option value="1">
                                            @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.open')
                                        </option>

                                        <option value="0">
                                            @lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.close')
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="status" />
                                </x-admin::form.control-group>
                            </div>
                        </template>
                    </x-slot:content>
                </x-admin::drawer>
            </form>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-default-booking', {
            template: '#v-default-booking-template',

            data() {
                return {
                    default_booking: @json($bookingProduct && $bookingProduct?->default_slot) ? @json($bookingProduct?->default_slot) : {
                        booking_type: 'one',

                        duration: 45,

                        break_time: 15,

                        slots: []
                    },

                    optionRowCount: 0,

                    currentIndex: '',

                    slots: {
                        one: [],

                        many: [[], [], [], [], [], [], [], []],
                    },

                    week_days: [
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.sunday')",
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.monday')",
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.tuesday')",
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.wednesday')",
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.thursday')",
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.friday')",
                        "@lang('admin::app.catalog.products.edit.types.booking.default.modal.slot.saturday')",
                    ],

                    selectedStatus : [],
                }
            },

            created() {
                if (this.default_booking.slots) {
                    const lastIndex = Object.keys(this.default_booking.slots).pop();
                    this.optionRowCount = lastIndex ? this.default_booking.id : 0;
                }

                if (this.default_booking.booking_type === 'one') {
                    this.slots['one'] = this.default_booking.slots ?? this.slots['one'];
                } else {
                    this.slots['many'] = this.slots['many'].map((_, index) => {
                        return this.default_booking.slots[index] ?? [];
                    });
                }
            },

            methods: {
                store(params) {
                    if (params.booking_type === 'one') {
                        if (! params.id) {
                            params.id = this.optionRowCount++;
                        }

                        if (
                            params.from_day > params.to_day || 
                            (params.from_day === params.to_day && params.from >= params.to)
                        ) {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: "@lang('admin::app.catalog.products.edit.types.booking.validations.time-validation')"
                            });
                            
                            return;
                        }
                        
                        const isOverlapping = this.slots.one.some(item => {
                            const toMinutes = (day, time) => {
                                const [h, m] = time.split(':').map(Number);
                                
                                return day * 1440 + h * 60 + m;
                            };

                            const itemStart = toMinutes(+item.from_day, item.from);

                            const itemEnd = toMinutes(+item.to_day, item.to);
                            
                            const paramsStart = toMinutes(+params.from_day, params.from);
                            
                            const paramsEnd = toMinutes(+params.to_day, params.to);

                            return paramsStart < itemEnd && paramsEnd > itemStart;
                        });

                        if (! isOverlapping) {
                            this.slots.one.push(params);
                        } else {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: "@lang('admin::app.catalog.products.edit.types.booking.validations.overlap-validation')",
                            });
                            
                            return;
                        }
                    } else {
                        params.id = this.currentIndex;

                        if (params.from && params.to) {
                            const currentSlot = this.slots['many'][this.currentIndex];

                            if (params.from >= params.to) {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: "@lang('admin::app.catalog.products.edit.types.booking.validations.time-validation')"
                                });

                                return;
                            }
                            
                            if (! currentSlot.length) {
                                currentSlot.push(params);
                            } else {
                                currentSlot.splice(0, 1, params);
                            }

                            this.selectedStatus[this.currentIndex] = params.status;
                        }
                    }

                    this.$refs.drawerForm.toggle();
                },

                convertIndexToDay(day) {
                    return this.week_days[day]?.slice(0, 3);
                },

                removeIndex(dayIndex, timeIndex) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            if (this.default_booking.booking_type == 'one') {
                                this.slots.one.splice(dayIndex, 1);
                            } else {
                                this.slots.many[dayIndex].splice(timeIndex, 1);

                                this.selectedStatus[dayIndex] = '';
                            }
                        },
                    });
                },

                toggle(element) {
                    if (element != undefined) {
                        this.$refs.modelForm.setValues(this.slots['many'][element][0]);

                        this.selectedStatus[this.currentIndex] = this.slots['many'][element][0].status;
                    } else {
                        this.selectedStatus[this.currentIndex] = 0;
                    }

                    this.$refs.drawerForm.toggle();
                },

                slotType() {
                    if (this.default_booking.booking_type == 'one') {
                        this.slots['one'] = [];
                    } else {
                        this.slots['many'] = [[], [], [], [], [], [], []];
                    }

                    this.optionRowCount = 0;
                }
            }
        });
    </script>
@endpushOnce