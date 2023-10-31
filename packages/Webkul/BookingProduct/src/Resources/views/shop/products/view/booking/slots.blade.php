<book-slots></book-slots>

@push('scripts')
    <script type="text/x-template" id="book-slots-template">

        <div class="book-slots">
            <label class="label-style required">{{ $title ?? __('bookingproduct::app.shop.products.book-an-appointment') }}</label>

            <div class="control-group-container">
                <div class="form-group date" :class="[errors.has('booking[date]') ? 'has-error' : '']">
                    <date @onChange="dateSelected($event)" :minDate="'{{$bookingProduct->available_from}}'" :maxDate="'{{$bookingProduct->available_to}}'">
                        <input type="text" v-validate="'required'" name="booking[date]" class="form-style" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.date') }}&quot;"/>
                    </date>

                    <span class="control-error" v-if="errors.has('booking[date]')">@{{ errors.first('booking[date]') }}</span>
                </div>

                <div class="form-group slots" :class="[errors.has('booking[slot]') ? 'has-error' : '']">
                    <select v-validate="'required'" name="booking[slot]" class="form-style" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.slot') }}&quot;">
                        <option v-for="slot in slots" :value="slot.timestamp">@{{ slot.from + ' - ' + slot.to }}</option>

                        <option value="" v-if="! slots.length">{{ __('bookingproduct::app.shop.products.no-slots-available') }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('booking[slot]')">@{{ errors.first('booking[slot]') }}</span>
                </div>
            </div>
        </div>

    </script>

    <script>

        Vue.component('book-slots', {
            template: '#book-slots-template',

            inject: ['$validator'],

            data: function() {
                return {
                    slots: []
                }
            },

            methods: {
                dateSelected: function(date) {
                    var this_this = this;

                    this.$http.get("{{ route('booking_product.slots.index', $bookingProduct->id) }}", {params: {date: date}})
                        .then (function(response) {
                            this_this.slots = response.data.data;

                            this_this.errors.clear();
                        })
                        .catch (function (error) {})
                }
            }
        });

    </script>
@endpush
