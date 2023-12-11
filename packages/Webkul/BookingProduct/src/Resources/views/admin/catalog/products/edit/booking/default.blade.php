{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-default-booking></v-default-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-default-booking-template"
    >
        <x-admin::form
            enctype="multipart/form-data"
            method="PUT"
        >
            <div>
                <!-- Type -->
                <x-admin::form.control-group class="w-full mb-[10px]">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.type.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[booking_type]"
                        rules="required"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.type.title')"
                        v-model="default_booking.booking_type"
                    >
                        @foreach (['many', 'one'] as $item)
                            <option value="{{ $item }}">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.default.' . $item)
                            </option>
                        @endforeach
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[booking_type]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <div v-if="default_booking.booking_type == 'many'">
                    <!-- Slot Duration -->
                    <x-admin::form.control-group class="w-full mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slot-duration')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="booking[duration]"
                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.slot-duration')"
                            required="required|min_value:1"
                            v-model="default_booking.duration"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error 
                            control-name="booking[duration]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Break Time -->
                    <x-admin::form.control-group class="w-full mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.break-duration')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="booking[break_time]"
                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.break-duration')"
                            required="required|min_value:1"
                            v-model="default_booking.break_time"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error 
                            control-name="booking[break_time]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>

                <!-- Slots Component -->
                <div class="flex gap-[20px] justify-between p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                        </p>
                    </div>

                    <!-- Add Slot Button -->
                    <div class="flex gap-x-[4px] items-center">
                        <div
                            class="secondary-button"
                            @click="addSlot();$refs.addOptionsRow.toggle()"
                        >
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                        </div>
                    </div>
                </div>
            </div>
        </x-admin::form>

        <x-admin::modal ref="addOptionsRow">
            <x-slot:header>
                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
                </p>
            </x-slot:header>

            <x-slot:content>
                <v-default-slot-item
                    v-if="default_booking.booking_type == 'one'"
                    v-for="(slot, index) in slots.one"
                    :key="index"
                    :days="days"
                    :control-name="'booking[slots][' + index + ']'"
                    @onRemoveSlot="removeSlot($event)"
                >
                </v-default-slot-item>

                <div v-if="default_booking.booking_type == 'many'">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.day')
                                </th>

                                <th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                </th>

                                <th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                </th>

                                <th>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- <tr v-for="(day, index) in days">
                                <td v-text="day"></td>

                                <td>
                                    <x-booking::form.control-group class="w-full mb-[10px]">
                                        <x-booking::form.control-group.label class="required">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </x-booking::form.control-group.label>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            :name="'booking[slots][' + index + '][from]'"
                                            rules="parseInt(slots.many[index].status) ? 'required': ''"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                            v-model="slots.many[index].from"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            :control-name="'booking[slots][' + index + '][from]'"
                                        >
                                        </x-booking::form.control-group.error>
                                    </x-booking::form.control-group>
                                </td>

                                <td>
                                    <x-booking::form.control-group class="w-full mb-[10px]">
                                        <x-booking::form.control-group.label class="required">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                        </x-booking::form.control-group.label>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            :name="'booking[slots][' + index + '][to]'"
                                            rules="parseInt(slots.many[index].status) ? {required: true, time_min: slots.many[index].from } : ''"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                            v-model="slots.many[index].to"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            :control-name="'booking[slots][' + index + '][to]'"
                                        >
                                        </x-booking::form.control-group.error>
                                    </x-booking::form.control-group>
                                </td>

                                <td>
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            :name="'booking[slots][' + index + '][status]'"
                                            rules="required"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')"
                                            v-model="slots.many[index].status"
                                        >
                                            <option value="1">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                                            </option>

                                            <option value="1">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                                            </option>
                                        <x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error 
                                            :control-name="'booking[slots][' + index + '][status]'"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
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
    </script>

    <script type="text/x-template" id="v-default-slot-item-template">
        <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
            <!-- From Day -->
            <x-admin::form.control-group class="w-full mb-[10px]">
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from-day')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    ::name="controlName + '[from_day]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-all-days.title')"
                >
                    <option
                        v-for="(day, index) in days"
                        :value="index"
                        v-text="day"
                    >
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    ::control-name="controlName + '[from_day]'"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>

            <!-- From -->
            <x-booking::form.control-group class="w-full mb-[10px]">
                <x-booking::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="controlName + '[from]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="controlName + '[from]'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>
        </div>

        <div class="flex gap-[16px] px-[16px] py-[10px]">
            <!-- TO Day -->
            <x-admin::form.control-group class="w-full mb-[10px]">
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    ::name="controlName + '[to_day]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                >
                    <option
                        v-for="(day, index) in days"
                        :value="index"
                        v-text="day"
                    >
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    ::control-name="controlName + '[from]'"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>

            <!-- TO Time -->
            <x-booking::form.control-group class="w-full mb-[10px]">
                <x-booking::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="controlName + '[to]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="controlName + '[to]'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>
        </div>
    </script>

    <script type="module">
        app.component('v-default-booking', {
            template: '#v-default-booking-template',

            props: ['bookingProduct'],

            data() {
                return {
                    default_booking: this.bookingProduct && this.bookingProduct.default_slot ? this.bookingProduct.default_slot : {
                        booking_type: 'one',

                        duration: 45,

                        break_time: 15,

                        slots: []
                    },

                    slots: {
                        'one': [],

                        'many': [
                            {'from': '', 'to': '', 'status': 0},
                            {'from': '', 'to': '', 'status': 1},
                            {'from': '', 'to': '', 'status': 1},
                            {'from': '', 'to': '', 'status': 1},
                            {'from': '', 'to': '', 'status': 1},
                            {'from': '', 'to': '', 'status': 1},
                            {'from': '', 'to': '', 'status': 1}
                        ]
                    },

                    days: [
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.sunday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.monday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.tuesday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.wednesday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.thursday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.friday')",
                        "@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.saturday')"
                    ]
                }
            },

            created() {
                if (this.default_booking.booking_type == 'one') {
                    this.slots['one'] = this.default_booking.slots ? this.default_booking.slots : [];
                } else {
                    if (this.default_booking.slots) {
                        this.slots['many'] = this.default_booking.slots;
                    }
                }
            },

            methods: {
                addSlot() {
                    this.slots.one.push({ 'from_day': 0, 'from': '', 'to_day': 0, 'to': '' });
                },

                removeSlot(slot) {
                    let index = this.slots.one.indexOf(slot)

                    this.slots.one.splice(index, 1)
                },
            }
        });

        app.component('v-default-slot-item', {
            template: '#v-default-slot-item-template',

            props: ['controlName', 'days'],

            data() {
                return {
                    controlName: this.controlName,
                }
            },

            methods: {
                removeSlot() {
                    this.$emit('onRemoveSlot', this.slotItem)
                },
            }
        });
    </script>
@endpushOnce