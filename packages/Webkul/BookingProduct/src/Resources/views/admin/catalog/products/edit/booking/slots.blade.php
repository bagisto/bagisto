<script type="text/x-template" id="v-slot-list-template">
    <div class="slot-list table" v-if="parseInt(sameSlotAllDays)">
        <div class="flex gap-4 px-4 py-2.5 border-b dark:border-gray-800">
            <v-slot-item
                {{-- v-for="(slot, index) in slots['same_for_week']" --}}
                {{-- :key="index" --}}
                {{-- :slot-item="slot" --}}
                {{-- :control-name="'booking[slots][' + index + ']'" --}}
                {{-- @onRemoveSlot="removeSlot($event)" --}}
            >
            </v-slot-item>
        </div>

        <div class="slot-list table" v-else>
            <v-slot-item
                v-if="slots['different_for_week'][dayIndex] && slots['different_for_week'][dayIndex].length"
                {{-- v-for="(slot, slotIndex) in slots['different_for_week'][dayIndex]" --}}
                {{-- :key="dayIndex + '_' + slotIndex" --}}
                {{-- :slot-item="slot" --}}
                {{-- :control-name="'booking[slots][' + dayIndex + '][' + slotIndex + ']'" --}}
                {{-- @onRemoveSlot="removeSlot($event, dayIndex)" --}}
            ></v-slot-item>
        </div>
    </div>
</script>

<script type="text/x-template" id="v-slot-item-template">
    <div>
        <!-- From Day -->
        <x-admin::form.control-group class="w-full mb-2.5">
            <x-admin::form.control-group.label class="required">
                @lang('booking::app.admin.catalog.products.edit.type.booking.modal.slot.from-day')
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="select"
                name="from_day"
                rules="required"
                :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-all-days.title')"
            >
                <option
                    v-for="(day, index) in days"
                    :value="index"
                    v-text="day"
                >
                </option>
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
</script>

<script typr="module">
    app.component('v-slot-list', {

        template: '#v-slot-list-template',

        props: ['bookingType', 'sameSlotAllDays'],

        data() {
            return {
                slots: {
                    'same_for_week': [],

                    'different_for_week': [[], [], [], [], [], [], []]
                },

                week_days: [
                    "@lang('bookingproduct::app.admin.catalog.products.sunday')",
                    "@lang('bookingproduct::app.admin.catalog.products.monday')",
                    "@lang('bookingproduct::app.admin.catalog.products.tuesday')",
                    "@lang('bookingproduct::app.admin.catalog.products.wednesday')",
                    "@lang('bookingproduct::app.admin.catalog.products.thursday')",
                    "@lang('bookingproduct::app.admin.catalog.products.friday')",
                    "@lang('bookingproduct::app.admin.catalog.products.saturday')"
                ]
            }
        },

        created() {
            if (! bookingProduct || ! bookingProduct[this.bookingType].slots || ! bookingProduct[this.bookingType].slots) {
                return;
            }

            if (bookingProduct[this.bookingType].same_slot_all_days) {
                this.slots['same_for_week'] = bookingProduct[this.bookingType].slots;
            } else {
                this.slots['different_for_week'] = bookingProduct[this.bookingType].slots;
            }
        },

        methods: {
            removeSlot(slot, dayIndex = null) {
                if (dayIndex != null) {
                    let index = this.slots['different_for_week'][dayIndex].indexOf(slot)

                    this.slots['different_for_week'][dayIndex].splice(index, 1)
                } else {
                    let index = this.slots['same_for_week'].indexOf(slot)

                    this.slots['same_for_week'].splice(index, 1)
                }
            },
        }
    });

    app.component('v-slot-item', {

        template: '#v-slot-item-template',

        props: ['slotItem', 'controlName'],

        methods: {
            removeSlot() {
                this.$emit('onRemoveSlot', this.slotItem)
            },
        }
    });

    const time_validator = {
        getMessage: (field) => {
            return "{{ __('bookingproduct::app.admin.catalog.products.time-error') }}"
        },

        validate: (value, min) => {
            if (Array.isArray(value) || value === null || value === undefined || value === '') {
                return false;
            }

            return value > min;
        }
    };

    VeeValidate.Validator.extend('time_min', time_validator);
</script>