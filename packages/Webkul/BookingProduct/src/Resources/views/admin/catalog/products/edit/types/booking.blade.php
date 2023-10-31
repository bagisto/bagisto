@php
    $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id);

    $formattedAvailableFrom = $bookingProduct && $bookingProduct->available_from ? $bookingProduct->available_from->format('Y-m-d H:i:s') : '';

    $formattedAvailableTo = $bookingProduct && $bookingProduct->available_to ? $bookingProduct->available_to->format('Y-m-d H:i:s') : '';
@endphp

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.booking.before', ['product' => $product]) !!}

<v-booking-information></v-booking-information>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.booking.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-booking-information-template">
        <x-admin::form
            {{-- :action="route('admin.catalog.attributes.update', $attribute->id)" --}}
            enctype="multipart/form-data"
            method="PUT"
        >
            <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <!-- Panel Header -->
                <div class="flex gap-[20px] justify-between p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('Booking Information')
                        </p>
                    </div>

                    <!-- Add Button -->
                    {{-- <div class="flex gap-x-[4px] items-center">
                        <div
                            class="secondary-button"
                            @click="resetForm(); $refs.updateCreateOptionModal.open()"
                        >
                            @lang('Add Slot')
                        </div>
                    </div> --}}
                </div>

                <!-- Panel Content -->
                <div
                    class="gap-[20px] justify-between mb-[10px] p-[16px]"
                    v-if="is_new"
                >
                    <!-- Booking Type -->
                    <x-admin::form.control-group class="w-full mb-[10px]">
                        <x-booking::form.control-group.label class="required">
                            @lang('Booking Type')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="select"
                            name="booking[type]"
                            rules="required"
                            ::value="booking_type"
                            :label="trans('Booking Type')"
                            v-model="booking_type"
                            @change="bookingSwatch=true"
                        >
                            <option value="default" selected>@lang('Default')</option>
                            <option value="appointment">@lang('Appointment Booking')</option>
                            <option value="event">@lang('Event Booking')</option>
                            <option value="rental">@lang('Rental Booking')</option>
                            <option value="table">@lang('Table Booking')</option>
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[type]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Location -->
                    <x-admin::form.control-group class="w-full mb-[10px]">
                        <x-booking::form.control-group.label>
                            @lang('Location')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="text"
                            name="loaction"
                            :label="trans('loaction')"
                            :placeholder="trans('loaction')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="loaction"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Quantity -->
                    <x-admin::form.control-group
                        class="w-full mb-[10px]"
                        v-if="(
                            booking_type == 'default'
                            || booking_type == 'appointment'
                            || booking_type == 'rental'
                        )"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Qty')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="text"
                            name="qty"
                            rules="required"
                            :label="trans('qty')"
                            :placeholder="trans('qty')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="qty"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Available Every Week  -->
                    <x-admin::form.control-group
                        v-if="bookingSwatch && (
                            booking_type == 'appointment' 
                            || booking_type == 'rental'
                            || booking_type == 'table'
                        )"
                        class="w-full mb-[10px]"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Available Every Week')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="datetime"
                            name="booking[available_every_week]"
                            rules="required"
                            :label="trans('Available Every Week')"
                            :placeholder="trans('Available Every Week')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[available_every_week]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Available From  -->
                    <x-admin::form.control-group class="w-full mb-[10px]">
                        <x-booking::form.control-group.label class="required">
                            @lang('Available From')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="datetime"
                            name="booking[available_from]"
                            rules="required"
                            :label="trans('Available From')"
                            :placeholder="trans('Available From')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[available_from]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Available To -->
                    <x-admin::form.control-group class="w-full mb-[10px]">
                        <x-booking::form.control-group.label class="required">
                            @lang('Available To')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="datetime"
                            name="booking[available_to]"
                            rules="required"
                            :label="trans('Available To')"
                            :placeholder="trans('Available To')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[available_to]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Renting Type -->
                    <x-admin::form.control-group
                        v-if="bookingSwatch && booking_type == 'rental'"
                        class="w-full mb-[10px]"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Renting Type')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="select"
                            name="booking[renting_type]"
                            rules="required"
                            :value="'daily'"
                            :label="trans('Renting Type')"
                            :placeholder="trans('Renting Type')"
                        >
                            <option value="daily" selected>@lang('Daily Basis')</option>
                            <option value="hourly">@lang('Hourly Basis')</option>
                            <option value="daily_hourly">@lang('Both (Daily and Hourly Basis)')</option>
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[renting_type]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Daily Price -->
                    <x-admin::form.control-group
                        v-if="bookingSwatch && booking_type == 'rental'"
                        class="w-full mb-[10px]"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Daily Price')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="text"
                            name="booking[daily_price]"
                            rules="required"
                            :label="trans('Daily Price')"
                            :placeholder="trans('Daily Price')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[daily_price]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Type -->
                    <x-admin::form.control-group
                        class="w-full mb-[10px]"
                        v-if="booking_type == 'default'"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Type')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="select"
                            name="booking[booking_type]"
                            rules="required"
                            :value="'one'"
                            :label="trans('Type')"
                            :placeholder="trans('Type')"
                            v-model="bookingSubType"
                            @change="bookingSwatchType=true"
                        >
                            <option value="many" selected>{{ __('Many Bookings for one day') }}</option>
                            <option value="one">{{ __('One Booking for many days') }}</option>
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[booking_type]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                     <!-- Slot Duration -->
                    <x-admin::form.control-group
                        v-if="bookingSwatch && booking_type == 'appointment'"
                        class="w-full mb-[10px]"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Slot Duration')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="text"
                            name="booking[duration]"
                            rules="required"
                            :label="trans('Slot Duration')"
                            :placeholder="trans('Slot Duration')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[duration]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                    <!-- Break Duration -->
                    <x-admin::form.control-group
                        v-if="bookingSwatchType && (
                            bookingSubType == 'many'
                        )"
                        class="w-full mb-[10px]"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Break Duration')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="text"
                            name="booking[break_time]"
                            rules="required"
                            :label="trans('Break Duration')"
                            :placeholder="trans('Break Duration')"
                        >
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[break_time]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>

                     <!-- Same Slot For All days -->
                    <x-admin::form.control-group
                        v-if="bookingSwatch && (
                            booking_type == 'appointment'
                            || booking_type == 'table'
                        )"
                        class="w-full mb-[10px]"
                    >
                        <x-booking::form.control-group.label class="required">
                            @lang('Same Slot For All days')
                        </x-booking::form.control-group.label>

                        <x-booking::form.control-group.control
                            type="select"
                            name="booking[same_slot_all_days]"
                            rules="required"
                            :value="'1'"
                            :label="trans('Same Slot For All days')"
                            :placeholder="trans('Same Slot For All days')"
                        >
                            <option value="1" selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </x-booking::form.control-group.control>

                        <x-booking::form.control-group.error 
                            control-name="booking[booking_type]"
                        >
                        </x-booking::form.control-group.error>
                    </x-booking::form.control-group>
                </div>
                    
                <!-- For Empty Option -->
                <div
                    class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                    v-else
                >
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-options.svg') }}"
                        class="w-[80px] h-[80px] border border-dashed dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                    />

                    <!-- Add Variants Information -->
                    <div class="flex flex-col gap-[5px] items-center">
                        <p class="text-[16px] text-gray-400 font-semibold">
                            @lang('Add Slot')
                        </p>

                        <p class="text-gray-400 text-red">
                            @lang('Add booking slot on the go.')
                        </p>
                    </div>

                    <div
                        class="secondary-button text-[14px]"
                        {{-- @click="resetForm(); $refs.updateCreateOptionModal.open()" --}}
                    >
                        @lang('Add Slot')
                    </div> 
                </div>
            </div>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-booking-information', {
            template: '#v-booking-information-template',

            // props: ['errors'],

            data() {
                return {
                    is_new: @json($bookingProduct) ? false : true,

                    booking: @json($bookingProduct) ?? {
                        type: 'default',
                        location: '',
                        qty: 0,
                        available_every_week: '',
                        available_from: '',
                        available_to: '',
                    },

                    booking_type: 'default',

                    bookingSubType: '',

                    bookingSwatch: false,

                    bookingSwatchType: false,
                };
            },

            created() {
                this.booking.available_from = @json($formattedAvailableFrom);
                this.booking.available_to = @json($formattedAvailableTo);
            },
        });
    </script>

    {{-- @include ('bookingproduct::admin.catalog.products.accordians.booking.slots') --}}
@endPushOnce