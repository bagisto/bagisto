{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-appointment-booking :bookingProduct = "$bookingProduct ?? []"></v-appointment-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}


@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-appointment-booking-template"
    >
        <!-- Slot Duration -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.booking.appointment.slot-duration')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[duration]"
                required="required|min_value:1"
                v-model="appointment_booking.duration"
                :label="trans('booking::app.admin.catalog.products.edit.booking.slot-duration')"
                :placeholder="trans('booking::app.admin.catalog.products.edit.booking.appointment.slot-duration')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error 
                control-name="booking[duration]"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <!-- Break Time -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.booking.appointment.break-duration')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[break_time]"
                required="required|min_value:1"
                v-model="appointment_booking.break_time"
                :label="trans('booking::app.admin.catalog.products.edit.booking.appointment.break-duration')"
                :placeholder="trans('booking::app.admin.catalog.products.edit.booking.appointment.break-duration')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error 
                control-name="booking[break_time]"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <!-- Same slot for all days -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.booking.appointment.same-slot-for-all-days.title')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="booking[same_slot_all_days]"
                rules="required"
                v-model="appointment_booking.same_slot_all_days"
                :label="trans('booking::app.admin.catalog.products.edit.booking.appointment.same-slot-for-all-days.title')"
                :placeholder="trans('booking::app.admin.catalog.products.edit.booking.appointment.same-slot-for-all-days.title')"
            >
                <option value="1">
                    @lang('booking::app.admin.catalog.products.edit.booking.appointment.same-slot-for-all-days.yes')
                </option>

                <option value="0">
                    @lang('booking::app.admin.catalog.products.edit.booking.appointment.same-slot-for-all-days.no')
                </option>
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error 
                control-name="booking[same_slot_all_days]"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <!-- Slots Vue Component -->
        <v-slots
            :booking-product="appointment_booking"
            :booking-type="'appointment_slot'"
            :same-slot-all-days="appointment_booking.same_slot_all_days"
        >
        </v-slots>
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
                }
            },
        });
    </script>
@endpushOnce