{!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

<v-rental-booking></v-rental-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}

@push('scripts')
    <script
        type="text/x-template"
        id="v-rental-booking-template"
    >
        <x-admin::form
            enctype="multipart/form-data"
            method="PUT"
        >
            <div>
                <!-- Renting Type -->
                <x-admin::form.control-group class="w-full mb-2.5">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[renting_type]"
                        rules="required"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.title')"
                        v-model="rental_booking.renting_type"
                    >
                        @foreach (['daily', 'hourly', 'daily_hourly'] as $item)
                            <option value="{{ $item }}">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.' . $item)
                            </option>
                        @endforeach
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[renting_type]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Daily Price -->
                <x-admin::form.control-group
                    class="w-full mb-2.5"
                    v-if="rental_booking.renting_type == 'daily' || rental_booking.renting_type == 'daily_hourly'"
                >
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[daily_price]"
                        rules="required"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.daily-price')"
                        v-model="rental_booking.daily_price"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[renting_type]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Hourly Price -->
                <x-admin::form.control-group
                    class="w-full mb-2.5"
                    v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'"
                >
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[hourly_price]"
                        rules="required"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.renting-type.hourly-price')"
                        v-model="rental_booking.hourly_price"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[hourly_price]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <div v-if="rental_booking.renting_type == 'hourly' || rental_booking.renting_type == 'daily_hourly'">
                    <!-- Same Slot For All -->
                    <x-admin::form.control-group class="w-full mb-2.5" >
                        <x-admin::form.control-group.label class="required">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="booking[same_slot_all_days]"
                            rules="required"
                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.title')"
                            v-model="rental_booking.same_slot_all_days"
                        >
                            <option value="1">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.yes')
                            </option>

                            <option value="0">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.same-slot-for-all-days.no')
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error 
                            control-name="booking[same_slot_all_days]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <div class="flex gap-5 justify-between p-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @lang('booking::app.admin.catalog.products.edit.type.booking.slots.title')
                            </p>
                        </div>
            
                        <!-- Add Ticket Button -->
                        <div class="flex gap-x-1 items-center">
                            <div
                                class="secondary-button"
                                @click="$refs.addOptionsRow.toggle()"
                            >
                                @lang('booking::app.admin.catalog.products.edit.type.booking.slots.add')
                            </div>
                        </div>
                    </div>

                    <!-- Table Information -->
            <div class="mt-4 overflow-x-auto">
                <template v-if="slots?.length">
                    <x-admin::table>
                        <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
                            <x-admin::table.thead.tr>
                                <!-- From -->
                                <x-admin::table.th>
                                    @lang('From')
                                </x-admin::table.th>

                                <!-- To -->
                                <x-admin::table.th>
                                    @lang('To')
                                </x-admin::table.th>

                                <!-- Action tables heading -->
                                <x-admin::table.th>
                                    @lang('Actions')
                                </x-admin::table.th>
                            </x-admin::table.thead.tr>
                        </x-admin::table.thead>

                        <x-admin::table.tbody.tr v-for="element in slots">
                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="element.params.from"
                                >
                                </p>
                            </x-admin::table.td>

                            <x-admin::table.td>
                                <p
                                    class="dark:text-white"
                                    v-text="element.params.to"
                                >
                                </p>
                            </x-admin::table.td>

                            <!-- Actions button -->
                            <x-admin::table.td class="!px-0">
                                <span
                                    class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="editModal(element)"
                                >
                                </span>

                                <span
                                    class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    @click="removeOption(element.id)"
                                >
                                </span>
                            </x-admin::table.td>
                        </x-admin::table.tbody.tr>
                    </x-admin::table>
                </template>

                <template v-else>
                    <v-empty-info type="rental"></v-empty-info>
                </template>
            </div>
                </div>
            </div>
        </x-admin::form>

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
                        <v-slots
                            booking-type="rental_slot"
                            :same-slot-all-days="rental_booking.same_slot_all_days"
                        >
                        </v-slots>
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
        app.component('v-rental-booking', {
            template: '#v-rental-booking-template',

            props: ['bookingProduct'],

            data() {
                return {
                    rental_booking: this.bookingProduct && this.bookingProduct.rental_slot ? this.bookingProduct.rental_slot : {
                        renting_type: 'daily',

                        daily_price: '',

                        hourly_price: '',

                        same_slot_all_days: 1,

                        slots: []
                    }
                }
            }
        });
    </script>
@endpush