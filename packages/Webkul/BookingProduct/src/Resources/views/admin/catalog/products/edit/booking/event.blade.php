{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-event-booking></v-event-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

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

        <!-- Table Information -->
        <div class="mt-4 overflow-x-auto">
            <template v-if="tickets?.length">
                <x-admin::table>
                    <x-admin::table.thead class="text-sm font-medium dark:bg-gray-800">
                        <x-admin::table.thead.tr>
                            <!-- Name -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.name')
                            </x-admin::table.th>

                            <!-- Price -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.price')
                            </x-admin::table.th>

                            <!-- Qty -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.qty')
                            </x-admin::table.th>

                            <!-- Special Price -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.special-price')
                            </x-admin::table.th>

                            <!-- Valid From -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')
                            </x-admin::table.th>

                            <!-- Valid Until -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.valid-until')
                            </x-admin::table.th>

                            <!-- Description -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.description')
                            </x-admin::table.th>

                            <!-- Action tables heading -->
                            <x-admin::table.th>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.event.action')
                            </x-admin::table.th>
                        </x-admin::table.thead.tr>
                    </x-admin::table.thead>

                    <x-admin::table.tbody.tr v-for="(element, index) in tickets">
                        <!-- Name-->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="element.name"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][' + currentLocaleCode + '][name]'"
                                :value="element.name"
                            />
                        </x-admin::table.td>

                        <!-- Price -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="element.price"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][price]'"
                                :value="element.price"
                            />
                        </x-admin::table.td>

                        <!-- Qty -->
                        <x-admin::table.td>
                            <p
                                v-text="element.qty"
                                class="dark:text-white"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][qty]'"
                                :value="element.qty"
                            />
                        </x-admin::table.td>

                        <!-- Special Price -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="element.special_price"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][special_price]'"
                                :value="element.special_price"
                            />
                        </x-admin::table.td>

                        <!-- Valid From -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="element.special_price_from"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][special_price_from]'"
                                :value="element.special_price_from"
                            />
                        </x-admin::table.td>

                        <!-- Valid Until -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="element.special_price_to"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][special_price_to]'"
                                :value="element.special_price_to"
                            />
                        </x-admin::table.td>

                        <!-- Description -->
                        <x-admin::table.td>
                            <p
                                class="dark:text-white"
                                v-text="element.description"
                            >
                            </p>

                            <input
                                type="hidden"
                                :name="'booking[tickets][ticket_' + index + '][' + currentLocaleCode + '][description]'"
                                :value="element.description"
                            />
                        </x-admin::table.td>

                        <!-- Actions button -->
                        <x-admin::table.td class="!px-0">
                            <span
                                class="icon-edit p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="editModal(element)"
                            >
                            </span>

                            <span
                                class="icon-delete p-1.5 rounded-md text-2xl leading-none cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                @click="removeOption(element.id)"
                            >
                            </span>
                        </x-admin::table.td>
                    </x-admin::table.tbody.tr>
                </x-admin::table>
            </template>

            <template v-else>
                <v-empty-info type="event"></v-empty-info>
            </template>
        </div>

        <!-- Add Options Model Form -->
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
            ref="modelForm"
        >
            <form
                @submit.prevent="handleSubmit($event, store)"
                enctype="multipart/form-data"
                ref="createOptionsForm"
            >
                <x-admin::modal ref="addOptionsRow">
                    <x-slot:header>
                        <p class="text-gray-800 dark:text-white font-bold">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.tickets.add')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="grid grid-cols-3 gap-4 px-4 py-2.5">
                            <!-- ID -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            >
                            </x-admin::form.control-group.control>

                            <!-- Name -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.name')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.name')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.name')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="name"
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
                                    {{-- required="{decimal: true, min_value:0, ...(ticketItem.price ? {max_value: ticketItem.price} : {})}" --}}
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
                            <x-booking::form.control-group class="mb-2.5">
                                <x-booking::form.control-group.label>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')
                                </x-booking::form.control-group.label>

                                @php
                                    $dateMin = \Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59');
                                @endphp

                                <x-booking::form.control-group.control
                                    type="datetime"
                                    name="special_price_from"
                                    {{-- :rules="'required_if:!value,date,required|after:' . $dateMin" --}}
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.valid-from')"
                                    ref="special_price_from"
                                >
                                </x-booking::form.control-group.control>

                                <x-booking::form.control-group.error 
                                    control-name="special_price_from"
                                >
                                </x-booking::form.control-group.error>
                            </x-booking::form.control-group>

                            <!-- Special Price To -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('booking::app.admin.catalog.products.edit.type.booking.event.valid-until')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="datetime"
                                    name="special_price_to"
                                    {{-- :rules="'after:special_price_from'" --}}
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
                                    name="[description]"
                                    rules="required"
                                    :label="trans('booking::app.admin.catalog.products.edit.type.booking.event.description')"
                                    :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.event.description')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error 
                                    control-name="[description]"
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
                    tickets: @json($bookingProduct ? $bookingProduct->event_tickets()->get() : []),

                    optionRowCount: 0,

                    currentLocaleCode: @json(core()->getCurrentLocale()->code),
                }
            },

            methods: {
                store(params) {
                    if (! params.id) {
                            this.optionRowCount++;
                            params.id = 'option_' + this.optionRowCount;
                        }

                        let foundIndex = this.tickets.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            this.tickets[foundIndex] = { 
                                ...this.tickets[foundIndex].params, 
                                ...params
                            };
                        } else {
                            this.tickets.push(params);
                        }

                    this.$refs.addOptionsRow.toggle();
                },

                editModal(values) {
                    this.$refs.modelForm.setValues(values);

                    this.$refs.addOptionsRow.toggle();
                },

                removeOption(id) {
                    this.tickets = this.tickets.filter(option => option.id !== id);
                },
            }
        });
    </script>
@endpushOnce
