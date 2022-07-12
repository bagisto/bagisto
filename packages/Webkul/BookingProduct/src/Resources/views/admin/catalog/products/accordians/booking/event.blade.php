{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.event.before', ['product' => $product]) !!}

<event-booking></event-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.event.after', ['product' => $product]) !!}

@push('scripts')
    <script type="text/x-template" id="event-booking-template">
        <div>
            <div class="section">
                <div class="secton-title">
                    <span>{{ __('bookingproduct::app.admin.catalog.products.tickets') }}</span>
                </div>

                <div class="section-content">
                    <ticket-list :tickets="tickets"></ticket-list>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="ticket-list-template">
        <div class="ticket-list table">
            <table>
                <tbody>
                    <ticket-item
                        v-for="(ticket, index) in tickets"
                        :key="index"
                        :index="index"
                        :ticket-item="ticket"
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
                <div class="control-group" :class="[errors.has(controlName + '[{{$locale}}][name]') ? 'has-error' : '']">
                    <label class="ticket-label required">{{ __('bookingproduct::app.admin.catalog.products.name') }}</label>
                    <input type="text" v-validate="'required'" :name="controlName + '[{{$locale}}][name]'" v-model="ticketItem.name" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.name') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[{{$locale}}][name]')">
                        @{{ errors.first(controlName + '[{!!$locale!!}][name]') }}
                    </span>
                </div>

                <div class="control-group" :class="[errors.has(controlName + '[special_price]') ? 'has-error' : '']">
                    <label class="ticket-label">{{ __('bookingproduct::app.admin.catalog.products.special-price') }}</label>
                    <input type="text" v-validate="{decimal: true, min_value:0, ...(ticketItem.price ? {max_value: ticketItem.price} : {})}" :name="controlName + '[special_price]'" v-model="ticketItem.special_price" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.price') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[special_price]')">
                        @{{ errors.first(controlName + '[special_price]') }}
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(controlName + '[price]') ? 'has-error' : '']">
                    <label class="ticket-label required">{{ __('bookingproduct::app.admin.catalog.products.price') }}</label>
                    <input type="text" v-validate="'required|decimal|min_value:0'" :name="controlName + '[price]'" v-model="ticketItem.price" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.price') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[price]')">
                        @{{ errors.first(controlName + '[price]') }}
                    </span>
                </div>

                <div class="control-group date" :class="[errors.has(controlName + '[special_price_from]') ? 'has-error' : '']">
                    <label class="ticket-label">{{ __('bookingproduct::app.admin.catalog.products.special-price-from') }}</label>

                    <datetime>
                        <input type="text" v-validate="'date_format:yyyy-MM-dd HH:mm:ss|after:{{\Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59')}}'" :name="controlName + '[special_price_from]'" v-model="ticketItem.special_price_from" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.special-price-from') }}&quot;" ref="special_price_from" style="width:100%"/>
                    </datetime>

                    <span class="control-error" v-if="errors.has(controlName + '[special_price_from]')">@{{ errors.first(controlName + '[special_price_from]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(controlName + '[qty]') ? 'has-error' : '']">
                    <label class="ticket-label">{{ __('bookingproduct::app.admin.catalog.products.quantity') }}</label>
                    <input type="text" v-validate="'required|min_value:0'" :name="controlName + '[qty]'" v-model="ticketItem.qty" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.qty') }}&quot;">

                    <span class="control-error" v-if="errors.has(controlName + '[qty]')">
                        @{{ errors.first(controlName + '[qty]') }}
                    </span>
                </div>

                <div class="control-group date" :class="[errors.has(controlName + '[special_price_to]') ? 'has-error' : '']">
                    <label class="ticket-label">{{ __('bookingproduct::app.admin.catalog.products.special-price-to') }}</label>

                    <datetime>
                        <input type="text" v-validate="'date_format:yyyy-MM-dd HH:mm:ss|after:special_price_from'" :name="controlName + '[special_price_to]'" v-model="ticketItem.special_price_to" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.special-price-to') }}&quot;" ref="special_price_to" style="width:100%"/>
                    </datetime>

                    <span class="control-error" v-if="errors.has(controlName + '[special_price_to]')">@{{ errors.first(controlName + '[special_price_to]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(controlName + '[{{$locale}}][description]') ? 'has-error' : '']">
                    <label class="ticket-label required">{{ __('bookingproduct::app.admin.catalog.products.description') }}</label>
                    <textarea type="text" v-validate="'required'" :name="controlName + '[{{$locale}}][description]'" v-model="ticketItem.description" class="control" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.description') }}&quot;"></textarea>

                    <span class="control-error" v-if="errors.has(controlName + '[{{$locale}}][description]')">
                        @{{ errors.first(controlName + '[{!!$locale!!}][description]') }}
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
                    tickets: @json($bookingProduct ? $bookingProduct->event_tickets()->get() : [])
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
                        'description': '',
                        'special_price': '',
                        'special_price_from': '',
                        'special_price_to': '',
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

            props: ['index', 'ticketItem'],

            inject: ['$validator'],

            computed: {
                controlName: function () {
                    if (this.ticketItem.id) {
                        return 'booking[tickets][' + this.ticketItem.id + ']';
                    }

                    return 'booking[tickets][ticket_' + this.index + ']';
                }
            },

            methods: {
                removeTicket: function() {
                    this.$emit('onRemoveTicket', this.ticketItem)
                },
            }
        });
    </script>

    <style>
        .ticket-label {
            font-weight: 700;
            margin: 20px 0 10px 0;
        }
    </style>
@endpush
