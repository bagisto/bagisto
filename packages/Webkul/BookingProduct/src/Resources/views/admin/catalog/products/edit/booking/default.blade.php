{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-default-booking></v-default-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-default-booking-template"
    >
        <form>
            <div>
                <!-- Type -->
                <x-admin::form.control-group class="w-full mb-2.5">
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
                    <x-admin::form.control-group class="w-full mb-2.5">
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
                    <x-admin::form.control-group class="w-full mb-2.5">
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
            </div>
        </form>

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
                        <div v-if="default_booking.booking_type == 'one'">
                            <div class="flex gap-4 px-4 py-2.5 border-b dark:border-gray-800">
                                <!-- From Day -->
                                <x-admin::form.control-group class="w-full mb-2.5">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from-day')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="from_day"
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
                                        control-name="from_day"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                    
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
                            </div>
                    
                            <div class="flex gap-4 px-4 py-2.5">
                                <!-- TO Day -->
                                <x-admin::form.control-group class="w-full mb-2.5">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="to_day"
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
                                        control-name="to_day"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                    
                                <!-- TO Time -->
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

                        <div v-if="default_booking.booking_type == 'many'">
                            <div class="grid grid-cols-4 gap-2.5 pb-5">
                                @foreach (['day', 'from', 'to', 'status'] as $item)
                                    <span class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                    </span>
                                @endforeach

                            </div>

                            <div
                                class="grid grid-cols-4 gap-2.5"
                                v-for="(day, index) in days"
                            >
                                <div class="text-black dark:text-white" v-text="day"></div>

                                <x-booking::form.control-group class="w-full mb-2.5">
                                    <x-booking::form.control-group.label class="hidden">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                    </x-booking::form.control-group.label>

                                    <x-booking::form.control-group.control
                                        type="time"
                                        ::name="'booking[slots][' + index + '][from]'"
                                        {{-- rules="slots.many[index].status ? 'required': ''" --}}
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                        v-model="slots.many[index].from"
                                    >
                                    </x-booking::form.control-group.control>

                                    <x-booking::form.control-group.error 
                                        ::control-name="'booking[slots][' + index + '][from]'"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::form.control-group>

                                <x-booking::form.control-group class="w-full mb-2.5">
                                    <x-booking::form.control-group.label class="hidden">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                    </x-booking::form.control-group.label>

                                    <x-booking::form.control-group.control
                                        type="time"
                                        ::name="'booking[slots][' + index + '][to]'"
                                        {{-- rules="{ slots.many[index].status ? {required: true, time_min: slots.many[index].from } : '' }" --}}
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                        v-model="slots.many[index].to"
                                    >
                                    </x-booking::form.control-group.control>

                                    <x-booking::form.control-group.error 
                                        ::control-name="'booking[slots][' + index + '][to]'"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group class="w-full mb-2.5">
                                    <x-admin::form.control-group.label class="hidden">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        ::name="'booking[slots][' + index + '][status]'"
                                        rules="required"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')"
                                        v-model="slots.many[index].status"
                                    >

                                        <option value="1">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                                        </option>

                                        <option value="0">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.close')
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error 
                                        ::control-name="'booking[slots][' + index + '][status]'"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
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
                storeSlots(params) {
                    console.log(params);
                },
            }
        });
    </script>
@endpushOnce