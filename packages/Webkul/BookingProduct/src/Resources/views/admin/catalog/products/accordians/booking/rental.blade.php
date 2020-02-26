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

            <div v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'">
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
                            :same-slot-all-days="rental_booking.same_slot_all_days">
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

                        same_slot_all_days: 1,

                        slots: []
                    }
                }
            }
        });
    </script>
@endpush