{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-default-booking></v-default-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-default-booking-template"
    >
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
                @change="slots.one=[];slots.many=[];"
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

        <!-- Table Information -->
        <div class="mt-[15px] overflow-x-auto">
            <template v-if="slots.one?.length || slots.many?.length">
                <x-admin::table>
                    <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
                        <x-admin::table.thead.tr>
                            <!-- From day -->
                            <x-admin::table.th v-if="slots.one?.length">
                                @lang('From Day')
                            </x-admin::table.th>

                            <!-- From -->
                            <x-admin::table.th>
                                @lang('From')
                            </x-admin::table.th>

                            <!-- TO day -->
                            <x-admin::table.th v-if="slots.one?.length">
                                @lang('To Day')
                            </x-admin::table.th>

                            <!-- Day -->
                            <x-admin::table.th v-if="slots.many?.length">
                                @lang('Day')
                            </x-admin::table.th>

                            <!-- To -->
                            <x-admin::table.th>
                                @lang('To')
                            </x-admin::table.th>

                            <!-- Status -->
                            <x-admin::table.th v-if="slots.many?.length">
                                @lang('Status')
                            </x-admin::table.th>

                            <!-- Action tables heading -->
                            <x-admin::table.th>
                                @lang('Actions')
                            </x-admin::table.th>
                        </x-admin::table.thead.tr>
                    </x-admin::table.thead>

                    <!-- Table Data -->
                    <x-admin::table.tbody.tr
                        v-if="slots.one?.length"
                        v-for="(slot, index) in slots.one"
                    >
                        <!-- Admin-->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.params.from_day"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from_day]'"
                                :value="slot.params.from_day"
                            />
                        </x-admin::table.td>

                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.params.from"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from]'"
                                :value="slot.params.from"
                            />
                        </x-admin::table.td>

                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.params.to_day"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to_day]'"
                                :value="slot.params.to_day"
                            />
                        </x-admin::table.td>

                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.params.to"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to]'"
                                :value="slot.params.to"
                            />
                        </x-admin::table.td>

                        <!-- Actions button -->
                        <x-admin::table.td class="!px-0">
                            <span
                                class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(slot)"
                            >
                            </span>

                            <span
                                class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption(slot.id)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>

                    <x-admin::table.tbody.tr
                        v-for="slot in slots.many[0]"
                        v-else
                    >
                        <!-- Day -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.day"
                            >
                            </p>
                        </x-admin::table.td>

                        <!-- From -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.from ?? 'N/A'"
                            >
                            </p>
                        </x-admin::table.td>

                        <!-- To -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.to ?? 'N/A'"
                            >
                            </p>
                        </x-admin::table.td>

                        <!-- Status -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.status ?? 'N/A'"
                            >
                            </p>
                        </x-admin::table.td>

                        <!-- Actions button -->
                        <x-admin::table.td class="!px-0">
                            <span
                                class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(slot)"
                            >
                            </span>

                            <span
                                class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption(slot.id)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>
                </x-admin::table>
            </template>

            <template v-else>
                <div class="grid gap-[14px] justify-items-center py-[40px] px-[10px]">
                    <!-- Attribute Option Image -->
                    <img
                        class="w-[120px] h-[120px]"
                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                        alt="@lang('admin::app.catalog.attributes.create.add-attribute-options')"
                    />

                    <!-- Add Slots Information -->
                    <div class="flex flex-col gap-[5px] items-center">
                        <p class="text-[16px] text-gray-400 font-semibold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                        </p>
                    </div>

                    <!-- Add Slot Button -->
                    <div
                        class="secondary-button text-[14px]"
                        @click="$refs.addOptionsRow.toggle()"
                    >
                        @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                    </div>
                </div>
            </template>
        </div>

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
                        <div v-if="default_booking.booking_type == 'one'" class="mb-2.5">
                            <div class="flex gap-4 mb-[10px] px-4 py-2.5 border-b dark:border-gray-800">
                                <!-- From Day -->
                                <x-admin::form.control-group class="w-full mb-2.5">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from-day')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="booking_type"
                                        value="one"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Hidden Id Input -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="from_day"
                                        rules="required"
                                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from-day')"
                                    >
                                        @foreach ($days as $key => $day)
                                            <option value="{{ $day }}">
                                                {{ $day}}
                                            </option>
                                        @endforeach
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
                                        @foreach ($days as $key => $day)
                                            <option value="{{ $day }}">
                                                {{ $day}}
                                            </option>
                                        @endforeach
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
                            <div class="grid grid-cols-4 gap-[10px] pb-[10px]">
                                @foreach (['day', 'from', 'to', 'status'] as $item)
                                    <div class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                    </div>
                                @endforeach
                            </div>

                            @foreach ($days as $key => $day)
                                <div class="grid grid-cols-4 gap-[10px]">
                                    <div class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $day),
                                    </div>

                                    <!-- Id -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}]id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Hidden Day Value -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}]day"
                                        value="{{ $day }}"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Slots From -->
                                    <x-booking::form.control-group class="w-full mb-2.5">
                                        <x-booking::form.control-group.label class="hidden">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </x-booking::form.control-group.label>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            name="[{{ $key }}]from"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}]from"
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
                                            name="[{{ $key }}]to"
                                            {{-- rules="{ slots.many[index].status ? {required: true, time_min: slots.many[index].from } : '' }" --}}
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}]to"
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
                                            name="[{{ $key }}]status"
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
                                            control-name="[{{ $key }}]status"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
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
        </x-admin::form>

        <!-- Model For Edit Many type same slots for all booking -->
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
                <x-admin::modal ref="addManyOptionsRow">
                    <x-slot:header>
                        <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="flex gap-4 mb-[10px] px-4 py-2.5 border-b dark:border-gray-800">

                            <!-- Hidden Id Input -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            >
                            </x-admin::form.control-group.control>

                            <!-- Day -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('Day')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    class="text-[0]"
                                    name="day"
                                    contenteditable="false"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                
                            <!-- From -->
                            <x-booking::form.control-group class="w-full mb-2.5">
                                <x-booking::form.control-group.label class="required">
                                    @lang('From')
                                </x-booking::form.control-group.label>
                
                                <x-booking::form.control-group.control
                                    type="time"
                                    name="from"
                                    rules="required"
                                    :label="trans('Day')"
                                >
                                </x-booking::form.control-group.control>
                
                                <x-booking::form.control-group.error 
                                    control-name="day"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>

                            <!-- To -->
                            <x-booking::form.control-group class="w-full mb-2.5">
                                <x-booking::form.control-group.label class="required">
                                    @lang('To')
                                </x-booking::form.control-group.label>
                
                                <x-booking::form.control-group.control
                                    type="time"
                                    name="to"
                                    rules="required"
                                    :label="trans('To')"
                                >
                                </x-booking::form.control-group.control>
                
                                <x-booking::form.control-group.error 
                                    control-name="to"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>

                            <!-- Status -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('Status')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="status"
                                >
                                    <option value="1">@lang('Open')</option>
                                    <option value="0">@lang('Close')</option>
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
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

                    optionRowCount: 1,

                    slots: {
                        one: [],

                        many: [],
                    },
                }
            },

            created() {
                if (this.default_booking.booking_type === 'one') {
                    this.slots['one'] = this.default_booking.slots ?? [];
                } else {
                    this.slots['many'] = this.default_booking.slots ?? [];
                }
            },

            methods: {
                storeSlots(params) {
                    if (params.booking_type === 'one') {
                        if (params.id) {
                            let foundIndex = this.slots.one.findIndex(item => item.id === params.id);

                            this.slots.one[foundIndex].params = { 
                                ...this.slots.one[foundIndex].params, 
                                ...params
                            };
                        } else {
                            this.slots.one.push({ 
                                id: 'option_' + this.optionRowCount++, 
                                params
                            });
                        }
                    } else {
                        if (params.id) {
                            let foundIndex = this.slots.many.findIndex(item => item.id === params.id);

                            this.slots.many[foundIndex].params = {
                                ...this.slots.many[foundIndex].params,
                                ...params
                            };
                        } else {
                            for (const key in params) {
                                params[key].id = 'option_' + this.optionRowCount++;
                            }

                            this.slots.many = [{ ...params }];
                        }
                    }

                    this.$refs.addOptionsRow.toggle();
                },

                editModal(values) {
                    if (values.booking_type === 'one') {
                        values.params.id = values.id;

                        this.$refs.modelForm.setValues(values.params);

                        this.$refs.addOptionsRow.toggle();
                    } else {
                        this.$refs.modelForm.setValues(values);

                        this.$refs.addManyOptionsRow.toggle();
                    }
                },

                removeOption(id) {
                    this.slots.one = this.slots.one.filter(option => option.id !== id);
                },
            }
        });
    </script>
@endpushOnce