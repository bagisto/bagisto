<v-book-slots :bookingProduct = "{{ $bookingProduct }}"></v-book-slots>

@pushOnce('scripts')
    <script type="text/x-template" id="v-book-slots-template">
        <div>
            <x-shop::form.control-group.label class="required">
                {{ $title  ?? trans('booking::app.shop.products.view.booking.slots.book-an-appointment') }}
            </x-shop::form.control-group.label>

            <div class="grid grid-cols-2 gap-2.5">
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label class="hidden">
                        @lang('booking::app.shop.products.view.booking.slots.date')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="date"
                        name="booking[date]"
                        class="py-4"
                        minDate="{{ $bookingProduct->available_from }}"
                        maxDate="{{ $bookingProduct->available_to }}"
                        rules="required"
                        :label="trans('booking::app.shop.products.view.booking.slots.date')"
                        :placeholder="trans('YYYY-MM-DD')"
                        @change="getAvailableSlots"
                    >
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error
                        control-name="booking[date]"
                    >
                    </x-shop::form.control-group.error>
                </x-shop::form.control-group>

                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label class="hidden">
                        @lang('booking::app.shop.products.view.booking.slots.title')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        name="booking[slot]"
                        class="py-4"
                        rules="required"
                        :label="trans('booking::app.shop.products.view.booking.slots.title')"
                        :placeholder="trans('booking::app.shop.products.view.booking.slots.title')"
                    >
                        <option
                            v-for="slot in slots"
                            :value="slot.timestamp"
                        >
                            @{{ slot.from + ' - ' + slot.to }}
                        </option>

                        <option v-if="! slots?.length">
                            @lang('booking::app.shop.products.view.booking.slots.no-slots-available')
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

            props: ['bookingProduct', 'title'],

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
