{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.appointment.before', ['product' => $product]) !!}

<appointment-booking></appointment-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.appointment.after', ['product' => $product]) !!}

@push('scripts')
    @parent

    <script type="text/x-template" id="appointment-booking-template">
        <div>
            <div class="control-group" :class="[errors.has('booking[duration]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.slot-duration') }}</label>

                <input type="text" v-validate="'required|min_value:1'" name="booking[duration]" v-model="appointment_booking.duration" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.slot-duration') }}&quot;"/>
                
                <span class="control-error" v-if="errors.has('booking[duration]')">@{{ errors.first('booking[duration]') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('booking[break_time]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.break-time') }}</label>

                <input type="text" v-validate="'required|min_value:1'" name="booking[break_time]" v-model="appointment_booking.break_time" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.break-time') }}&quot;"/>
                
                <span class="control-error" v-if="errors.has('booking[break_time]')">@{{ errors.first('booking[break_time]') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('booking[same_slot_all_days]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.same-slot-all-days') }}</label>

                <select v-validate="'required'" name="booking[same_slot_all_days]" v-model="appointment_booking.same_slot_all_days" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.same-slot-all-days') }}&quot;">
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
                        booking-type="appointment_slot"
                        :same-slot-all-days="appointment_booking.same_slot_all_days">
                    </slot-list>
                
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('appointment-booking', {

            template: '#appointment-booking-template',

            inject: ['$validator'],

            data: function() {
                return {
                    appointment_booking: bookingProduct && bookingProduct.appointment_slot ? bookingProduct.appointment_slot : {
                        duration: 45,

                        break_time: 15,

                        same_slot_all_days: 1,

                        slots: []
                    }
                }
            }
        });
    </script>
@endpush