{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-table-booking></v-table-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-table-booking-template"
    >
        <x-admin::form
            enctype="multipart/form-data"
            method="PUT"
        >
            <div>
                <!-- Charged Per -->
                <x-admin::form.control-group class="w-full mb-2.5">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.charged-per.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[price_type]"
                        rules="required"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.charged-per.title')"
                        v-model="table_booking.price_type"
                    >
                        @foreach (['guest', 'table'] as $item)
                            <option value="{{ $item }}">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.charged-per.' . $item)
                            </option>
                        @endforeach
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[price_type]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Guest Limit -->
                <x-admin::form.control-group
                    class="w-full mb-2.5"
                    v-if="table_booking.price_type == 'table'"
                >
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.guest-limit')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[guest_limit]"
                        rules="required|min_value:1"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.guest-limit')"
                        v-model="table_booking.guest_limit"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[guest_limit]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Guest Capacity -->
                <x-admin::form.control-group class="w-full mb-2.5">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.guest-capacity')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[qty]"
                        value="{{ $bookingProduct ? $bookingProduct->qty : 0 }}"
                        rules="required|min_value:1"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.guest-capacity')"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[qty]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Slot Duration -->
                <x-admin::form.control-group class="w-full mb-2.5">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.slot-duration')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[duration]"
                        v-model="table_booking.duration"
                        rules="required|min_value:1"
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
                        v-model="table_booking.break_time"
                        rules="required|min_value:1"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.break-duration')"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[break_time]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Prevent Scheduling Before -->
                <x-admin::form.control-group class="w-full mb-2.5">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.prevent-scheduling-before')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[prevent_scheduling_before]"
                        v-model="table_booking.prevent_scheduling_before"
                        rules="required|min_value:1"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.prevent-scheduling-before')"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[prevent_scheduling_before]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Same slot all days -->
                <x-admin::form.control-group class="w-full mb-2.5">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[same_slot_all_days]`"
                        v-model="table_booking.same_slot_all_days"
                        rules="required"
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
                        control-name="booking[same_slot_all_days]`"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </div>

            <!-- Slots Component -->
            <div class="flex gap-5 justify-between p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                    </p>
                </div>
    
                <!-- Add SlotS Button -->
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
                        <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
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
                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="slot.params.from"
                                >
                                </p>

                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][from]'"
                                    :value="slot.params.from"
                                />
                            </x-admin::table.td>

                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="slot.params.to"
                                >
                                </p>

                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][to]'"
                                    :value="slot.params.to"
                                />
                            </x-admin::table.td>

                            <!-- Actions button -->
                            <x-admin::table.td class="!px-0">
                                <span
                                    class="icon-edit p-1.5 rounded-1.5 text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="editModal(slot)"
                                >
                                </span>

                                <span
                                    class="icon-delete p-1.5 rounded-1.5 text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="removeOption(slot)"
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
                                :name="'booking[slots][' + index + '][id]'"
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
                                    :name="'booking[slots][' + index + '][from]'"
                                    :value="slot.from"
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
                                    :name="'booking[slots][' + index + '][to]'"
                                    :value="slot.to"
                                />
                            </x-admin::table.td>

                            <!-- Actions button -->
                            <x-admin::table.td class="!px-0">
                                <span
                                    class="icon-edit p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="editModal(slot)"
                                >
                                </span>

                                <span
                                    class="icon-delete p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="removeOption(slot)"
                                >
                                </span>
                            </x-admin::table.td>
                        </x-admin::table.tbody.tr>
                    </x-admin::table>
                </template>

                <template v-else>
                    <v-empty-info type="rental"></v-empty-info>
                </template>
            </div>
        </x-admin::form>

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
                @submit.prevent="handleSubmit($event, store)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::modal ref="addOptionsRow">
                    <x-slot:header>
                        <p class="text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div
                            class="flex gap-4 px-4 py-2.5"
                            v-if="parseInt(table_booking.same_slot_all_days)"
                        >
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
                @submit.prevent="handleSubmit($event, store)"
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

    <script
        type="text/x-template"
        id="v-slot-list-template"
    >
        
    </script>

    <script type="module">
        app.component('v-table-booking', {
            template: '#v-table-booking-template',

            props: ['bookingProduct'],

            data() {
                return {
                    table_booking: this.bookingProduct && this.bookingProduct.table_slot ? this.bookingProduct.table_slot : {
                        price_type: 'guest',

                        guest_limit: 2,

                        duration: 45,

                        break_time: 15,

                        prevent_scheduling_before: 0,

                        same_slot_all_days: 1,

                        slots: []
                    },

                    slots: {
                        one: [],

                        many: [],
                    },

                    optionRowCount: 1,
                }
            },

            methods: {
                store(params) {
                    if (params.booking_type === 'one') {
                        if (params.id) {
                            let foundIndex = this.slots.one.findIndex(item => item.id === params.id);

                            this.slots.one[foundIndex].params = { 
                                ...this.slots.one[foundIndex].params, 
                                ...params
                            };
                        } else {
                            this.slots.one.push({ 
                                id: 'option_' + this.optionRowCount++, 
                                params
                            });
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

                            this.slots.many = [{ ...params }];

                            this.$refs.addOptionsRow.toggle();
                        }
                    }
                },

                editModal(values) {
                    let hasParam = values?.params;

                    if (hasParam) {
                        if (hasParam.booking_type === 'one') {
                            hasParam.id = values.id;
    
                            this.oneOptionModal(hasParam);
                        } else {
                            this.manyOptionsModelForm(values);
                        }
                     } else {
                        if (values.booking_type === 'one') {
                            this.oneOptionModal(values);
                        } else {
                            this.manyOptionsModelForm(values);    
                        }
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

                removeOption(id) {
                    this.slots = this.slots.filter(option => option.id !== id);
                },
            },
        });
    </script>
@endpushOnce