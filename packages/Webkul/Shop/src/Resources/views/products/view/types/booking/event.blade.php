@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\EventTicket')

<div class="grid grid-cols-1 gap-6">
    <div class="flex gap-3">
        <span class="icon-calendar text-2xl"></span>

        <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
            <p class="text-[#6E6E6E]">
                @lang('shop::app.products.view.type.booking.event.title')
            </p>

            <p>
                {!! $bookingSlotHelper->getEventDate($bookingProduct) !!}
            </p>
        </div>
    </div>

    <!-- Event Vue Component -->
    <v-event-tickets></v-event-tickets>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-event-tickets-template"
    >
        <div class="grid grid-cols-1 gap-4">
            <div class="text-xl font-medium max-sm:text-base">
                @lang('shop::app.products.view.type.booking.event.book-your-ticket')
            </div>

            <div
                class="flex justify-between border-b border-slate-500 last:border-b-0"
                :class="tickets?.length - index == 1 ? '' : 'pb-4'"
                v-for="(ticket, index) in tickets"
            >
                <div class="grid gap-1.5">
                    <!-- Name -->
                    <p  
                        class="font-medium max-sm:text-sm"
                        v-text="ticket.name"
                    >
                    </p>

                    <div
                        v-if="ticket.original_formatted_price"
                        class="text-[#6E6E6E] max-sm:text-sm"
                    >
                        <p
                            class="mr-1.5 line-through"
                            v-text="ticket.original_formatted_price"
                        >
                        </p>

                        <p
                            class="text-lg max-sm:text-sm"
                            v-text="ticket.formatted_price_text"
                        >
                        </p>
                    </div>

                    <!-- Formatted Price -->
                    <p
                        v-else
                        v-text="ticket.formatted_price_text"
                    >
                    </p>

                    <!-- Description -->
                    <div v-text="ticket.description"></div>
                </div>

                <div class="place-items-end">
                    <x-shop::quantity-changer
                        class="mt-5 w-max gap-x-4 rounded-xl !border-[#E9E9E9] px-4 py-2.5 max-sm:p-1.5"
                        ::name="'booking[qty][' + ticket.id + ']'"
                        rules="required|numeric|min_value:0"
                        ::value="tickets.length > 1 ? 1 : 0"
                        ::min-value="0"
                    />
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
            }
        });
    </script>
@endpushOnce