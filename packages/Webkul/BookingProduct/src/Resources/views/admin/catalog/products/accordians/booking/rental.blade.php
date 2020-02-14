{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.rental.before', ['product' => $product]) !!}

<rental-booking></rental-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.rental.after', ['product' => $product]) !!}

@push('scripts')
    @parent

    <script type="text/x-template" id="rental-booking-template">
        <div>
            <div class="control-group" :class="[errors.has('booking[renting_type]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.renting-type') }}</label>

                <select v-validate="'required'" name="booking[renting_type]" v-model="rental_booking.renting_type" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.renting-type') }}&quot;">
                    <option value="daily">{{ __('bookingproduct::app.admin.catalog.products.daily') }}</option>
                    <option value="hourly">{{ __('bookingproduct::app.admin.catalog.products.hourly') }}</option>
                    <option value="daily_hourly">{{ __('bookingproduct::app.admin.catalog.products.daily-hourly') }}</option>
                </select>
                
                <span class="control-error" v-if="errors.has('booking[renting_type]')">@{{ errors.first('booking[renting_type]') }}</span>
            </div>

            <div v-if="rental_booking.renting_type == 'daily' || rental_booking.renting_type == 'daily_hourly'">            
                <div class="control-group" :class="[errors.has('booking[daily_price]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.daily-price') }}</label>

                    <input type="text" v-validate="'required'" name="booking[daily_price]" v-model="rental_booking.daily_price" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.daily-price') }}&quot;"/>
                    
                    <span class="control-error" v-if="errors.has('booking[daily_price]')">@{{ errors.first('booking[daily_price]') }}</span>
                </div>
            </div>

            <div v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'">            
                <div class="control-group" :class="[errors.has('booking[hourly_price]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.hourly-price') }}</label>

                    <input type="text" v-validate="'required'" name="booking[hourly_price]" v-model="rental_booking.hourly_price" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.hourly-price') }}&quot;"/>
                    
                    <span class="control-error" v-if="errors.has('booking[hourly_price]')">@{{ errors.first('booking[hourly_price]') }}</span>
                </div>
            </div>

            <div class="control-group" :class="[errors.has('booking[available_every_week]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.available-every-week') }}</label>

                <select v-validate="'required'" name="booking[available_every_week]" v-model="rental_booking.available_every_week" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.available-every-week') }}&quot;">
                    <option value="1">{{ __('bookingproduct::app.admin.catalog.products.yes') }}</option>
                    <option value="0">{{ __('bookingproduct::app.admin.catalog.products.no') }}</option>
                </select>
                
                <span class="control-error" v-if="errors.has('booking[available_every_week]')">@{{ errors.first('booking[available_every_week]') }}</span>
            </div>

            <div v-if="! parseInt(rental_booking.available_every_week)">
                <div class="control-group" :class="[errors.has('booking[available_from]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.available-from') }}</label>

                    <date>
                        <input type="text" v-validate="'required'" name="booking[available_from]" v-model="rental_booking.available_from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.available-from') }}&quot;"/>
                    </date>
                    
                    <span class="control-error" v-if="errors.has('booking[available_from]')">@{{ errors.first('booking[available_from]') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('booking[available_to]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.available-to') }}</label>

                    <date>
                        <input type="text" v-validate="'required'" name="booking[available_to]" v-model="rental_booking.available_to" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.available-to') }}&quot;"/>
                    </date>
                    
                    <span class="control-error" v-if="errors.has('booking[available_to]')">@{{ errors.first('booking[available_to]') }}</span>
                </div>
            </div>

            <div v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'">
                <div class="control-group" :class="[errors.has('booking[slot_has_quantity]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.slot-has-quantity') }}</label>

                    <select v-validate="'required'" name="booking[slot_has_quantity]" v-model="rental_booking.slot_has_quantity" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.slot-has-quantity') }}&quot;">
                        <option value="1">{{ __('bookingproduct::app.admin.catalog.products.yes') }}</option>
                        <option value="0">{{ __('bookingproduct::app.admin.catalog.products.no') }}</option>
                    </select>
                    
                    <span class="control-error" v-if="errors.has('booking[slot_has_quantity]')">@{{ errors.first('booking[slot_has_quantity]') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('booking[same_slot_all_days]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.same-slot-all-days') }}</label>

                    <select v-validate="'required'" name="booking[same_slot_all_days]" v-model="rental_booking.same_slot_all_days" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.same-slot-all-days') }}&quot;">
                        <option value="1">{{ __('bookingproduct::app.admin.catalog.products.yes') }}</option>
                        <option value="0">{{ __('bookingproduct::app.admin.catalog.products.no') }}</option>
                    </select>
                    
                    <span class="control-error" v-if="errors.has('booking[same_slot_all_days]')">@{{ errors.first('booking[same_slot_all_days]') }}</span>
                </div>

                <div class="section">
                    <div class="secton-title">
                        <span>{{ __('bookingproduct::app.admin.catalog.products.slots') }}</span>
                    </div>

                    <div class="section-content">

                        <slot-list
                            booking-type="rental_slot"
                            :same-slot-all-days="rental_booking.same_slot_all_days"
                            :slot-has-quantity="rental_booking.slot_has_quantity">
                        </slot-list>
                    
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('rental-booking', {

            template: '#rental-booking-template',

            inject: ['$validator'],

            data: function() {
                return {
                    rental_booking: bookingProduct && bookingProduct.rental_slot ? bookingProduct.rental_slot : {
                        renting_type: 'daily',

                        daily_price: '',

                        hourly_price: '',

                        available_every_week: 1,

                        available_from: '',

                        available_to: '',

                        same_slot_all_days: 1,
                        
                        slot_has_quantity: 1,

                        slots: []
                    }
                }
            }
        });
    </script>
@endpush