{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.event.before', ['product' => $product]) !!}

<event-booking></event-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.event.after', ['product' => $product]) !!}

@push('scripts')
    @parent

    <script type="text/x-template" id="event-booking-template">
        <div>
            <div class="control-group" :class="[errors.has('booking[available_from]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.event-start-date') }}</label>

                <datetime>
                    <input type="text" v-validate="'required'" name="booking[available_from]" v-model="event_booking.available_from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.event-start-date') }}&quot;"/>
                </datetime>
                
                <span class="control-error" v-if="errors.has('booking[available_from]')">@{{ errors.first('booking[available_from]') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('booking[available_to]') ? 'has-error' : '']">
                <label class="required">{{ __('bookingproduct::app.admin.catalog.products.event-end-date') }}</label>

                <datetime>
                    <input type="text" v-validate="'required'" name="booking[available_to]" v-model="event_booking.available_to" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.event-end-date') }}&quot;"/>
                </datetime>
                
                <span class="control-error" v-if="errors.has('booking[available_to]')">@{{ errors.first('booking[available_to]') }}</span>
            </div>

            <div class="section">
                <div class="secton-title">
                    <span>{{ __('bookingproduct::app.admin.catalog.products.tickets') }}</span>
                </div>

                <div class="section-content">

                    <ticket-list :tickets="event_booking.slots"></ticket-list>
                
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="ticket-list-template">
        <div class="ticket-list table">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('bookingproduct::app.admin.catalog.products.name') }}</th>
                        <th>{{ __('bookingproduct::app.admin.catalog.products.price') }}</th>
                        <th>{{ __('bookingproduct::app.admin.catalog.products.quantity') }}</th>
                        <th>{{ __('bookingproduct::app.admin.catalog.products.description') }}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>

                <tbody>
                    <ticket-item
                        v-for="(ticket, index) in tickets"
                        :key="index"
                        :ticket-item="ticket"
                        :control-name="'booking[slots][' + index + ']'"
                        @onRemoveTicket="removeTicket($event)"
                    ></ticket-item>
                </tbody>
            </table>

            <button type="button" class="btn btn-lg btn-primary" style="margin-top: 20px" @click="addTicket()">
                {{ __('bookingproduct::app.admin.catalog.products.add-ticket') }}
            </button>
        </div>
    </script>

    <script type="text/x-template" id="ticket-item-template">
        <tr>
            <td>
                <div class="control-group" :class="[errors.has(controlName + '[name]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" :name="controlName + '[name]'" v-model="ticketItem.name" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.name') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[name]')">
                        @{{ errors.first(controlName + '[name]') }}
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(controlName + '[price]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" :name="controlName + '[price]'" v-model="ticketItem.price" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.price') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[price]')">
                        @{{ errors.first(controlName + '[price]') }}
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(controlName + '[qty]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required|min_value:0'" :name="controlName + '[qty]'" v-model="ticketItem.qty" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.qty') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[qty]')">
                        @{{ errors.first(controlName + '[qty]') }}
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(controlName + '[description]') ? 'has-error' : '']">
                    <textarea type="text" v-validate="'required'" :name="controlName + '[description]'" v-model="ticketItem.description" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.description') }}&quot;"></textarea>

                    <span class="control-error" v-if="errors.has(controlName + '[description]')">
                        @{{ errors.first(controlName + '[description]') }}
                    </span>
                </div>
            </td>

            <td>
                <i class="icon remove-icon" @click="removeTicket()"></i>
            </td>
        </tr>
    </script>

    <script>
        Vue.component('event-booking', {

            template: '#event-booking-template',

            inject: ['$validator'],

            data: function() {
                return {
                    event_booking: bookingProduct && bookingProduct.event_slot ? bookingProduct.event_slot : {
                        available_from: '',

                        available_to: '',

                        slots: []
                    }
                }
            }
        });

        Vue.component('ticket-list', {

            template: '#ticket-list-template',

            props: ['tickets'],

            inject: ['$validator'],

            methods: {
                addTicket: function (dayIndex = null) {
                    this.tickets.push({
                        'name': '',
                        'price': '',
                        'qty': 0,
                        'description': ''
                    });
                },

                removeTicket: function(ticket) {
                    let index = this.tickets.indexOf(ticket)

                    this.tickets.splice(index, 1)
                },
            }
        });

        Vue.component('ticket-item', {

            template: '#ticket-item-template',

            props: ['ticketItem', 'controlName'],

            inject: ['$validator'],

            methods: {
                removeTicket: function() {
                    this.$emit('onRemoveTicket', this.ticketItem)
                },
            }
        });
    </script>

@endpush