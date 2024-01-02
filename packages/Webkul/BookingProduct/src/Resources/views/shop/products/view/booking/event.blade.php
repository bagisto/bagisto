@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\EventTicket')

<span class="value">
    {!! $bookingSlotHelper->getEventDate($bookingProduct) !!}
</span>

<!-- Event Vue Component -->
<v-event-tickets></v-event-tickets>

@pushOnce('scripts')
    <script type="text/x-template" id="v-event-tickets-template">
        <div class="book-slots">
            <label style="font-weight: 600">
                @lang('booking::app.shop.products.book-your-ticket')
            </label>

            <div class="ticket-list">
                <div class="ticket-item" v-for="(ticket, index) in tickets">
                    <div class="ticket-info">
                        <div class="ticket-name">
                            @{{ ticket.name }}
                        </div>

                        <div
                            v-if="ticket.original_formatted_price"
                            class="ticket-price"
                        >
                            <span class="mr-1.5 line-through">
                                @{{ ticket.original_formatted_price }}
                            </span>

                            <span class="text-lg">
                                @{{ ticket.formatted_price_text }}
                            </span>
                        </div>

                        <div v-else class="ticket-price">
                            @{{ ticket.formatted_price_text }}
                        </div>
                    </div>

                    <div class="ticket-quantity qty">                      
                        <x-shop::quantity-changer
                            ::name="'booking[qty][' + ticket.id + ']'"
                            rules="required|numeric|min_value:0"
                            ::value="defaultQty"
                            ::min-quantity="defaultQty"
                            class="gap-x-2.5 w-28 max-h-9 py-1.5 px-3.5 rounded-[10px]"
                        >
                        </x-shop::quantity-changer>
                    </div>
                    
                    <div class="ticket-item">
                        <p>@{{ ticket.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-event-tickets', {
            template: '#v-event-tickets-template',

            data() {
                return {
                    tickets: @json($bookingSlotHelper->getTickets($bookingProduct)),
                }
            },

            computed: {
                defaultQty() {
                    return this.tickets.length > 1 ? 0 : 1;
                }
            }
        });
    </script>
@endpushOnce