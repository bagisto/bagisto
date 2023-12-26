<v-rental-slots :bookingProduct = "{{ $bookingProduct }}"></v-rental-slots>

@pushOnce('scripts')
    <script type="text/x-template" id="v-rental-slots-template">
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, handleBillingAddressForm)">
                <div class="book-slots">
                    <h1>Hello</h1>
                    <h3>
                        @lang('booking::app.shop.products.rent-an-item')
                    </h3>

                    <div v-if="renting_type == 'daily_hourly'">
                        <div class="form-group">
                            <label class="label-style required">
                                @label('booking::app.shop.products.choose-rent-option')
                            </label>

                            <span class="radio">
                                <input
                                    type="radio"
                                    id="daily-renting-type"
                                    name="booking[renting_type]"
                                    value="daily"
                                    v-model="sub_renting_type"
                                >

                                <label class="radio-view" for="daily-renting-type"></label>

                                @lang('booking::app.shop.products.daily-basis')
                            </span>

                            <span class="radio">
                                <input
                                    type="radio"
                                    id="hourly-renting-type"
                                    name="booking[renting_type]"
                                    value="hourly"
                                    v-model="sub_renting_type"
                                >

                                <label class="radio-view" for="hourly-renting-type"></label>
                                @lang('booking::app.shop.products.hourly-basis')
                            </span>
                        </div>
                    </div>
                    
                    <div v-if="renting_type != 'daily' && sub_renting_type == 'hourly'">
                        <div>
                            <label class="label-style required">
                                @lang('booking::app.shop.products.select-slot')
                            </label>

                            <div class="flex gap-2.5">
                                <x-shop::form.control-group class="w-full">
                                    <x-shop::form.control-group.label class="hidden">
                                        @lang('booking::app.shop.products.select-date')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="date"
                                        name="booking[date]"
                                        rules="required"
                                        data-min-date="today"
                                        :label="trans('booking::app.shop.products.select-date')"
                                        :placeholder="trans('booking::app.shop.products.select-date')"
                                        @change="dateSelected($event)"
                                    >
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error
                                        control-name="booking[date]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>

                                <x-shop::form.control-group class="w-full !mb-px">
                                    <x-shop::form.control-group.label class="hidden">
                                        @lang('booking::app.shop.products.select-date')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="select"
                                        name="booking[slot]"
                                        class="!mb-1"
                                        rules="required"
                                        v-model="selected_slot"
                                        :label="trans('booking::app.shop.products.select-date')"
                                        :placeholder="trans('booking::app.shop.products.select-date')"
                                    >
                                        <option value="">
                                            @lang('booking::app.shop.products.select-time-slot')
                                        </option>

                                        <option
                                            v-for="(slot, index) in slots"
                                            :value="index"
                                        >
                                            @{{ slot.time }}
                                        </option>
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error
                                        control-name="booking[slot]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
                            </div>
                        </div>

                        <div v-if="parseInt(slots[selected_slot] && slots[selected_slot]?.slots?.length)">
                            <label class="label-style required">
                                @lang('booking::app.shop.products.select-rent-time')
                            </label>

                            <div class="flex gap-2.5">
                                <x-shop::form.control-group class="w-full !mb-px">
                                    <x-shop::form.control-group.label class="hidden">
                                        @lang('booking::app.shop.products.select-date')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="select"
                                        name="booking[slot][from]"
                                        class="!mb-1"
                                        rules="required"
                                        v-model="slot_from"
                                        :label="trans('booking::app.shop.products.select-date')"
                                        :placeholder="trans('booking::app.shop.products.select-date')"
                                    >
                                        <option value="">
                                            @lang('booking::app.shop.products.select-time-slot')
                                        </option>

                                        <option
                                            v-for="slot in slots[selected_slot]?.slots"
                                            :value="slot.from_timestamp"
                                        >
                                            @{{ slot.from }}
                                        </option>
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error
                                        control-name="booking[slot][from]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>

                                <x-shop::form.control-group class="w-full !mb-px">
                                    <x-shop::form.control-group.label class="hidden">
                                        @lang('booking::app.shop.products.slot')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="select"
                                        name="booking[slot][to]"
                                        class="!mb-1"
                                        rules="required"
                                        :label="trans('booking::app.shop.products.slot')"
                                        :placeholder="trans('booking::app.shop.products.slot')"
                                    >
                                        <option value="">
                                            @lang('booking::app.shop.products.select-time-slot')
                                        </option>

                                        <option
                                            v-for="slot in slots[selected_slot]?.slots"
                                            {{-- v-if="slot_from < slot?.to_timestamp" --}}
                                            :value="slot?.to_timestamp"
                                        >
                                            @{{ slot.to }}
                                        </option>
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error
                                        control-name="booking[slot][to]"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <label class="label-style required">
                            @lang('booking::app.shop.products.select-date')
                        </label>

                        <div class="flex gap-2.5">
                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="hidden">
                                    @lang('booking::app.shop.products.from')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="date"
                                    name="booking[date_from]"
                                    {{-- rules="required|before_or_equal:date_to" --}}
                                    v-model="date_from"
                                    ref="date_from"
                                    data-min-date="today"
                                    :label="trans('booking::app.shop.products.from')"
                                    :placeholder="trans('booking::app.shop.products.from')"
                                    @change="dateSelected($event)"
                                >
                                </x-shop::form.control-group.control>
        
                                <x-shop::form.control-group.error
                                    control-name="booking[date_from]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            <x-shop::form.control-group class="w-full">
                                <x-shop::form.control-group.label class="hidden">
                                    @lang('booking::app.shop.products.to')
                                </x-shop::form.control-group.label>
        
                                <x-shop::form.control-group.control
                                    type="date"
                                    name="booking[date_to]"
                                    {{-- rules="required|date_format:yyyy-MM-dd|after_or_equal:date_from" --}}
                                    v-model="date_from"
                                    ref="date_from"
                                    data-min-date="today"
                                    :label="trans('booking::app.shop.products.to')"
                                    :placeholder="trans('booking::app.shop.products.to')"
                                    @change="dateSelected($event)"
                                >
                                </x-shop::form.control-group.control>
        
                                <x-shop::form.control-group.error
                                    control-name="booking[date_to]"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>
                        </div>
                    </div>
                </div>
            </form>
        </x-shop::form>
    </script>

    <script type="module">

        app.component('v-rental-slots', {
            template: '#v-rental-slots-template',

            props: ['bookingProduct'],

            data() {
                return {
                    renting_type: "{{ $bookingProduct->rental_slot->renting_type }}",

                    sub_renting_type: 'hourly',

                    slots: [],

                    selected_slot: '',

                    slot_from: '',

                    date_from: '',

                    date_to: ''
                }
            },

            created() {
                var self = this;

                // this.$validator.extend('after_or_equal', {
                //     getMessage(field, val) {
                //         return 'The "To" must be equal or after "From"';
                //     },

                //     validate(value, field) {
                //         if (! self.date_from) {
                //             return true;
                //         }

                //         var from = new Date(self.date_from);
                        
                //         var to = new Date(self.date_to);

                //         return from <= to;
                //     }
                // });

                // this.$validator.extend('before_or_equal', {
                //     getMessage(field, val) {
                //         return 'The "From must be equal or before "To"';
                //     },

                //     validate(value, field) {
                //         if (! self.date_to) {
                //             return true;
                //         }

                //         var from = new Date(self.date_from);
                        
                //         var to = new Date(self.date_to);

                //         return from <= to;
                //     }
                // });
            },

            methods: {
                dateSelected(params) {
                    let date = params.target.value;

                    this.$axios.get(`{{ route('booking_product.slots.index', '') }}/${this.bookingProduct.id}`, {
                        params: { date }
                    })
                        .then((response) => {
                            console.log(response);
                            this.selected_slot = '';
                            
                            this.slot_from = '';

                            this.slots = response.data.data;
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }

        });
        
    </script>
@endpushOnce