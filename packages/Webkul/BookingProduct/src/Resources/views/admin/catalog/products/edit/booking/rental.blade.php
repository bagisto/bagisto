{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-rental-booking></v-rental-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-rental-booking-template"
    >
        <!-- Renting Type -->
        <x-admin::form.control-group class="w-full mb-2.5">
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="booking[renting_type]"
                rules="required"
                v-model="rental_booking.renting_type"
                :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')"
            >
                @foreach (['daily', 'hourly', 'daily_hourly'] as $item)
                    <option value="{{ $item }}">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.' . $item)
                    </option>
                @endforeach
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error 
                control-name="booking[renting_type]"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <!-- Daily Price -->
        <x-admin::form.control-group
            class="w-full mb-2.5"
            v-if="rental_booking.renting_type == 'daily' || rental_booking.renting_type == 'daily_hourly'"
        >
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[daily_price]"
                rules="required"
                v-model="rental_booking.daily_price"
                :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error 
                control-name="booking[renting_type]"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <!-- Hourly Price -->
        <x-admin::form.control-group
            class="w-full mb-2.5"
            v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'"
        >
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="booking[hourly_price]"
                rules="required"
                v-model="rental_booking.hourly_price"
                :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.error 
                control-name="booking[hourly_price]"
            >
            </x-admin::form.control-group.error>
        </x-admin::form.control-group>

        <div v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'">
            <!-- Same Slot For All -->
            <x-admin::form.control-group class="w-full mb-2.5" >
                <x-admin::form.control-group.label class="required">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    name="booking[same_slot_all_days]"
                    rules="required"
                    v-model="rental_booking.same_slot_all_days"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')"
                    @change="slots.one=[];slots.many=[];"
                >
                    <option value="1">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.yes')
                    </option>

                    <option value="0">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.no')
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error 
                    control-name="booking[same_slot_all_days]"
                >
                </x-admin::form.control-group.error>
            </x-admin::form.control-group>
        </div>

        <!-- Slots -->
        <div class="flex gap-5 justify-between p-4" v-if="rental_booking.renting_type != 'daily'">
            <div class="flex flex-col gap-2">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                </p>
            </div>

            <!-- Add Slots Button -->
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
        <div class="mt-4 overflow-x-auto" v-if="rental_booking.renting_type != 'daily'">
            <template v-if="slots.one?.length || slots.many[0]?.length">
                <x-admin::table>
                    <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
                        <x-admin::table.thead.tr>
                            <!-- From -->
                            <x-admin::table.th>
                                @lang('From')
                            </x-admin::table.th>

                            <!-- To -->
                            <x-admin::table.th>
                                @lang('To')
                            </x-admin::table.th>

                            <!-- Action tables heading -->
                            <x-admin::table.th>
                                @lang('Actions')
                            </x-admin::table.th>
                        </x-admin::table.thead.tr>
                    </x-admin::table.thead>

                    <x-admin::table.tbody.tr
                        v-if="slots.one?.length"
                        v-for="(slot, index) in slots.one"
                    >
                        <!-- Hidden Field Id -->
                        <input
                            type="hidden"
                            :name="'booking[slots][' + index + '][id]'"
                            :value="slot.id"
                        />

                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.from"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from]'"
                                :value="slot.from"
                            />
                        </x-admin::table.td>

                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot.to"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to]'"
                                :value="slot.to"
                            />
                        </x-admin::table.td>

                        <!-- Actions button -->
                        <x-admin::table.td class="!px-0">
                            <span
                                class="icon-edit p-1.5 rounded-1.5 text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(1, slot)"
                            >
                            </span>

                            <span
                                class="icon-delete p-1.5 rounded-1.5 text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption(1, slot)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>

                    <x-admin::table.tbody.tr
                        v-if="slots.many?.length"
                        v-for="(slot, index) in slots.many[0]"
                    >
                        <!-- Hidden Field Id -->
                        <input
                            type="hidden"
                            :name="'booking[slots][' + index + '][id]'"
                            :value="slot[0].id"
                        />

                        <!-- From -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot[0].from ? slot[0].from : '00:00'"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][from]'"
                                :value="slot[0].from"
                            />
                        </x-admin::table.td>

                        <!-- To -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="slot[0].to ? slot[0].to : '00:00'"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[slots][' + index + '][to]'"
                                :value="slot[0].to"
                            />
                        </x-admin::table.td>

                        <!-- Actions button -->
                        <x-admin::table.td class="!px-0">
                            <span
                                class="icon-edit p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(0, slot)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>
                </x-admin::table>
            </template>

            <template v-else>
                <v-empty-info type="rental"></v-empty-info>
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
                @submit.prevent="handleSubmit($event, store)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::modal ref="addOptionsRow">
                    <x-slot:header>
                        <p class="text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div
                            class="flex gap-4 px-4 py-2.5"
                            v-if="parseInt(rental_booking.same_slot_all_days)"
                        >
                            <!-- ID -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            >
                            </x-admin::form.control-group.control>

                            <!-- Hidden Booking Type -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="same_slot_all_days"
                                v-model="rental_booking.same_slot_all_days"
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
                        </div>

                        <!-- Booking Type Many -->
                        <div v-else>
                            <div class="grid grid-cols-3 gap-2.5 pb-3">
                                @foreach (['day', 'from', 'to'] as $item)
                                    <div class="font-semibold text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $item)
                                    </div>
                                @endforeach

                            </div>

                            @foreach ($days as $key => $day)
                                <div class="grid grid-cols-3 gap-2.5">
                                    <div class="text-black dark:text-white">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.' . $day)
                                    </div>

                                    <!-- Hidden ID Field -->
                                    <x-booking::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}][0]id"
                                    >
                                    </x-booking::form.control-group.control>

                                    <!-- Hidden Days Field -->
                                    <x-booking::form.control-group.control
                                        type="hidden"
                                        name="[{{ $key }}][0]day"
                                        value="{{ $day }}"
                                    >
                                    </x-booking::form.control-group.control>

                                    <!-- Slots From -->
                                    <x-booking::form.control-group class="w-full mb-2.5">
                                        <x-booking::form.control-group.label class="hidden">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </x-booking::form.control-group.label>

                                        <x-booking::form.control-group.control
                                            type="time"
                                            name="[{{ $key }}][0]from"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}][0]from"
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
                                            name="[{{ $key }}][0]to"
                                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                                        >
                                        </x-booking::form.control-group.control>

                                        <x-booking::form.control-group.error 
                                            control-name="[{{ $key }}][0]to"
                                        >
                                        </x-booking::form.control-group.error>
                                    </x-booking::form.control-group>
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
                @submit.prevent="handleSubmit($event, store)"
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
        app.component('v-rental-booking', {
            template: '#v-rental-booking-template',

            props: ['bookingProduct'],

            data() {
                return {
                    rental_booking: @json($bookingProduct && $bookingProduct?->rental_slot) ? @json($bookingProduct?->rental_slot) : {
                        renting_type: 'daily',

                        daily_price: '',

                        hourly_price: '',

                        same_slot_all_days: 1,

                        slots: []
                    },

                    slots: {
                        one: [],

                        many: [],
                    },

                    optionRowCount: 0,
                }
            },

            created() {
                if (this.rental_booking.slots && this.rental_booking.same_slot_all_days) {
                    if (this.rental_booking.slots?.length) {
                        let [lastId] = this.rental_booking.slots?.map(({ id }) => id).slice(-1);
        
                        this.optionRowCount = lastId?.split('_')[1];
                    }

                    this.slots['one'] =  this.rental_booking.slots ?? [];
                } else {
                    this.slots.many.push(this.rental_booking.slots ?? []);
                }
            },

            methods: {
                store(params) {
                    if (params.same_slot_all_days) {
                        if (! params.id) {
                            this.optionRowCount++;
                            params.id = 'option_' + this.optionRowCount;
                        }

                        let foundIndex = this.slots.one.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            this.slots.one[foundIndex] = { 
                                ...this.slots.one[foundIndex].params, 
                                ...params
                            };
                        } else {
                            this.slots.one.push(params);
                        }

                        this.$refs.addOptionsRow.toggle();
                    } else {
                        if (params && params?.id) {
                            let item = this.slots.many[0].flatMap(i => Object.values(i)).find(i => i.id === params.id);

                            if (item) {
                                for (const key in params) {
                                    item[key] = params[key];
                                }

                                this.slots.many = [...this.slots.many.filter(i => i !== item)];
                            }

                            this.$refs.addManyOptionsRow.toggle();
                        } else {
                            for (const key in params) {
                                params[key][0].id = 'option_' + this.optionRowCount++;
                            }

                            let result = Object.values(params).flat();

                            this.slots.many.push(result);

                            this.$refs.addOptionsRow.toggle();
                        }
                    }
                },

                editModal(type, values) {
                    if (type) {    
                        this.oneOptionModal(values[0]);
                    } else {
                        this.manyOptionsModelForm(values[0]);
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

                removeOption(id) {
                    this.slots = this.slots.filter(option => option.id !== id);
                },
            },
        });
    </script>
@endpushOnce