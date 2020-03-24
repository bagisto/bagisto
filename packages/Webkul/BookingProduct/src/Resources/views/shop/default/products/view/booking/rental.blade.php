<rental-slots></rental-slots>

@push('scripts')
    <script type="text/x-template" id="rental-slots-template">
        <div class="book-slots">
            <h3>{{ __('bookingproduct::app.shop.products.rent-an-item') }}</h3>

            <div v-if="renting_type == 'daily_hourly'">
                <div class="control-group">
                    <label>{{ __('bookingproduct::app.shop.products.choose-rent-option') }}</label>

                    <span class="radio">
                        <input type="radio" id="daily-renting-type" name="booking[renting_type]" value="daily" v-model="sub_renting_type">
                        <label class="radio-view" for="daily-renting-type"></label>
                        {{ __('bookingproduct::app.shop.products.daily-basis') }}
                    </span>

                    <span class="radio">
                        <input type="radio" id="hourly-renting-type" name="booking[renting_type]" value="hourly" v-model="sub_renting_type">
                        <label class="radio-view" for="hourly-renting-type"></label>
                        {{ __('bookingproduct::app.shop.products.hourly-basis') }}
                    </span>
                    
                </div>
            </div>
            
            <div v-if="renting_type != 'daily' && sub_renting_type == 'hourly'">
                
                <div>
                    <label>{{ __('bookingproduct::app.shop.products.select-slot') }}</label>

                    <div class="control-group-container">
                        <div class="control-group date" :class="[errors.has('booking[date]') ? 'has-error' : '']">
                            <date @onChange="dateSelected($event)">
                                <input type="text" v-validate="'required'" name="booking[date]" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.date') }}&quot;" placeholder="{{ __('bookingproduct::app.shop.products.select-date') }}" data-min-date="today"/>
                            </date>

                            <span class="control-error" v-if="errors.has('booking[date]')">@{{ errors.first('booking[date]') }}</span>
                        </div>

                        <div class="control-group slots" :class="[errors.has('booking[slot]') ? 'has-error' : '']">
                            <select v-validate="'required'" name="booking[slot]" v-model="selected_slot" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.slot') }}&quot;">
                                <option value="">{{ __('bookingproduct::app.shop.products.select-time-slot') }}</option>
                                <option v-for="(slot, index) in slots" :value="index">@{{ slot.time }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('booking[slot]')">@{{ errors.first('booking[slot]') }}</span>
                        </div>
                    </div>
                </div>

                <div v-if="slots[selected_slot] && slots[selected_slot]['slots'].length">
                    <label>{{ __('bookingproduct::app.shop.products.select-rent-time') }}</label>

                    <div class="control-group-container">
                        <div class="control-group slots" :class="[errors.has('booking[slot][from]') ? 'has-error' : '']">
                            <select v-validate="'required'" name="booking[slot][from]" v-model="slot_from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.slot') }}&quot;">
                                <option value="">{{ __('bookingproduct::app.shop.products.select-time-slot') }}</option>

                                <option v-for="slot in slots[selected_slot]['slots']" :value="slot.from_timestamp">
                                    @{{ slot.from }}
                                </option>
                            </select>

                            <span class="control-error" v-if="errors.has('booking[slot][from]')">@{{ errors.first('booking[slot][from]') }}</span>
                        </div>
                        
                        <div class="control-group slots" :class="[errors.has('booking[slot][to]') ? 'has-error' : '']">
                            <select v-validate="'required'" name="booking[slot][to]" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.slot') }}&quot;">
                                <option value="">{{ __('bookingproduct::app.shop.products.select-time-slot') }}</option>

                                <option v-for="slot in slots[selected_slot]['slots']" :value="slot.to_timestamp" v-if="slot_from < slot.to_timestamp">
                                    @{{ slot.to }}
                                </option>
                            </select>

                            <span class="control-error" v-if="errors.has('booking[slot][to]')">@{{ errors.first('booking[slot][to]') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <label>{{ __('bookingproduct::app.shop.products.select-date') }}</label>

                <div class="control-group-container">
                    <div class="control-group date" :class="[errors.has('booking[date_from]') ? 'has-error' : '']">
                        <date @onChange="dateSelected($event)">
                            <input type="text" v-validate="'required|date_format:yyyy-MM-dd|before_or_equal:date_to'" name="booking[date_from]" v-model="date_from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.from') }}&quot;" placeholder="{{ __('bookingproduct::app.shop.products.from') }}" ref="date_from" data-min-date="today"/>
                        </date>

                        <span class="control-error" v-if="errors.has('booking[date_from]')">@{{ errors.first('booking[date_from]') }}</span>
                    </div>

                    <div class="control-group date" :class="[errors.has('booking[date_to]') ? 'has-error' : '']">
                        <date @onChange="dateSelected($event)">
                            <input type="text" v-validate="'required|date_format:yyyy-MM-dd|after_or_equal:date_from'" name="booking[date_to]" v-model="date_to" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.to') }}&quot;" placeholder="{{ __('bookingproduct::app.shop.products.to') }}" ref="date_to" data-min-date="today"/>
                        </date>

                        <span class="control-error" v-if="errors.has('booking[date_to]')">@{{ errors.first('booking[date_to]') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
    </script>

    <script>

        Vue.component('rental-slots', {

            template: '#rental-slots-template',

            inject: ['$validator'],

            data: function() {
                return {
                    renting_type: "{{ $bookingProduct->rental_slot->renting_type }}",

                    sub_renting_type: 'hourly',

                    slots: [],

                    selected_slot: '',

                    slot_from: 0,

                    date_from: '',

                    date_to: ''
                }
            },

            created: function() {
                var self = this;

                this.$validator.extend('after_or_equal', {
                    getMessage(field, val) {
                        return 'The "To" must be equal or after "From"';
                    },

                    validate(value, field) {
                        if (! self.date_from) {
                            return true;
                        }

                        var from = new Date(self.date_from);
                        
                        var to = new Date(self.date_to);

                        return from <= to;
                    }
                });

                this.$validator.extend('before_or_equal', {
                    getMessage(field, val) {
                        return 'The "From must be equal or before "To"';
                    },

                    validate(value, field) {
                        if (! self.date_to) {
                            return true;
                        }

                        var from = new Date(self.date_from);
                        
                        var to = new Date(self.date_to);

                        return from <= to;
                    }
                });
            },

            methods: {
                dateSelected: function(date) {
                    var this_this = this;

                    this.$http.get("{{ route('booking_product.slots.index', $bookingProduct->id) }}", {params: {date: date}})
                        .then (function(response) {
                            this_this.selected_slot = '';
                            
                            this_this.slot_from = 0;

                            this_this.slots = response.data.data;
                        })
                        .catch (function (error) {})
                }
            }

        });
        
    </script>
@endpush