{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.table.before', ['product' => $product]) !!}
<div>
    <v-default-booking></v-default-booking>
</div>
{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.table.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-default-booking-template">
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
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.booking_type')"
                        v-model="default_booking.booking_type"
                    >
                        @foreach (['many', 'one'] as $item)
                            <option value="{{ $item }}">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.type.' . $item)
                            </option>
                        @endforeach
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[available_every_week]"
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
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slot-duration')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="booking[break_time]"
                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.slot-duration')"
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
            </div>
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
                        "{{ __('bookingproduct::app.admin.catalog.products.sunday') }}",
                        "{{ __('bookingproduct::app.admin.catalog.products.monday') }}",
                        "{{ __('bookingproduct::app.admin.catalog.products.tuesday') }}",
                        "{{ __('bookingproduct::app.admin.catalog.products.wednesday') }}",
                        "{{ __('bookingproduct::app.admin.catalog.products.thursday') }}",
                        "{{ __('bookingproduct::app.admin.catalog.products.friday') }}",
                        "{{ __('bookingproduct::app.admin.catalog.products.saturday') }}"
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
    </script>
@endpushOnce