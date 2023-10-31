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

                        <div v-if="ticket.original_formatted_price" class="ticket-price">
                            <span class="regular-price">@{{ ticket.original_formatted_price }}</span>
                            <span class="special-price">@{{ ticket.formatted_price_text }}</span>
                        </div>
                        <div v-else class="ticket-price">
                            @{{ ticket.formatted_price_text }}
                        </div>
                    </div>

                    <div class="ticket-quantity qty">
                        <quantity-changer
                            :control-name="'booking[qty][' + ticket.id + ']'"
                            :validations="'required|numeric|min_value:0'"
                            :quantity="defaultQty"
                            :min-quantity="defaultQty">
                        </quantity-changer>
                    </div>
                    
                    <div class="ticket-item">
                        <p>@{{ ticket.description }}</p>
                    </div>
                </div>
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
            },

            computed: {
                defaultQty: function() {
                    return this.tickets.length > 1 ? 0 : 1;
                }
            }
        });

    </script>

    <style>
        .ticket-price .regular-price{
            color: #a5a5a5;
            text-decoration: line-through;
            margin-right: 5px;
        }
        .ticket-price .special-price {
            color: #ff6472;
            font-size: larger;
        }
    </style>
@endpush