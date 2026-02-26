{!! view_render_event('bagisto.admin.catalog.product.edit.booking.table.before', ['product' => $product]) !!}

<!-- Vue Component -->
<v-table-booking></v-table-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.booking.table.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-table-booking-template"
    >
        <!-- Charged Per -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.table.charged-per.title')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="booking[price_type]"
                rules="required"
                v-model="table_booking.price_type"
                :label="trans('admin::app.catalog.products.edit.types.booking.charged-per.title')"
                :placeholder="trans('admin::app.catalog.products.edit.types.booking.charged-per.title')"
            >
                @foreach (['guest', 'table'] as $item)
                    <option value="{{ $item }}">
                        @lang('admin::app.catalog.products.edit.types.booking.table.charged-per.' . $item)
                    </option>
                @endforeach
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error control-name="booking[price_type]" />
        </x-admin::form.control-group>

        <!-- Guest Limit -->
        <x-admin::form.control-group
            class="w-full"
            v-if="table_booking.price_type == 'table'"
        >
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.table.guest-limit')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[guest_limit]"
                rules="required|min_value:1"
                v-model="table_booking.guest_limit"
                :label="trans('admin::app.catalog.products.edit.types.booking.table.guest-limit')"
                :placeholder="trans('admin::app.catalog.products.edit.types.booking.table.guest-limit')"
            />

            <x-admin::form.control-group.error  control-name="booking[guest_limit]" />
        </x-admin::form.control-group>

        <!-- Guest Capacity -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.table.guest-capacity')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[qty]"
                value="{{ $bookingProduct ? $bookingProduct->qty : 0 }}"
                rules="required|min_value:1"
                :label="trans('admin::app.catalog.products.edit.types.booking.table.guest-capacity')"
                :placeholder="trans('admin::app.catalog.products.edit.types.booking.table.guest-capacity')"
            />

            <x-admin::form.control-group.error control-name="booking[qty]" />
        </x-admin::form.control-group>

        <!-- Slot Duration -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.table.slot-duration')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[duration]"
                rules="required|min_value:1"
                v-model="table_booking.duration"
                :label="trans('admin::app.catalog.products.edit.types.booking.table.slot-duration')"
                :placeholder="trans('admin::app.catalog.products.edit.types.booking.table.slot-duration')"
            />

            <x-admin::form.control-group.error control-name="booking[duration]" />
        </x-admin::form.control-group>

        <!-- Break Time -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.table.break-duration')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[break_time]"
                rules="required|min_value:1"
                v-model="table_booking.break_time"
                :label="trans('admin::app.catalog.products.edit.types.booking.table.break-duration')"
                :placeholder="trans('admin::app.catalog.products.edit.types.booking.table.break-duration')"
            />

            <x-admin::form.control-group.error control-name="booking[break_time]" />
        </x-admin::form.control-group>

        <!-- Prevent Scheduling Before -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label>
                @lang('admin::app.catalog.products.edit.types.booking.table.prevent-scheduling-before')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[prevent_scheduling_before]"
                v-model="table_booking.prevent_scheduling_before"
                :label="trans('admin::app.catalog.products.edit.types.booking.table.prevent-scheduling-before')"
                :placeholder="trans('admin::app.catalog.products.edit.types.booking.table.prevent-scheduling-before')"
            />

            <x-admin::form.control-group.error control-name="booking[prevent_scheduling_before]" />
        </x-admin::form.control-group>

        <!-- Same slot all days -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.catalog.products.edit.types.booking.table.same-slot-for-all-days.title')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="booking[same_slot_all_days]`"
                rules="required"
                v-model="table_booking.same_slot_all_days"
                :label="trans('admin::app.catalog.products.edit.types.booking.table.same-slot-for-all-days.title')"
            >
                <option value="1">
                    @lang('admin::app.catalog.products.edit.types.booking.table.same-slot-for-all-days.yes')
                </option>

                <option value="0">
                    @lang('admin::app.catalog.products.edit.types.booking.table.same-slot-for-all-days.no')
                </option>
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error control-name="booking[same_slot_all_days]`" />
        </x-admin::form.control-group>

        <!-- Slots Vue Component -->
        <v-slots
            :booking-product="table_booking"
            :booking-type="'table_slot'"
            :same-slot-all-days="table_booking.same_slot_all_days"
        >
        </v-slots>
    </script>

    <script type="module">
        app.component('v-table-booking', {
            template: '#v-table-booking-template',

            props: ['bookingProduct'],

            data() {
                return {
                    table_booking: @json($bookingProduct && $bookingProduct?->table_slot) ? @json($bookingProduct?->table_slot) : {
                        price_type: 'guest',

                        guest_limit: 2,

                        duration: 45,

                        break_time: 15,

                        prevent_scheduling_before: 0,

                        same_slot_all_days: 1,

                        slots: []
                    }
                }
            },
        });
    </script>
@endpushOnce