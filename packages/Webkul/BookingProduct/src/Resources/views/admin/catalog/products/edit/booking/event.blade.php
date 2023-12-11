{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-event-booking></v-event-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@php
    $currentLocale = core()->getCurrentLocale();
@endphp

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-event-booking-template"
    >
        <div>
            <!-- Ticket List Vue Component -->
            <v-ticket-list :tickets="tickets"></v-ticket-list>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-ticket-list-template"
    >
        <!-- Tickets Component -->
        <div class="flex gap-[20px] justify-between p-[16px]">
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.title')
                </p>
            </div>

            <!-- Add Ticket Button -->
            <div class="flex gap-x-[4px] items-center">
                <div
                    class="secondary-button"
                    @click="addTicket()"
                >
                    @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')
                </div>
            </div>
        </div>

        <div>
            <table>
                <thead>
                    <th>
                        @lang('name')
                    </th>

                    <th>
                        @lang('price')
                    </th>

                    <th>
                        @lang('qty')
                    </th>

                    <th>
                        @lang('special-price')
                    </th>

                    <th>
                        @lang('special-price-from')
                    </th>

                    <th>
                        @lang('special-price-to')
                    </th>

                    <th>
                        @lang('description')
                    </th>

                    <th></th>
                </thead>

                <tbody>
                    <v-ticket-item
                        v-for="(ticket, index) in tickets"
                        :key="index"
                        :index="index"
                        :ticket-item="ticket"
                        @onRemoveTicket="removeTicket($event)"
                    ></v-ticket-item>
                </tbody>
            </table>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-ticket-item-template"
    >
        <tr>
            <td>
                <!-- Name -->
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.name')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="{{ $currentLocale->code }}[name]"
                        v-model="ticketItem.name"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.name')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.name')"
                    >
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error 
                        control-name="{{ $currentLocale->code }}[name]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <td>
                <!-- Price -->
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.price')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="price"
                        v-model="ticketItem.price"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.price')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.price')"
                    >
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error 
                        control-name="price"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <td>
                <!-- Quantity -->
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.qty')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="qty"
                        required="required|min_value:0"
                        v-model="ticketItem.qty"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.qty')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.qty')"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="qty"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <!-- Special Price -->
            <td>
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.special-price')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="special_price"
                        required="{decimal: true, min_value:0, ...(ticketItem.price ? {max_value: ticketItem.price} : {})}"
                        v-model="ticketItem.special_price"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price')"
                    >
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error 
                        control-name="special_price"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <!-- Special Price From -->
            <td>
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.special-price-from')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="special_price_from"
                        required="date_format:yyyy-MM-dd HH:mm:ss|after:{{\Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59')}}"
                        v-model="ticketItem.special_price_from"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price-from')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price-from')"
                        ref="special_price_from"
                    >
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error 
                        control-name="special_price_from"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <!-- Special Price To -->
            <td>
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.special-price-to')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="special_price_to"
                        required="date_format:yyyy-MM-dd HH:mm:ss|after:special_price_from"
                        v-model="ticketItem.special_price_to"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price-to')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price-to')"
                        ref="special_price_to"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="special_price_to"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <!-- Description -->
            <td>
                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.label>
                        @lang('booking::app.admin.catalog.products.edit.type.booking.event.description')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="textarea"
                        name="{{ $currentLocale->code }}[description]"
                        rules="required"
                        v-model="ticketItem.description"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.description')"
                        :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.description')"
                    >
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error 
                        control-name="{{ $currentLocale->code }}[description]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>
            </td>

            <td>
                <i class="icon remove-icon" @click="removeTicket()"></i>
            </td>
        </tr> 
    </script>

    <script type="module">
        app.component('v-event-booking', {
            template: '#v-event-booking-template',

            data() {
                return {
                    tickets: @json($bookingProduct ? $bookingProduct->event_tickets()->get() : [])
                }
            }
        });

        app.component('v-ticket-list', {
            template: '#v-ticket-list-template',

            props: ['tickets'],

            methods: {
                addTicket(dayIndex = null) {
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

                removeTicket(ticket) {
                    let index = this.tickets.indexOf(ticket)

                    this.tickets.splice(index, 1)
                },
            }
        });

        app.component('v-ticket-item', {
            template: '#v-ticket-item-template',

            props: ['index', 'ticketItem'],

            computed() {
                if (this.ticketItem.id) {
                    return 'booking[tickets][' + this.ticketItem.id + ']';
                }

                return 'booking[tickets][ticket_' + this.index + ']';
            },

            methods: {
                removeTicket() {
                    this.$emit('onRemoveTicket', this.ticketItem);
                },
            }
        });
    </script>
@endpushOnce
