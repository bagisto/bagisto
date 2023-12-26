{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-appointment-booking :bookingProduct = "$bookingProduct ?? []"></v-appointment-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}


@push('scripts')
    <script
        type="text/x-template"
        id="v-appointment-booking-template"
    >
        <div>
            <!-- Slot Duration -->
            <x-admin::form.control-group class="w-full mb-2.5">
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slot-duration')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="booking[duration]"
                    required="required|min_value:1"
                    v-model="appointment_booking.duration"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.slot-duration')"
                >
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    control-name="booking[duration]"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>

            <!-- Break Time -->
            <x-admin::form.control-group class="w-full mb-2.5">
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.break-duration')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="booking[break_time]"
                    required="required|min_value:1"
                    v-model="appointment_booking.break_time"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.break-duration')"
                >
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    control-name="booking[break_time]"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>

            <!-- Same slot for all days -->
            <x-admin::form.control-group class="w-full mb-2.5">
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    name="booking[same_slot_all_days]"
                    rules="required"
                    v-model="appointment_booking.same_slot_all_days"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')"
                    @change="slots.one=[];slots.many=[];"
                >
                    <option value="1">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.yes')
                    </option>

                    <option value="0">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.no')
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    control-name="booking[same_slot_all_days]"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>

            <!-- Slots Component -->
            <div class="flex gap-5 justify-between p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                    </p>
                </div>

                <!-- Add Ticket Button -->
                <div
                    class="flex gap-x-1 items-center"
                    v-if="! slots.many?.length"
                >
                    <div
                        class="secondary-button"
                        @click="$refs.addOptionsRow.toggle()"
                    >
                        @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                    </div>
                </div>
            </div>

            <!-- Table Information -->
            <div class="mt-4 overflow-x-auto">
                <template v-if="slots.one?.length || slots.many?.length">
                    <x-admin::table>
                        <x-admin::table.thead class="text-sm font-medium dark:bg-gray-800">
                            <x-admin::table.thead.tr>
                                <!-- From -->
                                <x-admin::table.th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.from')
                                </x-admin::table.th>

                                <!-- To -->
                                <x-admin::table.th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.to')
                                </x-admin::table.th>

                                <!-- Action tables heading -->
                                <x-admin::table.th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.action')
                                </x-admin::table.th>
                            </x-admin::table.thead.tr>
                        </x-admin::table.thead>

                        <x-admin::table.tbody.tr
                            v-if="slots.one?.length"
                            v-for="(slot, index) in slots.one"
                        >
                            <!-- Hidden Field ID -->
                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][id]'"
                                :value="slot.id"
                            />

                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="slot.from"
                                >
                                </p>

                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][from]'"
                                    :value="slot.from"
                                />
                            </x-admin::table.td>

                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="slot.to"
                                >
                                </p>

                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][to]'"
                                    :value="slot.to"
                                />
                            </x-admin::table.td>

                            <!-- Actions button -->
                            <x-admin::table.td class="!px-0">
                                <span
                                    class="icon-edit p-1.5 rounded-1.5 text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="editModal('one', slot)"
                                >
                                </span>

                                <span
                                    class="icon-delete p-1.5 rounded-1.5 text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="removeOption('one', slot)"
                                >
                                </span>
                            </x-admin::table.td>
                        </x-admin::table.tbody.tr>

                        <x-admin::table.tbody.tr
                            v-if="slots.many?.length"
                            v-for="(slot, index) in slots.many[0]"
                        >
                            <!-- Hidden Field Id -->
                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][0][id]'"
                                :value="slot.id"
                            />

                            <!-- From -->
                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="slot.from ?? '00:00'"
                                >
                                </p>

                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][0][from]'"
                                    :value="slot.from ?? '00:00'"
                                />
                            </x-admin::table.td>

                            <!-- To -->
                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="slot.to ?? '00:00'"
                                >
                                </p>

                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][0][to]'"
                                    :value="slot.to ?? '00:00'"
                                />
                            </x-admin::table.td>

                            <!-- Actions button -->
                            <x-admin::table.td class="!px-0">
                                <span
                                    class="icon-edit p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="editModal('many', slot)"
                                >
                                </span>

                                <span
                                    class="icon-delete p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="removeOption('many', slot)"
                                >
                                </span>
                            </x-admin::table.td>
                        </x-admin::table.tbody.tr>
                    </x-admin::table>
                </template>

                <template v-else>
                    <v-empty-info type="appointment"></v-empty-info>
                </template>
            </div>
        </div>

        @php
            $days =  [
                'sunday',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday'
            ];
        @endphp

        <!-- Add Options Model Form -->
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="modelForm"
        >
            <form
                @submit.prevent="handleSubmit($event, storeSlots)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::modal ref="addOptionsRow">
                    <x-slot:header>
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div v-if="parseInt(appointment_booking.same_slot_all_days)">
                            <div class="flex gap-4 mb-2.5 px-4 py-2.5">
                                <!-- ID -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <!-- Hidden Booking Type -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="booking_type"
                                    value="one"
                                >
                                </x-admin::form.control-group.control>

                                <!-- From -->
                                <x-booking::form.control-group class="w-full mb-2.5">
                                    <x-booking::form.control-group.label class="required">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                    </x-booking::form.control-group.label>
                    
                                    <x-booking::form.control-group.control
                                        type="time"
                                        name="from"
                                        rules="required"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                    >
                                    </x-booking::form.control-group.control>
                    
                                    <x-booking::form.control-group.error 
                                        control-name="from"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::form.control-group>
                        
                                <!-- To -->
                                <x-booking::form.control-group class="w-full mb-2.5">
                                    <x-booking::form.control-group.label class="required">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                    </x-booking::form.control-group.label>
                    
                                    <x-booking::form.control-group.control
                                        type="time"
                                        name="to"
                                        rules="required"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                    >
                                    </x-booking::form.control-group.control>
                    
                                    <x-booking::form.control-group.error 
                                        control-name="to"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::form.control-group>
                            </div>
                        </div>

                        <!-- Booking Type Many -->
                        <div v-else>
                            <div class="grid grid-cols-3 gap-2.5 pb-3">
                                @foreach (['day', 'from', 'to'] as $item)
                                    <div class="font-semibold text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                    </div>
                                @endforeach

                            </div>

                            @foreach ($days as $key => $day)
                                <div class="grid grid-cols-3 gap-2.5">
                                    <div class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $day)
                                    </div>

                                    <!-- Hidden ID Field -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}]id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Hidden Days Field -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}]day"
                                        value="{{ $day }}"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Slots From -->

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}]from"
                                        value="00:00"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-booking::form.control-group class="w-full mb-2.5">
                                        <x-booking::form.control-group.label class="hidden">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </x-booking::form.control-group.label>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            name="[{{ $key }}]from"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}]from"
                                        >
                                        </x-booking::form.control-group.error>
                                    </x-booking::form.control-group>

                                    <!-- Slots To -->
                                    <x-booking::form.control-group class="w-full mb-2.5">
                                        <x-booking::form.control-group.label class="hidden">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                        </x-booking::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="[{{ $key }}]to"
                                            value="00:00"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            name="[{{ $key }}]to"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}]to"
                                        >
                                        </x-booking::form.control-group.error>
                                    </x-booking::form.control-group>
                                </div>
                            @endforeach
                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.save')
                        </button>
                    </x-slot:footer>
                </x-admin::modal>
            </form>
        </x-admin::form>

        <!-- Model For Edit Many type same slots for all booking -->
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="ManyOptionsModelForm"
        >
            <form
                @submit.prevent="handleSubmit($event, storeSlots)"
                enctype="multipart/form-data"
            >
                <x-admin::modal ref="addManyOptionsRow">
                    <x-slot:header>
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="flex gap-4 mb-2.5 px-4 py-2.5">
                            <!-- Hidden Id Input -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            >
                            </x-admin::form.control-group.control>

                            <!-- From -->
                            <x-booking::form.control-group class="w-full mb-2.5">
                                <x-booking::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                </x-booking::form.control-group.label>
                
                                <x-booking::form.control-group.control
                                    type="time"
                                    name="from"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                >
                                </x-booking::form.control-group.control>
                
                                <x-booking::form.control-group.error 
                                    control-name="from"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>

                            <!-- To -->
                            <x-booking::form.control-group class="w-full mb-2.5">
                                <x-booking::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                </x-booking::form.control-group.label>
                
                                <x-booking::form.control-group.control
                                    type="time"
                                    name="to"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                >
                                </x-booking::form.control-group.control>
                
                                <x-booking::form.control-group.error 
                                    control-name="to"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>
                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.save')
                        </button>
                    </x-slot:footer>
                </x-admin::modal>
            </form>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-appointment-booking', {
            template: '#v-appointment-booking-template',

            props: ['bookingProduct'],

            data: function() {
                return {
                    appointment_booking: @json($bookingProduct && $bookingProduct?->appointment_slot) ? @json($bookingProduct?->appointment_slot) : {
                        duration: 45,

                        break_time: 15,

                        same_slot_all_days: 1,

                        slots: []
                    },

                    optionRowCount: 0,

                    slots: {
                        one: [],

                        many: [],
                    },
                }
            },

            created() {
                if (this.appointment_booking.same_slot_all_days) {
                    if (this.appointment_booking.slots?.length) {
                        if (this.appointment_booking) {
                            let [lastId] = this.appointment_booking.slots.map(({ id }) => id).slice(-1);
            
                            this.optionRowCount = lastId?.split('_')[1];
                        }
                    }

                    this.slots['one'] = this.appointment_booking.slots ?? [];
                } else {
                    this.slots['many'] = this.appointment_booking.slots ?? [];
                }
            },

            methods: {
                storeSlots(params) {
                    if (params.booking_type === 'one') {
                        if (! params.id) {
                            this.optionRowCount++;
                            params.id = 'option_' + this.optionRowCount;
                        }

                        let foundIndex = this.slots.one.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            this.slots.one[foundIndex] = { 
                                ...this.slots.one[foundIndex].params, 
                                ...params
                            };
                        } else {
                            this.slots.one.push(params);
                        }

                        this.$refs.addOptionsRow.toggle();
                    } else {
                        if (params && params.id) {
                            let item = this.slots.many.flatMap(i => Object.values(i)).find(i => i.id === params.id);

                            if (item) {
                                for (const key in params) {
                                    item[key] = params[key];
                                }

                                this.slots.many = [...this.slots.many.filter(i => i !== item)];
                            }

                            this.$refs.addManyOptionsRow.toggle();
                        } else {
                            for (const key in params) {
                                params[key].id = 'option_' + this.optionRowCount++;
                            }

                            this.slots.many.push([{ ...params }]);

                            this.$refs.addOptionsRow.toggle();
                        }
                    }
                },
    
                editModal(type, values) {
                    if (type === 'one') {    
                        this.oneOptionModal(values);
                    } else {
                        this.manyOptionsModelForm(values);
                    }
                },

                oneOptionModal(params) {
                    this.$refs.modelForm.setValues(params);

                    this.$refs.addOptionsRow.toggle();
                },

                manyOptionsModelForm(params) {
                    this.$refs.ManyOptionsModelForm.setValues(params);

                    this.$refs.addManyOptionsRow.toggle();
                },

                removeOption(type , values) {
                    if (type === 'many') {
                        // this.slots.many = this.slots.many.filter(option => option.id !== values.id);
                    } else {
                        this.slots.one = this.slots.one.filter(option => option.id !== values.id);
                    }
                },
            },
        });
    </script>
@endpush