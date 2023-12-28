{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-appointment-booking :bookingProduct = "$bookingProduct ?? []"></v-appointment-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}


@push('scripts')
    <script
        type="text/x-template"
        id="v-appointment-booking-template"
    >
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
        <v-slots
            :booking-product="appointment_booking"
            booking-type="appointment_slot"
            :same-slot-all-days="appointment_booking.same_slot_all_days"
        >
        </v-slots>

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
        {{-- <x-admin::form
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
                                    <x-booking::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}][0]id"
                                    >
                                    </x-booking::form.control-group.control>

                                    <!-- Hidden Days Field -->
                                    <x-booking::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}][0]day"
                                        value="{{ $day }}"
                                    >
                                    </x-booking::form.control-group.control>

                                    <!-- Slots From -->
                                    <x-booking::form.control-group class="w-full mb-2.5">
                                        <x-booking::form.control-group.label class="hidden">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </x-booking::form.control-group.label>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            name="[{{ $key }}][0]from"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}][0]from"
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
                                            name="[{{ $key }}][0]to"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}][0]to"
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
        </x-admin::form> --}}

        <!-- Model For Edit Many type same slots for all booking -->
        {{-- <x-admin::form
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
        </x-admin::form> --}}
    </script>

    <script type="module">
        app.component('v-appointment-booking', {
            template: '#v-appointment-booking-template',

            props: ['bookingProduct'],

            data() {
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

                    formData: [],
                }
            },
        });
    </script>
@endpush