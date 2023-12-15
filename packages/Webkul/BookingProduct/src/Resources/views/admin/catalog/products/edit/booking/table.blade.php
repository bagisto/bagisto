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

                <!-- Add Slot Button -->
                <div class="flex gap-x-1 items-center">
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
                <template v-if="slots?.length">
                    <x-admin::table>
                        <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
                            <x-admin::table.thead.tr>
                                <!-- From -->
                                <x-admin::table.th>
                                    @lang('From')
                                </x-admin::table.th>

                                <!-- To -->
                                <x-admin::table.th>
                                    @lang('To')
                                </x-admin::table.th>

                                <!-- Action tables heading -->
                                <x-admin::table.th>
                                    @lang('Actions')
                                </x-admin::table.th>
                            </x-admin::table.thead.tr>
                        </x-admin::table.thead>

                        <x-admin::table.tbody.tr v-for="element in slots">
                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="element.params.from"
                                >
                                </p>
                            </x-admin::table.td>

                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="element.params.to"
                                >
                                </p>
                            </x-admin::table.td>

                            <!-- Actions button -->
                            <x-admin::table.td class="!px-0">
                                <span
                                    class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="editModal(element)"
                                >
                                </span>

                                <span
                                    class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="removeOption(element.id)"
                                >
                                </span>
                            </x-admin::table.td>
                        </x-admin::table.tbody.tr>
                    </x-admin::table>
                </template>

                <template v-else>
                    <v-empty-info type="table"></v-empty-info>
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
                @submit.prevent="handleSubmit($event, storeSlots)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::modal ref="addOptionsRow">
                    <x-slot:header>
                        <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
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
                            <v-slots
                                booking-type="table_slot"
                                :same-slot-all-days="table_booking.same_slot_all_days"
                            >
                            </v-slots>
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

                    slots: [],
                }
            },

            methods: {
                storeSlots(params) {
                    if (params.id) {
                        let foundIndex = this.slots.findIndex(item => item.id === params.id);

                        this.slots.splice(foundIndex, 1, {
                            ...this.slots[foundIndex],
                            params: {
                                ...this.slots[foundIndex].params,
                                ...params,
                            }
                        }); 
                    } else {
                        this.slots.push({
                            id: 'option_' + this.optionRowCount++,
                            params
                        });
                    }

                    this.$refs.addOptionsRow.toggle();
                },

                editModal(values) {
                    values.params.id = values.id;

                    this.$refs.modelForm.setValues(values.params);

                    this.$refs.addOptionsRow.toggle();
                },

                removeOption(id) {
                    this.slots = this.slots.filter(option => option.id !== id);
                },
            },
        });
    </script>
@endpushOnce