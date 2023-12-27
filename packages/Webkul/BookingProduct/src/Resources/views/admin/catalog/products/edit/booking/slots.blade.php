@pushOnce('scripts')
    <script type="text/x-template" id="v-slots-template">
        <div class="flex gap-5 justify-between p-4">
            <div class="flex flex-col gap-2">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                </p>
            </div>

            <!-- Add Ticket Button -->
            <div class="flex gap-x-1 items-center">                
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
            <div v-if="slots.same_for_week.length">

            </div>

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
                @submit.prevent="handleSubmit($event, store)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::drawer ref="drawerform">
                    <x-slot:header>
                        <div class="flex justify-between items-center">
                            <p class="my-2.5 text-xl font-medium dark:text-white">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                            </p>

                            <button
                                type="submit"
                                v-if="parseInt(sameSlotAllDays)"
                                class="mr-11 primary-button"
                            >
                                @lang('save')
                            </button>

                            <div
                                class="mr-11 primary-button"
                                v-if="parseInt(sameSlotAllDays)"
                                @click="addSlot()"
                            >
                                @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                            </div>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div v-if="parseInt(sameSlotAllDays)">
                            <div v-if="slots['same_for_week']?.length">
                                <div class="grid grid-cols-3 gap-2.5 mx-2.5 py-2.5 text-white">
                                    <div class="w-full">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                    </div>
                        
                                    <div class="w-full">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                    </div>
                                </div>

                                
                                <template
                                    v-for="(slot, index) in slots['same_for_week']"
                                    :key="index"
                                >
                                    <v-slot-item
                                        :control-name="'booking[slots][' + index + ']'"
                                        @onRemoveSlot="removeSlot($event)"
                                    >
                                    </v-slot-item>
                                </template>
                            </div>

                            <div v-else>
                                <v-empty-info ::type="bookingType"></v-empty-info>
                            </div>
                        </div>

                        <div v-else>
                            <div
                                v-for="(day, dayIndex) in week_days"
                                class="my-2.5 text-white border-b border-slate-300 dark:border-gray-800"
                                :key="dayIndex"
                                :title="day"
                            >
                                <div class="grid grid-cols-2 gap-2.5 pb-2.5">
                                    <div v-text="day"></div>

                                    <span class="primary-button w-fit" @click="addSlot(dayIndex)">
                                        @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                                    </span>
                                </div>

                                <div v-if="slots['different_for_week'][dayIndex] && slots['different_for_week'][dayIndex].length">
                                    <div class="grid grid-cols-3 gap-2.5 mx-2.5 py-2.5">
                                        <div class="w-full">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                                        </div>
                            
                                        <div class="w-full">
                                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                                        </div>
                                    </div>

                                    <v-slot-item
                                        v-for="(slot, slotIndex) in slots['different_for_week'][dayIndex]"
                                        :key="dayIndex + '_' + slotIndex"
                                        class=""
                                        :slot-item="slot"
                                        :control-name="'booking[slots][' + dayIndex + '][' + slotIndex + ']'"
                                        @onRemoveSlot="removeSlot($event, dayIndex)"
                                    >
                                    </v-slot-item>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
            </form>
        </x-booking::form>
    </script>

    <script type="text/x-template" id="v-slot-item-template">
        <div class="grid grid-cols-3 gap-2.5 mx-2.5">
            <!-- From -->
            <x-booking::form.control-group class="w-full mb-2.5">
                <x-booking::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="controlName + '[from]'"
                    ::id="controlName + '[from]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="controlName + '[from]'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>

            <!-- To -->
            <x-booking::form.control-group class="w-full mb-2.5">
                <x-booking::form.control-group.label class="hidden">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')
                </x-booking::form.control-group.label>

                <x-booking::form.control-group.control
                    type="time"
                    ::name="controlName + '[to]'"
                    ::id="controlName + '[to]'"
                    rules="required"
                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.modal.slot.to')"
                >
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.control
                    type="hidden">
                </x-booking::form.control-group.control>

                <x-booking::form.control-group.error 
                    ::control-name="controlName + '[to]'"
                >
                </x-booking::form.control-group.error>
            </x-booking::form.control-group>

            <div>
                <span 
                    class="icon-cross text-2xl p-1.5 cursor-pointer transition-all"
                    @click="removeSlot"
                ></span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-slots', {
            template: '#v-slots-template',

            props: ['bookingType', 'sameSlotAllDays'],

            data() {
                return {
                    slots: {
                        'same_for_week': [],
    
                        'different_for_week': [[], [], [], [], [], [], []]
                    },
    
                    week_days: [
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.sunday') }}",
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.monday') }}",
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.tuesday') }}",
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.wednesday') }}",
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.thursday') }}",
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.friday') }}",
                        "{{ __('booking::app.admin.catalog.products.edit.type.booking.modal.slot.saturday') }}"
                    ],
                }
            },

            created() {
                if (! this.appointment_booking || ! this.appointment_booking[this.bookingType].slots || ! this.appointment_booking[this.bookingType].slots) {
                    return;
                }
    
                if (this.appointment_booking[this.bookingType].same_slot_all_days) {
                    this.slots['same_for_week'] = $this.appointment_booking[this.bookingType].slots;
                } else {
                    this.slots['different_for_week'] = this.appointment_booking[this.bookingType].slots;
                }
            },

            methods: {
                addSlot(dayIndex = null) {
                    if (dayIndex !== null) {
                        if (this.slots['different_for_week'][dayIndex] == undefined) {
                            this.slots['different_for_week'][dayIndex] = [];
                        }
    
                        var slot = {
                            'from': '',
                            'to': ''
                        };
    
                        this.slots['different_for_week'][dayIndex].push(slot)
                    } else {
                        var slot = {
                            'from': '',
                            'to': ''
                        };
    
                        this.slots['same_for_week'].push(slot);
                    }
                },
    
                removeSlot(slot, dayIndex = null) {
                    if (dayIndex != null) {
                        let index = this.slots['different_for_week'][dayIndex].indexOf(slot)
    
                        this.slots['different_for_week'][dayIndex].splice(index, 1)
                    } else {
                        let index = this.slots['same_for_week'].indexOf(slot)
    
                        this.slots['same_for_week'].splice(index, 1)
                    }
                },

                store(params) {
                    const formData = new FormData(this.$refs.createOptionsForm);
                    this.$emit('store', formData.entries());
                }
            },
        });

        app.component('v-slot-item', {
            template: '#v-slot-item-template',
    
            props: ['controlName'],

            methods: {
                removeSlot() {
                    this.$emit('onRemoveSlot', this.slotItem)
                },
            }
        });
    </script>
@endpushOnce