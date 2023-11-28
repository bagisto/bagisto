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
        <x-booking::form
            {{-- :action="route('admin.catalog.attributes.update', $attribute->id)" --}}
            enctype="multipart/form-data"
            method="PUT"
        >
            <div class="grid gap-[8px]">
                <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <!-- Panel Header -->
                    <div class="flex gap-[20px] justify-between p-[16px]">
                        <div class="flex flex-col gap-[8px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.title')
                            </p>
                        </div>
                    </div>

                    <!-- Panel Content -->
                    <div class="gap-[20px] justify-between p-[16px]">
                        <!-- Booking Type -->
                        <x-booking::form.control-group class="w-full mb-[10px]">
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.booking_type')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="select"
                                name="booking[type]"
                                rules="required"
                                ::value="booking_type"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.booking_type')"
                                v-model="booking_type"
                                @change="bookingSwatch=true, slots=[]"
                            >
                                @foreach (['default', 'appointment', 'event', 'rental', 'table'] as $item)
                                    <option value={{ $item }}>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.type.' . $item)
                                    </option>
                                @endforeach
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[type]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Location -->
                        <x-booking::form.control-group class="w-full mb-[10px]">
                            <x-booking::form.control-group.label>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.location')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[location]"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.location')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.location')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[location]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Quantity -->
                        <x-booking::form.control-group
                            class="w-full mb-[10px]"
                            v-if="(
                                booking_type == 'default'
                                || booking_type == 'appointment'
                                || booking_type == 'rental'
                            )"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.qty')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[qty]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.qty')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.qty')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[qty]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Available Every Week  -->
                        <x-booking::form.control-group
                            v-if="bookingSwatch && (
                                booking_type == 'appointment' 
                                || booking_type == 'rental'
                                || booking_type == 'table'
                            )"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.available-every-week')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="datetime"
                                name="booking[available_every_week]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.available-every-week')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.available-every-week')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[available_every_week]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Available From  -->
                        <x-booking::form.control-group class="w-full mb-[10px]">
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.available-from')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="datetime"
                                name="booking[available_from]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.available-from')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.available-from')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[available_from]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Available To -->
                        <x-booking::form.control-group class="w-full mb-[10px]">
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.available-to')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="datetime"
                                name="booking[available_to]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.available-to')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.available-to')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[available_to]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Charged Per -->
                        <x-booking::form.control-group
                            v-if="bookingSwatch && booking_type == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.charged-per')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="select"
                                name="booking[price_type]"
                                rules="required"
                                :value="'guest'"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.charged-per')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.charged-per')"
                                v-model="charged_per"
                                @change="chargedSwatch=true"
                            >
                                <option value="guest" selected>{{ __('Guest') }}</option>
                                <option value="table">{{ __('Table') }}</option>
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[price_type]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Guest Limit Per Table -->
                        <x-booking::form.control-group
                            v-if="chargedSwatch && charged_per == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.guest-limit-per-table')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[guest_limit]"
                                rules="required"
                                :value="2"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.guest-limit-per-table')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.guest-limit-per-table')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[guest_limit]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Guest Capacity -->
                        <x-booking::form.control-group
                            v-if="bookingSwatch && booking_type == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.guest-capacity')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[qty]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.guest-capacity')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.guest-capacity')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[booking_type]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Renting Type -->
                        <x-booking::form.control-group
                            v-if="bookingSwatch && booking_type == 'rental'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="select"
                                name="booking[renting_type]"
                                rules="required"
                                :value="'renting_type'"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')"
                                v-model="renting_type"
                                @change="rentingSwatch=true, slots=[]"
                            >
                                <option value="daily">@lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily')</option>
                                <option value="hourly">@lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly')</option>
                                <option value="daily_hourly">@lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-hourly')</option>
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[renting_type]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Daily Price -->
                        <x-booking::form.control-group
                            v-if="bookingSwatch && (
                                renting_type != 'hourly'
                            )"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[daily_price]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[daily_price]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Hourly Price -->
                        <x-booking::form.control-group
                            v-if="rentingSwatch && renting_type != 'daily'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[hourly_price]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[hourly_price]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Type -->
                        <x-booking::form.control-group
                            class="w-full mb-[10px]"
                            v-if="booking_type == 'default'"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.type.title')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="select"
                                name="booking[booking_type]"
                                rules="required"
                                :value="'one'"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.type.title')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.type.title')"
                                v-model="bookingSubType"
                                @change="bookingSwatchType=true"
                            >
                                @foreach (['many', 'one'] as $item)
                                    <option value="{{ $item }}">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.type.' . $item)
                                    </option>
                                @endforeach
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[booking_type]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Slot Duration -->
                        <x-booking::form.control-group
                            v-if="(
                                bookingSwatchType 
                                && bookingSubType == 'many'
                            ) || (
                                bookingSwatch 
                                && (
                                    booking_type == 'appointment' 
                                    || booking_type == 'table'
                                )
                            )"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.slot-duration')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[duration]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.slot-duration')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.slot-duration')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[duration]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Break Duration -->
                        <x-booking::form.control-group
                            v-if="(bookingSwatchType && bookingSubType == 'many') || 
                                (bookingSwatch && (booking_type == 'appointment' || booking_type == 'table'))"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.break-duration')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[break_time]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.break-duration')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.break-duration')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[break_time]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Prevent Scheduling Before -->
                        <x-booking::form.control-group
                            v-if="bookingSwatch && booking_type == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.prevent-scheduling-before')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[prevent_scheduling_before]"
                                rules="required"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.prevent-scheduling-before')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.prevent-scheduling-before')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[prevent_scheduling_before]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Same Slot For All days -->
                        <x-booking::form.control-group
                            v-if="(bookingSwatch || rentingSwatch) && (
                                booking_type == 'appointment'
                                || booking_type == 'table'
                                || renting_type != 'daily'
                            )"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="select"
                                name="booking[same_slot_all_days]"
                                rules="required"
                                :value="'same_slot'"
                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')"
                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')"
                                v-model="same_slot"
                                @change="sameSlotSwatch=true"
                            >
                                <option value="1">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.yes')
                                </option>

                                <option value="0">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.no')
                                </option>
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[same_slot_all_days]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>
                    </div>
                </div>

                @php
                    $weeks = ['sunday', 'monaday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                @endphp

                <!-- Slots Component -->
                <div
                    class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]"
                    v-if="(
                        booking_type != 'rental' 
                        || renting_type != 'daily'
                    )"
                >
                    <div class="flex gap-[20px] justify-between">
                        <div class="flex flex-col gap-[8px]">
                            <p
                                class="text-[16px] text-gray-800 dark:text-white font-semibold"
                                v-text="booking_type != 'event' 
                                    ? '@lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')' 
                                    : '@lang('booking::app.admin.catalog.products.edit.type.booking.tickets.title')'"
                            >
                            </p>
                        </div>

                        <!-- Add Slot Button -->
                        <div class="flex gap-x-[4px] items-center">
                            <div
                                v-text="booking_type != 'event' 
                                    ? '@lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')' 
                                    : '@lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')'"
                                class="secondary-button"
                                @click="$refs.addOptionsRow.open()"
                            >
                            </div>
                        </div>
                    </div>

                    <!-- For Booking Slots If Data Exist -->
                    <template v-if="this.slots?.length">
                        <!-- Table Information -->
                        <div class="mt-[15px] overflow-x-auto">
                            <x-admin::table>
                                <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
                                    <x-admin::table.thead.tr v-if="booking_type != 'event'">
                                        <x-admin::table.th class="!p-0"></x-admin::table.th>

                                        <!-- From Day -->
                                        <x-admin::table.th v-if="booking_type == 'default'">
                                            @lang('From Day')
                                        </x-admin::table.th>

                                        <!-- From Time -->
                                        <x-admin::table.th>
                                            @lang('From Time')
                                        </x-admin::table.th>

                                        <!-- To Day -->
                                        <x-admin::table.th v-if="booking_type == 'default'">
                                            @lang('To Day')
                                        </x-admin::table.th>

                                        <!-- To Time -->
                                        <x-admin::table.th>
                                            @lang('To Time')
                                        </x-admin::table.th>

                                        <!-- Action tables heading -->
                                        <x-admin::table.th></x-admin::table.th>
                                    </x-admin::table.thead.tr>
                                </x-admin::table.thead>

                                <!-- Draggable Component -->
                                <draggable
                                    tag="tbody"
                                    ghost-class="draggable-ghost"
                                    v-bind="{animation: 200}"
                                    :list="slots"
                                    item-key="id"
                                >
                                    <template #item="{ element, index }">
                                        <x-admin::table.thead.tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-950"
                                            v-if="booking_type != 'event'"
                                        >
                                            <!-- Draggable Icon -->
                                            <x-admin::table.td class="!px-0">
                                                <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

                                                <input
                                                    type="hidden"
                                                    :name="'booking[slots][' + element.id + '][id]'"
                                                    :value="index"
                                                />
                                            </x-admin::table.td>

                                            <!-- From Day-->
                                            <x-admin::table.td v-if="booking_type == 'default'">
                                                <p
                                                    class="dark:text-white"
                                                    v-text="element.from_day"
                                                >
                                                </p>

                                                <input
                                                    type="hidden"
                                                    :name="'booking[slots][' + element.id + ']' + element.from_day"
                                                    :value="element.from_day"
                                                />
                                            </x-admin::table.td>

                                            <!-- From Time-->
                                            <x-admin::table.td>
                                                <p
                                                    class="dark:text-white"
                                                    v-text="element.from_time"
                                                >
                                                </p>

                                                <input
                                                    type="hidden"
                                                    :name="'booking[slots][' + element.id + ']' + element.from_time"
                                                    :value="element.from_time"
                                                />
                                            </x-admin::table.td>

                                            <!-- To Day-->
                                            <x-admin::table.td v-if="booking_type == 'default'">
                                                <p
                                                    class="dark:text-white"
                                                    v-text="element.to_day"
                                                >
                                                </p>

                                                <input
                                                    type="hidden"
                                                    :name="'booking[slots][' + element.id + ']' + element.to_day"
                                                    :value="element.to_day"
                                                />
                                            </x-admin::table.td>

                                            <!-- To Time-->
                                            <x-admin::table.td>
                                                <p
                                                    class="dark:text-white"
                                                    v-text="element.to_time"
                                                >
                                                </p>

                                                <input
                                                    type="hidden"
                                                    :name="'booking[slots][' + element.id + ']' + element.to_time"
                                                    :value="element.to_time"
                                                />
                                            </x-admin::table.td>

                                            <!-- Actions button -->
                                            <x-admin::table.td class="!px-0">
                                                <span
                                                    class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                    @click="removeOption(element.id)"
                                                >
                                                </span>
                                            
                                                <span
                                                    class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                    @click="removeOption(element.id)"
                                                >
                                                </span>
                                            </x-admin::table.td>
                                        </x-admin::table.thead.tr>

                                        <x-admin::table.thead.tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-950"
                                            v-else
                                        >
                                            <!-- Draggable Icon -->
                                            <x-admin::table.td class="!px-0">
                                                <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

                                                <input
                                                    type="hidden"
                                                    :name="'booking[slots][' + element.id + '][id]'"
                                                    :value="index"
                                                />
                                            </x-admin::table.td>
                                        </x-admin::table.thead.tr>
                                    </template>
                                </draggable>
                            </x-admin::table>
                        </div>
                    </template>

                    <!-- For Empty Booking Slots -->
                    <template v-else>
                        <div class="grid gap-[14px] justify-items-center py-[40px] px-[10px]">
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
                            />

                            <!-- Add Slot Information -->
                            <div class="flex flex-col gap-[5px] items-center">
                                <p
                                    class="text-[16px] text-gray-400 font-semibold"
                                    v-text="booking_type != 'event' 
                                        ? '@lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')' 
                                        : '@lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')'"
                                >
                                </p>
                                
                                <p
                                    class="text-gray-400 text-red"
                                    v-text="booking_type != 'event' 
                                        ? '@lang('booking::app.admin.catalog.products.edit.type.booking.slots.add-desc')' 
                                        : '@lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add-desc')'"
                                >
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </x-admin::form>

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
                        <p
                            class="text-[18px] text-gray-800 dark:text-white font-bold"
                            v-text="booking_type != 'event' 
                                ? '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')' 
                                : '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.title')'"
                        >
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div v-if="booking_type != 'event' || booking_type != 'rental'">
                            <div v-if="sameSlotSwatch && same_slot == 1">
                                @php $count = 0; @endphp

                                <!-- Heading -->
                                <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    @foreach (['day', 'from', 'to'] as $item)
                                        <p class="w-full text-[16px] font-medium">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                        </p>
                                    @endforeach
                                </div>

                                <!-- Content -->
                                @foreach ($weeks as $item)
                                    <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                        <!-- Day -->
                                        <div class="w-full">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                        </div>

                                        <!-- From Time -->
                                        <x-admin::form.control-group class="w-full mb-[10px]">
                                            <x-admin::form.control-group.label class="hidden">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="date"
                                                name="booking[slots][{{ $count }}][0][from]"
                                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="booking[slots][{{ $count }}][0][from]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- To Time -->
                                        <x-admin::form.control-group class="w-full mb-[10px]">
                                            <x-admin::form.control-group.label class="hidden">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="date"
                                                name="booking[slots][{{ $count }}][0][to]"
                                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="booking[slots][{{ $count }}][0][to]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- Status -->
                                        <x-admin::form.control-group
                                            class="w-full mb-[10px]"
                                            v-if="bookingSubType == 'many'"
                                        >
                                            <x-admin::form.control-group.label class="hidden">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="from_day"
                                                :value="'1'"
                                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')"
                                            >
                                                <option value="1">
                                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                                                </option>

                                                <option value="0">
                                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.close')
                                                </option>
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="from_day"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::from.control-group>
                                    </div>
                                @endforeach
                            </div>

                            <div v-else-if="bookingSwatchType && bookingSubType == 'many'">
                                @php $count = 0; @endphp

                                <!-- Heading -->
                                <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    @foreach (['day', 'from', 'to', 'status'] as $item)
                                        <p class="w-full text-[16px] font-medium">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                        </p>
                                    @endforeach
                                </div>

                                <!-- Content -->
                                @foreach ($weeks as $item)
                                    <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                        <!-- Day -->
                                        <x-admin::form.control-group.label class="w-full">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                        </x-admin::form.control-group.label>
        
                                        <!-- From -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label class="hidden">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="booking[slots][{{ $count }}][from]"
                                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="booking[slots][{{ $count }}][from]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::from.control-group>
        
                                        <!-- To -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label class="hidden">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="booking[slots][{{ $count }}][to]"
                                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="booking[slots][{{ $count }}][to]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::from.control-group>
        
                                        <!-- Status -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label class="hidden">
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="booking[slots][{{ $count }}][status]"
                                                :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')"
                                                :value="'0'"
                                                :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')"
                                            >
                                                <option value="1">
                                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                                                </option>

                                                <option value="0">
                                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.close')
                                                </option>
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="booking[slots][{{ $count }}][status]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::from.control-group>
    
                                        @php $count++; @endphp
                                    </div>
                                @endforeach
                            </div>

                            <div v-else>
                                <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <!-- From -->
                                    <x-admin::form.control-group
                                        class="w-full mb-[10px]"
                                        v-if="booking_type == 'default'"
                                    >
                                        <x-admin::form.control-group.label>
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="from_day"
                                            :value="'sunday'"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                        >
                                            @foreach ($weeks as $week)
                                                <option value="{{ $week }}" selected>
                                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $week)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="from_day"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::from.control-group>
    
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label class="none">
                                            @lang('Time')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="from_time"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="from_time"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
    
                                <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <!-- To -->
                                    <x-admin::form.control-group
                                        class="w-full mb-[10px]"
                                        v-if="booking_type == 'default'"
                                    >
                                        <x-admin::form.control-group.label>
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="to_day"
                                            :value="'sunday'"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                        >
                                            @foreach ($weeks as $week)
                                                <option value="{{ $week }}">
                                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $week)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="to_day"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="to_time"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="to_time"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="grid grid-cols-3 gap-[10px] px-[16px] py-[10px]">
                                <!-- Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][name]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.name')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][name]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>

                                <!-- Price -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.price')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][price]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.price')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.price')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][price]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>

                                <!-- Quantity -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.qty')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][booking::app.admin.catalog.products.edit.type.booking.modal.ticket.qty]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.qty')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.qty')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][quantity]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>

                                <!-- Special Price -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.special-price')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][special_price]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.special-price')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.special-price')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][special_price]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>

                                <!-- Valid From -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.valid-from')
                                    </x-admin::form.control-group.label>

                                    <x-booking::form.control-group.control
                                        type="datetime"
                                        name="booking[tickets][ticket_0][en][valid_from]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.valid-from')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.valid-from')"
                                    >
                                    </x-booking::form.control-group.control>

                                    <x-booking::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][valid_from]"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::from.control-group>

                                <!-- Valid Until -->
                                <x-booking::form.control-group>
                                    <x-booking::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.valid-until')
                                    </x-booking::form.control-group.label>

                                    <x-booking::form.control-group.control
                                        type="datetime"
                                        name="booking[tickets][ticket_0][en][valid_until]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.valid-until')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.valid-until')"
                                    >
                                    </x-booking::form.control-group.control>

                                    <x-booking::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][valid_until]"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::from.control-group>

                                <!-- Valid Until -->
                                <x-booking::form.control-group>
                                    <x-booking::form.control-group.label>
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.description')
                                    </x-booking::form.control-group.label>

                                    <x-booking::form.control-group.control
                                        type="textarea"
                                        class="w-full"
                                        name="booking[tickets][ticket_0][en][description]"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.description')"
                                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.description')"
                                    >
                                    </x-booking::form.control-group.control>

                                    <x-booking::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][description]"
                                    >
                                    </x-booking::form.control-group.error>
                                </x-booking::from.control-group>
                            </div>
                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                            v-text="booking_type != 'event' 
                                ? '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.save')' 
                                : '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.save')'"
                        >
                        </button>
                    </x-slot:footer>
                </x-booking::modal>
            </form>
        </x-booking::form>
    </script>

    <script type="module">
        app.component('v-booking-information', {
            template: '#v-booking-information-template',

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

                    charged_per: 'guest',
                    
                    renting_type: 'daily',

                    same_slot: 0,

                    bookingSubType: 'one',

                    bookingSwatch: false,

                    bookingSwatchType: false,

                    chargedSwatch: false,
                    
                    rentingSwatch: false,

                    sameSlotSwatch: false,

                    slotRowCount: 1,

                    slots: [],
                };
            },

            created() {
                this.booking.available_from = @json($formattedAvailableFrom);
                this.booking.available_to = @json($formattedAvailableTo);
            },

            methods: {
                storeSlots(params, { resetForm }) {
                    this.slots.push({
                        id: this.slotRowCount,
                        from_day: params?.from_day,
                        from_time: params.from_time,
                        to_day: params?.to_day,
                        to_time: params.to_time,
                    });
                    
                    this.slotRowCount++

                    this.$refs.addOptionsRow.toggle();

                    resetForm();
                }
            },
        });
    </script>
@endPushOnce