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
        <!-- Tickets Component -->
        <div class="flex gap-5 justify-between p-4">
            <div class="flex flex-col gap-2">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.title')
                </p>
            </div>

            <!-- Add Ticket Button -->
            <div class="flex gap-x-1 items-center">
                <div
                    class="secondary-button"
                    @click="$refs.addOptionsRow.toggle()"
                >
                    @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')
                </div>
            </div>
        </div>

        <!-- Add Options Model Form -->
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="modelForm"
        >
            <form
                @submit.prevent="handleSubmit($event, storeTickets)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::modal ref="addOptionsRow">
                    <x-slot:header>
                        <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="grid grid-cols-3 gap-4 px-4 py-2.5">
                            <!-- Name -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.name')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="{{ $currentLocale->code }}[name]"
                                    rules="required"
                                    {{-- v-model="ticketItem.name" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.name')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.name')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="{{ $currentLocale->code }}[name]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Price -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.price')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="price"
                                    rules="required"
                                    {{-- v-model="ticketItem.price" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.price')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.price')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="price"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Quantity -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.qty')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="qty"
                                    required="required|min_value:0"
                                    {{-- v-model="ticketItem.qty" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.qty')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.qty')"
                                >
                                </x-admin::form.control-group.control>
            
                                <x-admin::form.control-group.error 
                                    control-name="qty"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                    
                            <!-- Special Price -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.special-price')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="special_price"
                                    required="{decimal: true, min_value:0, ...(ticketItem.price ? {max_value: ticketItem.price} : {})}"
                                    {{-- v-model="ticketItem.special_price" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.special-price')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="special_price"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                    
                            <!-- Special Price From -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="datetime"
                                    name="special_price_from"
                                    required="date_format:yyyy-MM-dd HH:mm:ss|after:{{\Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59')}}"
                                    {{-- v-model="ticketItem.special_price_from" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')"
                                    ref="special_price_from"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="special_price_from"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                    
                            <!-- Special Price To -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.valid-until')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="datetime"
                                    name="special_price_to"
                                    required="date_format:yyyy-MM-dd HH:mm:ss|after:special_price_from"
                                    {{-- v-model="ticketItem.special_price_to" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.valid-until')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.valid-until')"
                                    ref="special_price_to"
                                >
                                </x-admin::form.control-group.control>
            
                                <x-admin::form.control-group.error 
                                    control-name="special_price_to"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                    
                            <!-- Description -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.description')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="{{ $currentLocale->code }}[description]"
                                    rules="required"
                                    {{-- v-model="ticketItem.description" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.description')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.description')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="{{ $currentLocale->code }}[description]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('booking::app.admin.catalog.products.edit.type.booking.modal.ticket.save')
                        </button>
                    </x-slot:footer>
                </x-admin::modal>
            </form>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-event-booking', {
            template: '#v-event-booking-template',

            data() {
                return {
                    tickets: @json($bookingProduct ? $bookingProduct->event_tickets()->get() : [])
                }
            },

            methods: {
                storeTickets(params) {
                    console.log(params);
                },
            }
        });
    </script>
@endpushOnce
