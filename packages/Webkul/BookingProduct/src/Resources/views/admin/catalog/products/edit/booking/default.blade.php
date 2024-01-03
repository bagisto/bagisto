{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-default-booking></v-default-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-default-booking-template"
    >
        <!-- Type -->
        <x-admin::form.control-group class="w-full">
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.type.booking.type.title')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="booking[booking_type]"
                rules="required"
                :label="trans('booking::app.admin.catalog.products.edit.type.booking.type.title')"
                v-model="default_booking.booking_type"
                @change="slots.one=[];slots.many=[];optionRowCount=0"
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
            <x-admin::form.control-group class="w-full">
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slot-duration')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="booking[duration]"
                    required="required|min_value:1"
                    v-model="default_booking.duration"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.slot-duration')"
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
                    @lang('booking::app.admin.catalog.products.edit.type.booking.break-duration')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="booking[break_time]"
                    required="required|min_value:1"
                    v-model="default_booking.break_time"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.break-duration')"
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
            <div class="flex gap-x-1 items-center" v-if="! slots.many[0]?.length">
                <div
                    class="secondary-button"
                    @click="$refs.drawerform.toggle()"
                >
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                </div>
            </div>
        </div>

        <!-- Table Information -->
        <div class="mt-4 overflow-x-auto">
            <template v-if="slots.one?.length || slots.many[0]?.length">
                <template v-if="slots.one?.length || slots.many[0]?.length">
                    <div
                        class="grid border-b last:border-b-0 border-slate-300 dark:border-gray-800"
                        v-if="slots.one?.length"
                        v-for="(slot, index) in slots.one"
                    >
                        <div class="flex gap-2.5 justify-between py-3 cursor-pointer">
                            <div class="grid gap-1.5 place-content-start">
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ slot.from_day }} - @{{ slot.from }}
                                </p>

                                <!-- Hidden Field Id -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][id]'"
                                    :value="slot.id"
                                />

                                <!-- Hidden Field From day -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][from_day]'"
                                    :value="slot.from_day"
                                />

                                <!-- Hidden Field From Time -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][from]'"
                                    :value="slot.from"
                                />

                                <p class="dark:text-white">
                                    @{{ slot.to_day }} - @{{ slot.to }}
                                </p>

                                <!-- Hiiden Field TO Day -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][to_day]'"
                                    :value="slot.to_day"
                                />

                                <!-- Hiiden Field TO Time -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][to]'"
                                    :value="slot.to"
                                />
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-x-5 items-center place-content-start text-right">
                                <p
                                    class="text-blue-600 cursor-pointer transition-all hover:underline"
                                    @click="edit(index)"
                                >
                                    Edit
                                </p>
                                
                                <p
                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                    @click="remove(index)"
                                >
                                    Delete
                                </p>
                            </div>
                        </div>
                    </div>
                </template>

                    {{-- <x-admin::table.tbody.tr
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
                                v-text="slot.from ? slot.from : '00:00'"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from]'"
                                :value="slot.from ? slot.from : '00:00'"
                            />
                        </x-admin::table.td>

                        <!-- To -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.to ? slot.to : '00:00'"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to]'"
                                :value="slot.to ? slot.to : '00:00'"
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
                                @click="editModal('many', slot)"
                            >
                            </span>

                            <span
                                class="icon-delete p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption('many', slot)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>
                </x-admin::table> --}}
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

         <!-- Drawer component -->
         <x-booking::form
         v-slot="{ meta, errors, handleSubmit }"
         as="div"
         ref="modelForm"
     >
         <form
             @submit.prevent="handleSubmit($event, store)"
             enctype="multipart/form-data"
             ref="createOptionsForm"
         >
             <x-admin::drawer ref="drawerform">
                 <x-slot:header>
                     <div class="flex justify-between items-center">
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.title')
                        </p>

                         <div class="flex gap-2">
                             <button
                                 type="submit"
                                 class="primary-button"
                                 :class="! parseInt(sameSlotAllDays) ? 'mr-11' : ''"
                             >
                                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.save')
                             </button>

                             <div
                                 class="mr-11 primary-button"
                                 v-if="parseInt(sameSlotAllDays)"
                                 @click="addSlot()"
                             >
                                 @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                             </div>
                         </div>
                     </div>
                 </x-slot:header>

                 <x-slot:content>
                     <!-- Booking Type One -->
                     <div
                        v-if="default_booking.booking_type == 'one'"
                        class="mb-2.5"
                    >
                        <div class="flex gap-4 mb-2.5 px-4 py-2.5 border-b dark:border-gray-800">
                            <!-- From Day -->
                            <x-admin::form.control-group class="w-full">
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
                                    <option
                                        v-for="(day, index) in days"
                                        :value="index"
                                        v-text="'@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.week')'.replace(':day', day)"
                                    >
                                    </option>
                                </x-admin::form.control-group.control>
                
                                <x-admin::form.control-group.error 
                                    control-name="from_day"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                
                            <!-- From -->
                            <x-booking::form.control-group class="w-full">
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
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                </x-admin::form.control-group.label>
                
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="to_day"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                >
                                    <option
                                        v-for="(day, index) in days"
                                        :value="index"
                                        v-text="'@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.week')'.replace(':day', day)"
                                    >
                                    </option>
                                </x-admin::form.control-group.control>
                
                                <x-admin::form.control-group.error 
                                    control-name="to_day"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                
                            <!-- TO Time -->
                            <x-booking::form.control-group class="w-full">
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
                                <div class="font-semibold text-black dark:text-white">
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
                                <x-booking::form.control-group class="w-full">
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
                                <x-booking::form.control-group class="w-full">
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
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="hidden">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="[{{ $key }}]status"
                                        value="0"
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
             </x-admin::drawer>
         </form>
     </x-booking::form>

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
                            <x-booking::form.control-group class="w-full">
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
                            <x-booking::form.control-group class="w-full">
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
                            <x-admin::form.control-group class="w-full">
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

            data() {
                return {
                    default_booking: @json($bookingProduct && $bookingProduct?->default_slot) ? @json($bookingProduct?->default_slot) : {
                        booking_type: 'one',

                        duration: 45,

                        break_time: 15,

                        slots: []
                    },

                    optionRowCount: 0,

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
                    },

                    days: {
                        0: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.monday')',
                        1: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.tuesday')',
                        2: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.wednesday')',
                        3: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.tuesday')',
                        4: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.friday')',
                        5: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.saturday')',
                        6: '@lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.sunday')',
                    },
                }
            },

            created() {
                if (this.default_booking.slots) {
                    let [lastId] = this.default_booking.slots?.map(({ id }) => id).slice(-1);

                    if (lastId) {
                        this.optionRowCount = lastId?.split('_')[1];
                    }
                }

                if (this.default_booking.booking_type === 'one') {
                    let data = this.default_booking.slots?.map((e) => {
                        e.from_day = this.days[e.from_day];
                        e.to_day = this.days[e.to_day];

                        return e;
                    })

                    this.slots['one'] =  data ?? [];
                } else {
                    if (this.default_booking.slots) {
                        this.slots['many'].push(this.default_booking.slots ?? []);
                    }
                }
            },

            methods: {
                store(params) {
                    if (params.booking_type === 'one') {
                        if (! params.id) {
                            this.optionRowCount++;
                            params.id = 'option_' + this.optionRowCount;
                        }

                        let data = Object.keys(params)?.map((key) => {
                            if (key == 'from_day') {
                                params.from_day = this.days[params.from_day];
                            }

                            if (key == 'to_day') {
                                params.to_day = this.days[params.to_day];
                            }

                            return key;
                        });

                        let foundIndex = this.slots.one.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            this.slots.one[foundIndex] = { 
                                ...this.slots.one[foundIndex].params, 
                                ...params
                            };
                        } else {
                            if (! this.slots.one.some(item => item.id === params.id)) {
                                this.slots.one.push(params);
                            }
                        }

                        this.$refs.drawerform.toggle();
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

                            this.slots.many.push(params);

                            this.$refs.drawerform.toggle();
                        }
                    }
                },

                edit(element) {
                    if (this.default_booking.booking_type == 'one') {
                        this.$refs.modelForm.setValues(this.slots.one[element]);
                    } else {
                        this.$refs.modelForm.setValues(element);
                    }

                    this.$refs.drawerform.toggle();
                },

                remove(element) {
                    if (this.default_booking.booking_type == 'one') {
                        delete this.slots.one[element];
                    } else {

                    }
                },

                // removeOption(type , values) {
                //     if (type === 'many') {
                //         // this.slots.many = this.slots.many.filter(option => option.id !== values.id);
                //     } else {
                //         this.slots.one = this.slots.one.filter(option => option.id !== values.id);
                //     }
                // },
            }
        });
    </script>
@endpushOnce