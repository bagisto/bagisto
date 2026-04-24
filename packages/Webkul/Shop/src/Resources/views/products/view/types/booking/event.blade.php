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
    <v-event-tickets
        :base-price="{{ $product->getTypeInstance()->getMinimalPrice() ?? 0 }}"
        :base-regular-price="{{ $product->getTypeInstance()->getRegularMinimalPrice() ?? 0 }}"
    ></v-event-tickets>
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
                class="flex justify-between"
                :class="tickets?.length - index == 1 ? '' : 'border-b border-slate-500 pb-4'"
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
                        ::max-value="ticket.qty || 999"
                        @change="onTicketQuantityChange(ticket, $event)"
                    />
                </div>
            </div>

            <!-- Event Price Breakdown -->
            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-sm">
                <p class="mb-3 font-semibold">
                    @lang('shop::app.products.view.type.booking.event.summary-title')
                </p>

                <template v-if="totalTicketCount > 0">
                    <div
                        class="flex items-center justify-between py-1"
                        v-for="line in ticketLines"
                        :key="line.id"
                    >
                        <span
                            class="text-zinc-600"
                            v-text="line.label"
                        >
                        </span>

                        <span
                            class="font-medium"
                            v-text="line.formattedSubtotal"
                        >
                        </span>
                    </div>

                    <div
                        class="flex items-center justify-between py-1"
                        v-if="Number(basePrice) > 0"
                    >
                        <span
                            class="text-zinc-600"
                            v-text="baseFeeLabel"
                        >
                        </span>

                        <span class="flex items-center gap-2">
                            <span
                                v-if="hasBaseDiscount"
                                class="text-zinc-400 line-through"
                                v-text="formattedBaseFeeRegularTotal"
                            >
                            </span>

                            <span
                                class="font-medium"
                                v-text="formattedBaseFeeTotal"
                            >
                            </span>
                        </span>
                    </div>

                    <div class="mt-3 flex items-center justify-between border-t border-zinc-200 pt-3">
                        <span class="font-semibold">
                            @lang('shop::app.products.view.type.booking.event.total')
                        </span>

                        <span
                            class="text-base font-semibold"
                            v-text="formattedGrandTotal"
                        >
                        </span>
                    </div>
                </template>

                <template v-else>
                    <p class="text-xs text-zinc-500">
                        @lang('shop::app.products.view.type.booking.event.select-tickets-hint')
                    </p>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-event-tickets', {
            template: '#v-event-tickets-template',

            props: ['basePrice', 'baseRegularPrice'],

            data() {
                const tickets = @json($bookingSlotHelper->getTickets($bookingProduct));

                /**
                 * Matches the template's default: if there is only a single ticket type
                 * it is pre-selected with quantity 1, otherwise everything starts at 0.
                 */
                const initialQuantities = {};
                const defaultQty = tickets.length > 1 ? 1 : 0;

                tickets.forEach(ticket => {
                    initialQuantities[ticket.id] = defaultQty;
                });

                return {
                    tickets,
                    selectedQuantities: initialQuantities,
                };
            },

            computed: {
                selectedEntries() {
                    return this.tickets
                        .map(ticket => ({
                            ticket,
                            qty: Number(this.selectedQuantities[ticket.id] ?? 0),
                        }))
                        .filter(entry => entry.qty > 0);
                },

                totalTicketCount() {
                    return this.selectedEntries.reduce((sum, entry) => sum + entry.qty, 0);
                },

                ticketLines() {
                    return this.selectedEntries.map(({ ticket, qty }) => {
                        const unitPrice = Number(ticket.converted_price ?? 0);
                        const subtotal = unitPrice * qty;

                        const label = "@lang('shop::app.products.view.type.booking.event.ticket-line')"
                            .replace(':name', ticket.name)
                            .replace(':count', qty)
                            .replace(':price', this.$shop.formatPrice(unitPrice));

                        return {
                            id: ticket.id,
                            label,
                            formattedSubtotal: this.$shop.formatPrice(subtotal),
                        };
                    });
                },

                baseFeeLabel() {
                    const word = this.totalTicketCount === 1
                        ? "@lang('shop::app.products.view.type.booking.event.ticket')"
                        : "@lang('shop::app.products.view.type.booking.event.tickets')";

                    return "@lang('shop::app.products.view.type.booking.event.base-fee-line')"
                        .replace(':count', `${this.totalTicketCount} ${word}`)
                        .replace(':price', this.$shop.formatPrice(Number(this.basePrice ?? 0)));
                },

                baseFeeTotal() {
                    return Number(this.basePrice ?? 0) * this.totalTicketCount;
                },

                formattedBaseFeeTotal() {
                    return this.$shop.formatPrice(this.baseFeeTotal);
                },

                hasBaseDiscount() {
                    return Number(this.baseRegularPrice ?? 0) > Number(this.basePrice ?? 0);
                },

                baseFeeRegularTotal() {
                    return Number(this.baseRegularPrice ?? 0) * this.totalTicketCount;
                },

                formattedBaseFeeRegularTotal() {
                    return this.$shop.formatPrice(this.baseFeeRegularTotal);
                },

                grandTotal() {
                    const ticketsSubtotal = this.selectedEntries.reduce((sum, entry) => {
                        return sum + (Number(entry.ticket.converted_price ?? 0) * entry.qty);
                    }, 0);

                    return ticketsSubtotal + this.baseFeeTotal;
                },

                formattedGrandTotal() {
                    return this.$shop.formatPrice(this.grandTotal);
                },
            },

            methods: {
                onTicketQuantityChange(ticket, qty) {
                    this.selectedQuantities[ticket.id] = Number(qty) || 0;
                },
            },
        });
    </script>
@endpushOnce