@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-slots-template"
    >
        <div class="flex gap-5 justify-between py-4">
            <div class="flex flex-col gap-1">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('booking::app.admin.catalog.products.edit.booking.slots.title')
                </p>

                <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                    @lang('booking::app.admin.catalog.products.edit.booking.slots.description-info')
                 </p>
            </div>

            <!-- Add Slots Button -->
            <div class="flex gap-x-1 items-center">
                <div
                    class="secondary-button"
                    @click="$refs.drawerform.toggle()"
                >
                    @lang('booking::app.admin.catalog.products.edit.booking.slots.add')
                </div>
            </div>
        </div>

        <!-- Table Information -->
        <div class="overflow-x-auto">
            <template v-if="different_for_week?.length || same_for_week?.length">
                <!-- For Same Slot All Days -->
                <template
                    v-if="parseInt(bookingProduct.same_slot_all_days)"
                    v-for="(data, index) in same_for_week"
                >
                    <div class="grid border-b border-slate-300 dark:border-gray-800 last:border-b-0">
                        <div class="flex gap-2.5 justify-between py-3 cursor-pointer">
                            <div class="grid gap-1.5 place-content-start">
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('From') - @{{ data.from }}
                                </p>
    
                                <!-- Hidden field for Id -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][id]'"
                                    :value="data.id"
                                />
    
                                <!-- Hidden field for from -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][from]'"
                                    :value="data.from"
                                />
    
                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('To') - @{{ data.to }}
                                </p>
    
                                <!-- Hidden field for To -->
                                <input
                                    type="hidden"
                                    :name="'booking[slots][' + index + '][to]'"
                                    :value="data.to"
                                />
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-x-5 items-center place-content-start text-right">
                                <p
                                    class="text-blue-600 cursor-pointer transition-all hover:underline"
                                    @click="editSlot(index)"
                                >
                                    @lang('booking::app.admin.catalog.products.edit.booking.slots.edit')
                                </p>
                                
                                <p
                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                    @click="remove(index)"
                                >
                                    @lang('booking::app.admin.catalog.products.edit.booking.slots.delete')
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
    
                <!-- For Not Same Slot All Days -->
                <template
                    v-else-if="different_for_week?.length"
                    v-for="(slot, slotIndex) in different_for_week"
                >
                    <div class="grid border-b border-slate-300 dark:border-gray-800 last:border-b-0">
                        <template v-for="(item, itemIndex) in slot">
                            <div
                                class="text-base text-gray-800 dark:text-white my-2"
                                v-text="itemIndex"
                            >
                            </div>
    
                            <template v-for="(time, timeIndex) in item">
                                <div class="flex gap-2.5 justify-between py-3 cursor-pointer">
                                    <div class="grid gap-1.5 place-content-start">
                                        <p class="text-gray-800 dark:text-white">
                                            From - @{{ time.from }}
                                        </p>
    
                                        <!-- Hidden Field for from -->
                                        <input
                                            type="hidden"
                                            :name="'booking[slots][' + slotIndex + '][' + timeIndex + '][from]'"
                                            :value="time.from"
                                        />
    
                                        <p class="text-gray-800 dark:text-white">
                                            To - @{{ time.to }}
                                        </p>
    
                                        <!-- Hidden Field for To -->
                                        <input
                                            type="hidden"
                                            :name="'booking[slots][' + slotIndex + '][' + timeIndex + '][to]'"
                                            :value="time.to"
                                        />
                                    </div>
        
                                    <!-- Actions -->
                                    <div class="flex gap-x-5 place-content-start text-right">
                                        <p
                                            class="text-blue-600 cursor-pointer transition-all hover:underline"
                                            @click="editSlot(time);currentWeek=itemIndex;currentTime=timeIndex"
                                        >
                                            @lang('booking::app.admin.catalog.products.edit.booking.slots.edit')
                                        </p>
                                        
                                        <p
                                            class="text-red-600 cursor-pointer transition-all hover:underline"
                                            @click="currentWeek=itemIndex;currentTime=timeIndex;removeSlot(time);"
                                        >
                                            @lang('booking::app.admin.catalog.products.edit.booking.slots.delete')
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </template>
            </template>

            <!-- For Empty Illustration -->
            <div v-else>
                <v-empty-info ::type="bookingType"></v-empty-info>
            </div>
        </div>

        <!-- Drawer component -->
        <x-booking::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="modalForm"
        >
            <form
                @submit.prevent="handleSubmit($event, storeSlot)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::drawer ref="drawerform">
                    <x-slot:header>
                        <div class="flex justify-between items-center">
                            <p class="my-2.5 text-xl font-medium dark:text-white">
                                @lang('booking::app.admin.catalog.products.edit.booking.slots.title')
                            </p>

                            <div class="flex gap-2">
                                <button
                                    type="submit"
                                    class="primary-button"
                                    :class="! parseInt(sameSlotAllDays) ? 'mr-11' : ''"
                                >
                                    @lang('booking::app.admin.catalog.products.edit.booking.slots.save')
                                </button>

                                <div
                                    class="mr-11 primary-button"
                                    v-if="parseInt(sameSlotAllDays)"
                                    @click="addSlot()"
                                >
                                    @lang('booking::app.admin.catalog.products.edit.booking.slots.add')
                                </div>
                            </div>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <template v-if="parseInt(sameSlotAllDays)">
                            <template v-if="slots['same_for_week']?.length">
                                <div class="grid grid-cols-3 gap-2.5 mx-2.5 py-2.5 text-gray-800 dark:text-white">
                                    <div class="w-full">
                                        @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')
                                    </div>
                        
                                    <div class="w-full">
                                        @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')
                                    </div>
                                </div>

                                <template v-for="(slot, index) in slots['same_for_week']">
                                    <v-slot-item
                                        :control-name="'booking[slots][' + index + ']'"
                                        :slot-item="slot"
                                        @onRemoveSlot="remove($event)"
                                    >
                                    </v-slot-item>
                                </template>
                            </template>

                            <div v-else>
                                <v-empty-info ::type="bookingType"></v-empty-info>
                            </div>
                        </template>

                        <div v-else>
                            <div
                                v-for="(day, dayIndex) in week_days"
                                class="py-2.5 text-gray-800 dark:text-white border-b border-slate-300 dark:border-gray-800"
                                :title="day"
                            >
                                <div class="grid grid-cols-2 gap-2.5">
                                    <div v-text="day.charAt(0).toUpperCase() + day.slice(1)"></div>

                                    <span
                                        class="primary-button w-fit"
                                        @click="addSlot(dayIndex)"
                                    >
                                        @lang('booking::app.admin.catalog.products.edit.booking.slots.add')
                                    </span>
                                </div>

                                <template v-if="slots['different_for_week'][dayIndex] && slots['different_for_week'][dayIndex]?.length">
                                    <div class="grid grid-cols-3 gap-2.5 m-2.5">
                                        <div class="w-full">
                                            @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')
                                        </div>
                            
                                        <div class="w-full">
                                            @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')
                                        </div>
                                    </div>

                                    <template v-for="(slot, index) in slots['different_for_week'][dayIndex]">
                                        <v-slot-item
                                            :control-name="'booking[slots][' + dayIndex + '][' + index + ']'"
                                            :slot-item="slot"
                                            :id="index"
                                            @onRemoveSlot="remove($event, dayIndex)"
                                        >
                                        </v-slot-item>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
            </form>
        </x-booking::form>

        <!-- Single Edit Drawer component -->
        <x-booking::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="editModalForm"
        >
            <form
                @submit.prevent="handleSubmit($event, editStore)"
                enctype="multipart/form-data"
                ref="editOptionsForm"
            >
                <x-admin::drawer ref="editDrawerform">
                    <x-slot:header>
                        <div class="flex justify-between items-center">
                            <p class="my-2.5 text-xl font-medium text-gray-800 dark:text-white">
                                @lang('booking::app.admin.catalog.products.edit.booking.slots.title')
                            </p>

                            <button
                                type="submit"
                                class="mr-11 primary-button"
                            >
                                @lang('booking::app.admin.catalog.products.edit.booking.slots.save')
                            </button>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="grid grid-cols-2 gap-2.5 mx-2.5 py-2.5 text-gray-800 dark:text-white">
                            <div class="w-full">
                                @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')
                            </div>
                
                            <div class="w-full">
                                @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5 mx-2.5">
                            <!-- From -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            >
                            </x-admin::form.control-group.control>

                            <!-- From Input Field -->
                            <x-booking::form.control-group class="w-full">
                                <x-booking::form.control-group.label class="hidden">
                                    @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')
                                </x-booking::form.control-group.label>
                
                                <x-booking::form.control-group.control
                                    type="time"
                                    name="from"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')"
                                >
                                </x-booking::form.control-group.control>

                                <x-booking::form.control-group.error 
                                    control-name="from"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>
                
                            <!-- To Input Field -->
                            <x-booking::form.control-group class="w-full">
                                <x-booking::form.control-group.label class="hidden">
                                    @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')
                                </x-booking::form.control-group.label>
                
                                <x-booking::form.control-group.control
                                    type="time"
                                    name="to"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')"
                                >
                                </x-booking::form.control-group.control>

                                <!-- For Avoiding object value in last input field -->
                                <x-booking::form.control-group.control
                                    type="hidden"
                                >
                                </x-booking::form.control-group.control>

                                <x-booking::form.control-group.error 
                                    control-name="to"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
            </form>
        </x-booking::form>
    </script>

    <!-- Slot Item Vue Component -->
    <script
        type="text/x-template"
        id="v-slot-item-template"
    >
        <div class="grid grid-cols-3 gap-2.5 mx-2.5">
            <!-- From -->
            <input
                type="hidden"
                :name="controlName + '[id]'"
                :value="id"
            />

            <!-- From -->
            <x-booking::form.control-group class="w-full">
                <x-booking::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="controlName + '[from]'"
                    ::id="controlName + '[from]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')"
                    :placeholder="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.from')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="controlName + '[from]'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>

            <!-- To -->
            <x-booking::form.control-group class="w-full">
                <x-booking::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="controlName + '[to]'"
                    ::id="controlName + '[to]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')"
                    :placeholder="trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.to')"
                >
                </x-booking::form.control-group.control>

                <!-- Form Avoiding object value in last input field -->
                <x-booking::form.control-group.control
                    type="hidden"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="controlName + '[to]'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>

            <div
                class="icon-cross w-fit text-2xl p-1.5 cursor-pointer transition-all"
                @click="remove"
            >
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-slots', {
            template: '#v-slots-template',

            props: ['bookingType', 'bookingProduct', 'sameSlotAllDays'],

            data() {
                return {
                    slots: {
                        'same_for_week': [],
    
                        'different_for_week': [[], [], [], [], [], [], []]
                    },
    
                    week_days: [
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.sunday') }}",
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.monday') }}",
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.tuesday') }}",
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.wednesday') }}",
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.thursday') }}",
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.friday') }}",
                        "{{ trans('booking::app.admin.catalog.products.edit.booking.slots.modal.slot.saturday') }}"
                    ],

                    same_for_week: [],

                    different_for_week: [],

                    optionRowCount: 0,

                    currentWeek: '',

                    currentTime: '',
                }
            },

            created() {
                if (! this.bookingProduct && ! this.bookingProduct.slots) {
                    return;
                }
    
                if (parseInt(this.bookingProduct.same_slot_all_days)) {
                    this.same_for_week = this.bookingProduct.slots;
                } else {
                    let updatedData = this.bookingProduct.slots?.map((item, index) => {
                        let updatedItem = {};

                        updatedItem[this.week_days[index]] = item;

                        return updatedItem;
                    });

                    if (updatedData) {
                        this.different_for_week = updatedData;
                    }
                }

                if (this.same_for_week){
                    let [lastId] = Object.values(this.same_for_week).map(obj => obj.id);
    
                    this.optionRowCount = lastId?.split('_')[1];
                }
            },

            methods: {
                addSlot(dayIndex = null) {
                    if (dayIndex !== null) {
                        if (this.slots['different_for_week'][dayIndex] == undefined) {
                            this.slots['different_for_week'][dayIndex] = [];
                        }
    
                        this.slots['different_for_week'][dayIndex].push({
                            'id': '',
                            'from': '',
                            'to': ''
                        });
                    } else {
                        this.slots['same_for_week'].push({
                            'from': '',
                            'to': ''
                        });
                    }
                },
    
                remove(slot, dayIndex = null) {
                    if (dayIndex != null) {
                        let index = this.slots['different_for_week'][dayIndex].indexOf(slot)
    
                        this.slots['different_for_week'][dayIndex].splice(index, 1)
                    } else {
                        let index = this.slots['same_for_week'].indexOf(slot)
    
                        this.slots['same_for_week'].splice(index, 1)
                    }
                },

                storeSlot(params) {
                    const formDataObj = {};

                    let formData = new FormData(this.$refs.createOptionsForm);

                    formData.forEach((value, key) => (formDataObj[key] = value));

                    this.slotData(formDataObj);

                    this.$refs.drawerform.toggle();
                },

                editSlot(element) {
                    if (parseInt(this.sameSlotAllDays)) {
                        this.$refs.editModalForm.setValues(this.same_for_week[element]);
                    } else {
                        this.$refs.editModalForm.setValues(element);
                    }

                    this.$refs.editDrawerform.toggle();
                },

                editStore(params) {
                    if (parseInt(this.sameSlotAllDays)) {
                        let foundIndex = this.same_for_week.findIndex(item => item.id === params.id);
                        
                        if (foundIndex !== -1) {
                            this.same_for_week[foundIndex] = { 
                                ...this.same_for_week[foundIndex], 
                                ...params
                            };
                        }
                    } else {
                        this.different_for_week.forEach(element => {
                            if (element.hasOwnProperty(this.currentWeek)) {
                                element[this.currentWeek][this.currentTime] = params
                            }
                        });
                    }

                    this.$refs.editDrawerform.toggle();
                },

                removeSlot(element) {
                    if (parseInt(this.sameSlotAllDays)) {

                    } else {
                        this.different_for_week.forEach(element => {
                            if (element.hasOwnProperty(this.currentWeek)) {
                                element[this.currentWeek][this.currentTime] = {id: element.id, from: '00:00', to: '00:00'}
                            }
                        });
                    }
                },

                slotData(params) {
                    let item = [];

                    if (parseInt(this.sameSlotAllDays)) {
                        Object.keys(params).forEach(key => {
                            let matches = key.match(/booking\[slots\]\[(\d+)\]\[(from|to|id)\]/);

                            if (params[key] == '[object Object]') {
                                this.optionRowCount++;
                                params[key] = 'option_' + this.optionRowCount;
                            }

                            if (matches) {
                                let index = parseInt(matches[1]);

                                if (! item[index]) {
                                    item[index] = {};
                                }

                                item[index][matches[2]] = params[key];
                            }
                        });

                        if (this.same_for_week?.length) {
                            this.same_for_week = this.same_for_week.concat(item);
                        } else {
                            this.same_for_week = item;
                        }

                        this.slots.same_for_week = [];
                    } else {
                        let updatedData = '';

                        for (const key in params) {
                            const matches = key.match(/\[(\d+)\]\[(\d+)\]\[(.+)\]/);
                            const slotIndex = matches[1];
                            const timeIndex = matches[2];
                            const prop = matches[3];

                            if (! this.different_for_week[slotIndex]) {
                                this.different_for_week[slotIndex] = [];
                            }

                            if (! this.different_for_week[slotIndex][timeIndex]) {
                                this.different_for_week[slotIndex][timeIndex] = {};
                            }

                            this.different_for_week[slotIndex][timeIndex][prop] = params[key];
                        }

                        updatedData = this.different_for_week.map((item, index) => {
                            let day = this.week_days[index];

                            let updatedItem = {};

                            updatedItem[day] = item;

                            return updatedItem;
                        });

                        this.different_for_week = updatedData;
                    }
                },
            },
        });

        app.component('v-slot-item', {
            template: '#v-slot-item-template',
    
            props: ['controlName', 'slotItem', 'id'],

            methods: {
                remove() {
                    this.$emit('onRemoveSlot', this.slotItem)
                },
            }
        });
    </script>
@endpushOnce