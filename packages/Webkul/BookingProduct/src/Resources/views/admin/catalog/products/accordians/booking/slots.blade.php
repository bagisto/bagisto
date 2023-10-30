<script type="text/x-template" id="slot-list-template">
    <div>
        <div class="slot-list table" v-if="parseInt(sameSlotAllDays)">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('bookingproduct::app.admin.catalog.products.from') }}</th>
                        <th>{{ __('bookingproduct::app.admin.catalog.products.to') }}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>

                <tbody>
                    <slot-item
                        v-for="(slot, index) in slots['same_for_week']"
                        :key="index"
                        :slot-item="slot"
                        :control-name="'booking[slots][' + index + ']'"
                        @onRemoveSlot="removeSlot($event)">
                    </slot-item>
                </tbody>
            </table>

            <button type="button" class="btn btn-lg btn-primary" style="margin-top: 20px" @click="addSlot()">
                {{ __('bookingproduct::app.admin.catalog.products.add-slot') }}
            </button>
        </div>

        <div class="slot-list table" v-else>
            <accordian
                v-for="(day, dayIndex) in week_days"
                :key="dayIndex"
                :title="day"
                :active="false">

                <div slot="header">
                    <i class="icon expand-icon left"></i>
                    <h1>@{{ day }}</h1>
                </div>

                <div slot="body">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('bookingproduct::app.admin.catalog.products.from') }}</th>
                                <th>{{ __('bookingproduct::app.admin.catalog.products.to') }}</th>
                                <th class="actions"></th>
                            </tr>
                        </thead>

                        <tbody v-if="slots['different_for_week'][dayIndex] && slots['different_for_week'][dayIndex].length">
                            <slot-item
                                v-for="(slot, slotIndex) in slots['different_for_week'][dayIndex]"
                                :key="dayIndex + '_' + slotIndex"
                                :slot-item="slot"
                                :control-name="'booking[slots][' + dayIndex + '][' + slotIndex + ']'"
                                @onRemoveSlot="removeSlot($event, dayIndex)"
                            ></slot-item>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-lg btn-primary" style="margin-top: 20px" @click="addSlot(dayIndex)">
                        {{ __('bookingproduct::app.admin.catalog.products.add-slot') }}
                    </button>
                </div>
            </accordian>
        </div>
    </div>
</script>

<script type="text/x-template" id="slot-item-template">
    <tr>
        <td>
            <div class="control-group date" :class="[errors.has(controlName + '[from]') ? 'has-error' : '']">
            
                <time-component>
                    <input type="text" v-validate="'required'" :name="controlName + '[from]'" v-model="slotItem.from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.from') }}&quot;">
                </time-component>

                <span class="control-error" v-if="errors.has(controlName + '[from]')">
                    @{{ errors.first(controlName + '[from]') }}
                </span>
            </div>
        </td>

        <td>
            <div class="control-group date" :class="[errors.has(controlName + '[to]') ? 'has-error' : '']">
                
                <time-component>
                    <input type="text" v-validate="{required: true, time_min: slotItem.from }" :name="controlName + '[to]'" v-model="slotItem.to" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.to') }}&quot;">
                </time-component>

                <span class="control-error" v-if="errors.has(controlName + '[to]')">
                    @{{ errors.first(controlName + '[to]') }}
                </span>
            </div>
        </td>

        <td>
            <i class="icon remove-icon" @click="removeSlot()"></i>
        </td>
    </tr>
</script>

<script>
    Vue.component('slot-list', {

        template: '#slot-list-template',

        props: ['bookingType', 'sameSlotAllDays'],

        inject: ['$validator'],

        data: function() {
            return {
                slots: {
                    'same_for_week': [],

                    'different_for_week': [[], [], [], [], [], [], []]
                },

                week_days: [
                    "{{ __('bookingproduct::app.admin.catalog.products.sunday') }}",
                    "{{ __('bookingproduct::app.admin.catalog.products.monday') }}",
                    "{{ __('bookingproduct::app.admin.catalog.products.tuesday') }}",
                    "{{ __('bookingproduct::app.admin.catalog.products.wednesday') }}",
                    "{{ __('bookingproduct::app.admin.catalog.products.thursday') }}",
                    "{{ __('bookingproduct::app.admin.catalog.products.friday') }}",
                    "{{ __('bookingproduct::app.admin.catalog.products.saturday') }}"
                ]
            }
        },

        created: function() {
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
            addSlot: function (dayIndex = null) {
                if (dayIndex !== null) {
                    if (this.slots['different_for_week'][dayIndex] == undefined) {
                        this.slots['different_for_week'][dayIndex] = [];
                    }

                    var slot = {
                        'from': '',
                        'to': ''
                    };

                    this.slots['different_for_week'][dayIndex].push(slot)

                    Vue.set(this.slots['different_for_week'], dayIndex, this.slots['different_for_week'][dayIndex])
                } else {
                    var slot = {
                        'from': '',
                        'to': ''
                    };

                    this.slots['same_for_week'].push(slot);
                }
            },

            removeSlot: function(slot, dayIndex = null) {
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

    Vue.component('slot-item', {

        template: '#slot-item-template',

        props: ['slotItem', 'controlName'],

        inject: ['$validator'],

        methods: {
            removeSlot: function() {
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