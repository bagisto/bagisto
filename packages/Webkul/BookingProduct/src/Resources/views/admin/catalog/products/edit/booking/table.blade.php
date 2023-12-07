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
                <x-admin::form.control-group class="w-full mb-[10px]">
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
                    class="w-full mb-[10px]"
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
                <x-admin::form.control-group class="w-full mb-[10px]">
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
                <x-admin::form.control-group class="w-full mb-[10px]">
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
                <x-admin::form.control-group class="w-full mb-[10px]">
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
                <x-admin::form.control-group class="w-full mb-[10px]">
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
                <x-admin::form.control-group class="w-full mb-[10px]">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-all-days.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[same_slot_all_days]`"
                        v-model="table_booking.same_slot_all_days"
                        rules="required|min_value:1"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-all-days.title')"
                    >
                        <option value="1">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-all-days.yes')
                        </option>

                        <option value="0">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-all-days.no')
                        </option>
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[same_slot_all_days]`"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </div>

            <div class="section">
                <div class="secton-title">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                </div>

                <div class="section-content">
                    <slot-list
                        booking-type="table_slot"
                        :same-slot-all-days="table_booking.same_slot_all_days">
                    </slot-list>
                </div>
            </div>
        </x-admin::form>
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
                    }
                }
            }
        });
    </script>
@endpushOnce