<v-book-slots :bookingProduct = "{{ $bookingProduct }}"></v-book-slots>

@pushOnce('scripts')
    <script type="text/x-template" id="v-book-slots-template">
        <div class="book-slots">
            <label class="label-style required">
                {{ $title ?? __('booking::app.shop.products.book-an-appointment') }}
            </label>

            <div class="flex gap-2.5">
                <x-shop::form.control-group class="w-full">
                    <x-shop::form.control-group.label class="hidden">
                        @lang('shop::app.customers.account.profile.dob')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="date"
                        name="booking[date]"
                        minDate="{{ $bookingProduct->available_from }}"
                        maxDate="{{ $bookingProduct->available_to }}"
                        rules="required"
                        @change="getAvailableSlots"
                    >
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error
                        control-name="booking[date]"
                    >
                    </x-shop::form.control-group.error>
                </x-shop::form.control-group>

                <x-shop::form.control-group class="w-full !mb-px">
                    <x-shop::form.control-group.label class="hidden">
                        @lang('shop::app.checkout.onepage.addresses.billing.country')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        name="booking[slot]"
                        class="!mb-1"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.addresses.billing.country')"
                        :placeholder="trans('shop::app.checkout.onepage.addresses.billing.country')"
                    >
                        <option v-for="slot in slots" :value="slot.timestamp">
                            @{{ slot.from + ' - ' + slot.to }}
                        </option>

                        <option value="" v-if="! slots?.length">
                            @lang('booking::app.shop.products.no-slots-available')
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error
                        control-name="booking[slot]"
                    >
                    </x-shop::form.control-group.error>
                </x-shop::form.control-group>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-book-slots', {
            template: '#v-book-slots-template',

            props: ['bookingProduct'],

            data() {
                return {
                    slots: []
                }
            },

            methods: {
                getAvailableSlots(params) {
                    let date = params.target.value;

                    this.$axios.get(`{{ route('booking_product.slots.index', '') }}/${this.bookingProduct.id}`, {
                        params: { date }
                    })
                        .then((response) => {
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
