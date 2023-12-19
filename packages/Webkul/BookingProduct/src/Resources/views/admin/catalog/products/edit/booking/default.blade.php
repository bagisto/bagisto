{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-default-booking :bookingProduct = "$bookingProduct ?? []"></v-default-booking>

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
            <div class="flex gap-x-1 items-center" v-if="! slots.many?.length">
                <div
                    class="secondary-button"
                    @click="$refs.addOptionsRow.toggle()"
                >
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                </div>
            </div>
        </div>

        <!-- Table Information -->
        <div class="mt-4 overflow-x-auto">
            <template v-if="slots.one?.length || slots.many?.length">
                <x-admin::table>
                    <x-admin::table.thead class="text-sm font-medium dark:bg-gray-800">
                        <x-admin::table.thead.tr>
                            <!-- From day -->
                            <x-admin::table.th v-if="slots.one?.length">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.from-day')
                            </x-admin::table.th>

                            <!-- From -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.from')
                            </x-admin::table.th>

                            <!-- TO day -->
                            <x-admin::table.th v-if="slots.one?.length">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.to-day')
                            </x-admin::table.th>

                            <!-- To -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.to')
                            </x-admin::table.th>

                            <!-- Status -->
                            <x-admin::table.th v-if="slots.many?.length">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.status')
                            </x-admin::table.th>

                            <!-- Action tables heading -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.action')
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
                                class="icon-edit p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(slot)"
                            >
                            </span>

                            <span
                                class="icon-delete p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption(slot)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>

                    <x-admin::table.tbody.tr
                        v-if="slots.many?.length"
                        v-for="(slot, index) in slots.many[0]"
                    >
                        <!-- Day -->
                        <p
                            class="hidden"
                            v-text="slot.day"
                        >
                        </p>

                        <!-- Hidden Field Id -->
                        <input
                            type="hidden"
                            :name="'booking[slots][' + index + '][id]'"
                            :value="slot.id"
                        />

                        <!-- From -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.from ?? '00:00'"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from]'"
                                :value="slot.from"
                            />
                        </x-admin::table.td>

                        <!-- To -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.to ?? '00:00'"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to]'"
                                :value="slot.to"
                            />
                        </x-admin::table.td>

                        <!-- Status -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.status"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][status]'"
                                :value="slot.status"
                            />
                        </x-admin::table.td>

                        <!-- Actions button -->
                        <x-admin::table.td class="!px-0">
                            <span
                                class="icon-edit p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(slot)"
                            >
                            </span>

                            <span
                                class="icon-delete p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption(slot)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>
                </x-admin::table>
            </template>

            <template v-else>
                <v-empty-info type="default"></v-empty-info>
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
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Booking Type One -->
                        <div
                            v-if="default_booking.booking_type == 'one'"
                            class="mb-2.5"
                        >
                            <div class="flex gap-4 mb-2.5 px-4 py-2.5 border-b dark:border-gray-800">
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
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $day)
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
                                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $day)
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

                        <!-- Booking Type Many -->
                        <div v-if="default_booking.booking_type == 'many'">
                            <div class="grid grid-cols-4 gap-2.5 pb-3">
                                @foreach (['day', 'from', 'to', 'status'] as $item)
                                    <div class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                    </div>
                                @endforeach

                            </div>

                            @foreach ($days as $key => $day)
                                <div class="grid grid-cols-4 gap-2.5">
                                    <div class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $day)
                                    </div>

                                    <!-- Hidden ID Field -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}]id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Hidden Days Field -->
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
                                            ::rules="slotsStatus[{{ $key }}] ? 'required' : ''"
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
                                            @change="slotsStatus[{{ $key }}]=$event.target.value==1?true:false;"
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

                            <!-- Status -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="status"
                                >
                                    <option value="1">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.open')
                                    </option>

                                    <option value="0">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.close')
                                    </option>
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

                    slotsStatus: {
                        0: false,
                        1: false,
                        2: false,
                        3: false,
                        4: false,
                        5: false,
                        6: false,
                    }
                }
            },

            created() {
                if (this.default_booking.booking_type === 'one') {
                    this.slots['one'] = this.default_booking.slots ?? [];
                } else {
                    this.slots['many'].push(this.default_booking.slots ?? []);
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

                        this.$refs.addOptionsRow.toggle();
                    } else {
                        if (params && params.id) {
                            let item = this.slots.many.flatMap(i => Object.values(i)).find(i => i.id === params.id);

                            if (item) {
                                for (const key in params) {
                                    item[key] = params[key];
                                }

                                this.slots.many = [...this.slots.many.filter(i => i !== item)];
                            }

                            this.$refs.addManyOptionsRow.toggle();
                        } else {
                            for (const key in params) {
                                params[key].id = 'option_' + this.optionRowCount++;
                            }

                            this.slots.many = [{ ...params }];

                            this.$refs.addOptionsRow.toggle();
                        }
                    }
                },

                editModal(values) {
                    let hasParam = values?.params;

                    if (hasParam) {
                        if (hasParam.booking_type === 'one') {
                            hasParam.id = values.id;
    
                            this.oneOptionModal(hasParam);
                        } else {
                            this.manyOptionsModelForm(values);
                        }
                     } else {
                        if (values.booking_type === 'one') {
                            this.oneOptionModal(values);
                        } else {
                            this.manyOptionsModelForm(values);    
                        }
                    }
                },

                oneOptionModal(params) {
                    this.$refs.modelForm.setValues(params);

                    this.$refs.addOptionsRow.toggle();
                },

                manyOptionsModelForm(params) {
                    this.$refs.ManyOptionsModelForm.setValues(params);

                    this.$refs.addManyOptionsRow.toggle();
                },

                removeOption(values) {
                    if (values.booking_type === 'many') {
                        // console.log(this.slots.many);
                        // this.slots.many = this.slots.many.filter(option => option.id !== values.id);
                    } else {
                        this.slots.one = this.slots.one.filter(option => option.id !== values.id);
                    }
                },
            }
        });
    </script>
@endpushOnce