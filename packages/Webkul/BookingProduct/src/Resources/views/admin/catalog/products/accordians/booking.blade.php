{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.before', ['product' => $product]) !!}

<accordian :title="'{{ __('bookingproduct::app.admin.catalog.products.booking') }}'" :active="true">
    <div slot="body">

        <booking-information></booking-information>

    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.after', ['product' => $product]) !!}

@push('scripts')
    <?php $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id) ?>

    @parent

    <script type="text/x-template" id="booking-information-template">
        <div>

            <div class="control-group" :class="[errors.has('booking[type]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.booking-type') }}</label>

                <select v-validate="'required'" name="booking[type]" v-model="booking_type" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.booking-type') }}&quot;">
                    <option value="default">{{ __('bookingproduct::app.admin.catalog.products.default') }}</option>
                    <option value="appointment">{{ __('bookingproduct::app.admin.catalog.products.appointment-booking') }}</option>
                    <option value="event">{{ __('bookingproduct::app.admin.catalog.products.event-booking') }}</option>
                    <option value="rental">{{ __('bookingproduct::app.admin.catalog.products.rental-booking') }}</option>
                    <option value="table">{{ __('bookingproduct::app.admin.catalog.products.table-booking') }}</option>
                </select>
                
                <span class="control-error" v-if="errors.has('booking[type]')">@{{ errors.first('booking[type]') }}</span>
            </div>
        
            <div class="control-group">
                <label>{{ __('bookingproduct::app.admin.catalog.products.location') }}</label>
                <input type="text" name="booking[location]" v-model="location" class="control"/>
            </div>

            <div v-if="booking_type == 'appointment' || booking_type == 'event' || booking_type == 'rental' || booking_type == 'table'">
                <div class="control-group" v-if="booking_type != 'event'" :class="[errors.has('booking[available_every_week]') ? 'has-error' : '']">
                    <label class="required">{{ __('bookingproduct::app.admin.catalog.products.available-every-week') }}</label>

                    <select v-validate="'required'" name="booking[available_every_week]" v-model="available_every_week" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.available-every-week') }}&quot;">
                        <option value="1">{{ __('bookingproduct::app.admin.catalog.products.yes') }}</option>
                        <option value="0">{{ __('bookingproduct::app.admin.catalog.products.no') }}</option>
                    </select>
                    
                    <span class="control-error" v-if="errors.has('booking[available_every_week]')">@{{ errors.first('booking[available_every_week]') }}</span>
                </div>

                <div v-if="! parseInt(available_every_week)">
                    <div class="control-group" :class="[errors.has('booking[available_from]') ? 'has-error' : '']">
                        <label class="required">{{ __('bookingproduct::app.admin.catalog.products.available-from') }}</label>

                        <date>
                            <input type="text" v-validate="'required'" name="booking[available_from]" v-model="available_from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.available-from') }}&quot;"/>
                        </date>
                        
                        <span class="control-error" v-if="errors.has('booking[available_from]')">@{{ errors.first('booking[available_from]') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('booking[available_to]') ? 'has-error' : '']">
                        <label class="required">{{ __('bookingproduct::app.admin.catalog.products.available-to') }}</label>

                        <date>
                            <input type="text" v-validate="'required'" name="booking[available_to]" v-model="available_to" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.available-to') }}&quot;"/>
                        </date>
                        
                        <span class="control-error" v-if="errors.has('booking[available_to]')">@{{ errors.first('booking[available_to]') }}</span>
                    </div>
                </div>
            </div>

            <div class="default-booking-section" v-if="booking_type == 'default'">
                @include ('bookingproduct::admin.catalog.products.accordians.booking.default', ['bookingProduct' => $bookingProduct])
            </div>

            <div class="appointment-booking-section" v-if="booking_type == 'appointment'">
                @include ('bookingproduct::admin.catalog.products.accordians.booking.appointment', ['bookingProduct' => $bookingProduct])
            </div>

            <div class="event-booking-section" v-if="booking_type == 'event'">
                @include ('bookingproduct::admin.catalog.products.accordians.booking.event', ['bookingProduct' => $bookingProduct])
            </div>

            <div class="rental-booking-section" v-if="booking_type == 'rental'">
                @include ('bookingproduct::admin.catalog.products.accordians.booking.rental', ['bookingProduct' => $bookingProduct])
            </div>

            <div class="table-booking-section" v-if="booking_type == 'table'">
                @include ('bookingproduct::admin.catalog.products.accordians.booking.table', ['bookingProduct' => $bookingProduct])
            </div>

        </div>
    </script>

    <script>
        var bookingProduct = @json($bookingProduct);

        Vue.component('booking-information', {

            template: '#booking-information-template',

            inject: ['$validator'],

            data: function() {
                return {
                    booking_type: bookingProduct ? bookingProduct.type : 'default',

                    location: '',

                    available_every_week: bookingProduct.available_every_week,

                    available_from: bookingProduct.available_from,

                    available_to: bookingProduct.available_to
                }
            }
        });
    </script>

    @include ('bookingproduct::admin.catalog.products.accordians.booking.slots')

@endpush