@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\EventTicket')

<div class="booking-info-row">
    <span class="icon bp-slot-icon"></span>
    <span class="title">
        {{ __('bookingproduct::app.shop.products.event-on') }}
    </span>
    <span class="value">
        {!! $bookingSlotHelper->getEventDate($bookingProduct) !!}
    </span>
</div>

<event-tickets></event-tickets>

@push('scripts')

    <script type="text/x-template" id="event-tickets-template">
        <div class="book-slots">
            <label style="font-weight: 600">{{ __('bookingproduct::app.shop.products.book-your-ticket') }}</label>

            <div class="ticket-list">
                <div class="ticket-item" v-for="(ticket, index) in tickets">
                    <div class="ticket-info">
                        <div class="ticket-name">
                            @{{ ticket.name }}
                        </div>

                        <div class="ticket-price">
                            @{{ ticket.formated_price_text }}
                        </div>
                    </div>

                    <div class="ticket-quantity">
                        <div class="control-group" :class="[errors.has('booking[qty][' + ticket.id + ']') ? 'has-error' : '']">
                            <label class="required">{{ __('bookingproduct::app.shop.products.number-of-tickets') }}</label>
                            <input type="text" v-validate="'required'" :name="'booking[qty][' + ticket.id + ']'" class="control" value="0" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.number-of-tickets') }}&quot;" style="width: 100%"/>
                            <span class="control-error" v-if="errors.has('booking[qty][' + ticket.id + ']')">@{{ errors.first('booking[qty][' + ticket.id + ']') }}</span>
                        </div>
                    </div>

                    <p>@{{ ticket.description }}</p>
                </div>
            </div>

            <div class="ticket-total">
                <!--<div class="total-tickets">
                    {{ __('bookingproduct::app.shop.products.total-tickets') }} - 1
                </div>-->

                <div class="ticket-base-price">
                    {{ __('bookingproduct::app.shop.products.base-price') }} - $100
                    
                    <p>{{ __('bookingproduct::app.shop.products.base-price-info') }}</p>
                </div>

                <!--<div class="ticket-total-price">
                    {{ __('bookingproduct::app.shop.products.total-price') }} - $100
                </div>-->
            </div>
        </div>
    </script>

    <script>

        Vue.component('event-tickets', {

            template: '#event-tickets-template',

            inject: ['$validator'],

            data: function() {
                return {
                    tickets: @json($bookingSlotHelper->getTickets($bookingProduct)),
                }
            }
        });

    </script>

@endpush