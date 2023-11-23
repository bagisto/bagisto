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
            <div class="grid gap-[8px]">
                <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <!-- Panel Header -->
                    <div class="flex gap-[20px] justify-between p-[16px]">
                        <div class="flex flex-col gap-[8px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @lang('Booking Information')
                            </p>
                        </div>
                    </div>

                    <!-- Panel Content -->
                    <div class="gap-[20px] justify-between p-[16px]">
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
                                @change="bookingSwatch=true, slots=[]"
                            >
                                @foreach (['default', 'appointment', 'event', 'rental', 'table'] as $item)
                                    <option value={{ $item }}>
                                        @lang($item)
                                    </option>
                                @endforeach
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

                        <!-- Charged Per -->
                        <x-admin::form.control-group
                            v-if="bookingSwatch && booking_type == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('Charged Per')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="select"
                                name="booking[price_type]"
                                rules="required"
                                :value="'guest'"
                                :label="trans('Same Slot For All days')"
                                :placeholder="trans('Same Slot For All days')"
                            >
                                <option value="guest" selected>{{ __('Guest') }}</option>
                                <option value="table">{{ __('Table') }}</option>
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[booking_type]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Guest Capacity -->
                        <x-admin::form.control-group
                            v-if="bookingSwatch && booking_type == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('Guest Capacity')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[qty]"
                                rules="required"
                                :label="trans('Same Slot For All days')"
                                :placeholder="trans('Same Slot For All days')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[booking_type]"
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
                                v-model="renting_type"
                                @change="rentingSwatch=true, slots=[]"
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

                        <!-- Prevent Scheduling Before -->
                        <x-admin::form.control-group
                            v-if="bookingSwatch && booking_type == 'table'"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('Prevent Scheduling Before')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[prevent_scheduling_before]"
                                rules="required"
                                :label="trans('Prevent Scheduling Before')"
                                :placeholder="trans('Prevent Scheduling Before')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[prevent_scheduling_before]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Slot Duration -->
                        <x-admin::form.control-group
                            v-if="(bookingSwatchType && bookingSubType == 'many') || 
                                (bookingSwatch && (booking_type == 'appointment' || booking_type == 'table'))"
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
                            v-if="(bookingSwatchType && bookingSubType == 'many') || 
                                (bookingSwatch && (booking_type == 'appointment' || booking_type == 'table'))"
                            class="w-full mb-[10px]"
                        >
                            <x-booking::form.control-group.label class="required">
                                @lang('Break Duration')
                            </x-booking::form.control-group.label>

                            <x-booking::form.control-group.control
                                type="text"
                                name="booking[duration]"
                                rules="required"
                                :label="trans('Break Duration')"
                                :placeholder="trans('Break Duration')"
                            >
                            </x-booking::form.control-group.control>

                            <x-booking::form.control-group.error 
                                control-name="booking[duration]"
                            >
                            </x-booking::form.control-group.error>
                        </x-booking::form.control-group>

                        <!-- Same Slot For All days -->
                        <x-admin::form.control-group
                            v-if="bookingSwatch && (
                                booking_type == 'appointment'
                                || booking_type == 'table'
                                || renting_type != 'daily'
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
                </div>

                @php
                    $weeks = ['sunday', 'monaday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                @endphp

                <!-- Slots Component -->
                <div
                    class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]"
                    v-if="renting_type != 'daily'"
                >
                    <div class="flex gap-[20px] justify-between">
                        <div class="flex flex-col gap-[8px]">
                            <p
                                class="text-[16px] text-gray-800 dark:text-white font-semibold"
                                v-text="booking_type != 'event' ? '@lang('Slots')' : '@lang('Tickets')'"
                            >
                            </p>
                        </div>

                        <!-- Add Slot Button -->
                        <div class="flex gap-x-[4px] items-center">
                            <div
                                v-text="booking_type != 'event' ? 'Add Slot' : 'Add Tickets'"
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
                                    v-text="booking_type != 'event' ? 'Add Slot' : 'Add Tickets'"
                                >
                                </p>
                                
                                <p
                                    class="text-gray-400 text-red"
                                    v-text="booking_type != 'event' ? '@lang('Add booking slot on the go.')' : '@lang('Add booking Tickets on the go.')'"
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
                <x-admin::modal
                    @toggle="listenModal"
                    ref="addOptionsRow"
                >
                    <x-slot:header>
                        <p
                            class="text-[18px] text-gray-800 dark:text-white font-bold"
                            v-text="booking_type != 'event' ? 'Add Slot' : 'Add Tickets'"
                        >
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div v-if="(booking_type != 'event')">
                            <div class="flex gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                <!-- From -->
                                <x-admin::form.control-group
                                    class="w-full mb-[10px]"
                                    v-if="booking_type == 'default'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('From')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="from_day"
                                        :value="'sunday'"
                                        :label="trans('admin::app.catalog.attributes.create.admin')"
                                    >
                                        @foreach ($weeks as $week)
                                            <option value="{{ $week }}" selected>
                                                @lang($week)
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
                                        @lang('To')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="to_day"
                                        :value="'sunday'"
                                        :label="trans('admin::app.catalog.attributes.create.admin')"
                                    >
                                        @foreach ($weeks as $week)
                                            <option value="{{ $week }}" selected>
                                                @lang($week)
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
                                        @lang('To')
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

                        <div v-else-if="bookingSubType == 'many'">
                            <div class="grid grid-cols-4 gap-[10px] px-[16px] py-[10px]">
                                @php $count = 0; @endphp

                                @foreach ($weeks as $item)
                                    <!-- Day -->
                                    <x-admin::form.control-group.label>
                                        {{ $item }}
                                    </x-admin::form.control-group.label>
    
                                    <!-- From -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="hidden">
                                            @lang('From')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="booking[slots][{{ $count }}][from]"
                                            :label="trans('From')"
                                            :placeholder="trans('From')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="booking[slots][{{ $count }}][from]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::from.control-group>
    
                                    <!-- To -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="hidden">
                                            @lang('To')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="booking[slots][{{ $count }}][to]"
                                            :label="trans('To')"
                                            :placeholder="trans('To')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="booking[slots][{{ $count }}][to]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::from.control-group>
    
                                    <!-- Status -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="hidden">
                                            @lang('Status')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="booking[slots][{{ $count }}][status]"
                                            :label="trans('Status')"
                                            :value="'0'"
                                            :placeholder="trans('Status')"
                                        >
                                            <option value="1">Open</option>
                                            <option value="0">Close</option>
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="booking[slots][{{ $count }}][status]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::from.control-group>

                                    @php $count++; @endphp
                                @endforeach
                            </div>
                        </div>

                        <div v-else>
                            <div class="grid grid-cols-3 gap-[10px] px-[16px] py-[10px]">
                                <!-- Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('Name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][name]"
                                        :label="trans('Name')"
                                        :placeholder="trans('Name')"
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
                                        @lang('Price')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][price]"
                                        :label="trans('Price')"
                                        :placeholder="trans('Price')"
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
                                        @lang('Quantity')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][quantity]"
                                        :label="trans('Quantity')"
                                        :placeholder="trans('Quantity')"
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
                                        @lang('Special Price')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking[tickets][ticket_0][en][special_price]"
                                        :label="trans('Special Price')"
                                        :placeholder="trans('Special Price')"
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
                                        @lang('Valid From')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="datetime"
                                        name="booking[tickets][ticket_0][en][valid_from]"
                                        :label="trans('Valid From')"
                                        :placeholder="trans('Valid From')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][valid_from]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>

                                <!-- Valid Until -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('Valid Until')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="datetime"
                                        name="booking[tickets][ticket_0][en][valid_until]"
                                        :label="trans('Valid Until')"
                                        :placeholder="trans('Valid Until')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][valid_until]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>

                                <!-- Valid Until -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('Description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        class="w-full"
                                        name="booking[tickets][ticket_0][en][description]"
                                        :label="trans('Description')"
                                        :placeholder="trans('Description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="booking[tickets][ticket_0][en][description]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::from.control-group>
                            </div>
                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                            v-text="booking_type != 'event' ? '@lang('Save Slot')' : '@lang('Save Tickets')'"
                        >
                        </button>
                    </x-slot:footer>
                </x-admin::modal>
            </form>
        </x-admin::form>
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

                    slots_available: [
                        'sunday',
                        'monaday',
                        'tuesday',
                        'wednesday',
                        'thursday',
                        'friday',
                        'saturday',
                    ],

                    booking_type: 'default',
                    
                    renting_type: '',

                    bookingSubType: 'one',

                    bookingSwatch: false,
                    
                    bookingSwatchType: false,

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