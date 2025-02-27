{!! view_render_event('bagisto.shop.checkout.cart.summary.giftcard.before') !!}

<v-gift-card
    :cart="cart"
    @giftcard-applied="getCart"
    @giftcard-removed="getCart"
>
</v-gift-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-gift-card-template"
    >
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm max-sm:font-normal">
                @{{ cart.giftcard_number ? ("@lang('giftcard::app.giftcard.applied')") : "@lang('giftcard::app.giftcard.discount')" }}
            </p>         

            {!! view_render_event('bagisto.shop.checkout.cart.giftcard.before') !!}

            <p class="text-base font-medium max-sm:text-sm">
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                 <!-- Apply Giftcard form -->
                    <form @submit="handleSubmit($event, activateGiftCard)">
                        {!! view_render_event('bagisto.shop.checkout.cart.giftcard.giftcard.controls.before') !!}
                        <!-- Apply Giftcard modal -->
                        <x-shop::modal ref="giftCardModel">
                            <!-- Modal Toggler -->
                            <x-slot:toggle>
                                <span
                                    class="text-[#0A49A7] cursor-pointer"
                                    role="button"
                                    tabindex="0"
                                    v-if="!cart || !cart.giftcard_number"
                                >
                                    @lang('giftcard::app.giftcard.apply')
                                </span>
                            </x-slot>

                            <!-- Modal Header -->
                            <x-slot:header>
                                <h2 class="text-2xl font-medium max-sm:text-xl">
                                    @lang('giftcard::app.giftcard.apply')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <x-shop::form.control-group class="!mb-0">
                                    <x-shop::form.control-group.control
                                        type="text"
                                        class="py-5 px-6"
                                        name="giftcard_number"
                                        v-model="giftCardNumber"
                                        rules="required"
                                        :placeholder="trans('giftcard::app.giftcard.enter-your-code')"
                                    />

                                    <x-shop::form.control-group.error
                                        class="flex"
                                        control-name="giftcard_number"
                                    />
                                </x-shop::form.control-group>

                                <!-- Additional Controls for Gift Card Status -->
                                <div v-if="giftCardInfo">
                                    <p style="text-align: left; font-weight: normal;">
                                        Status: <span style="font-weight: bold;"> @{{ getGiftCardStatus() }}</span>
                                    </p>
                                    <p style="text-align: left; font-weight: normal;">
                                        Gift Card Amount: <span style="font-weight: bold;">@{{ giftCardInfo.giftcard_amount }}</span>
                                    </p>
                                    <p style="text-align: left; font-weight: normal;">
                                        Valid Till: <span style="font-weight: bold;">@{{ giftCardInfo.expirationdate }}</span>
                                    </p>
                                </div>
                                

                                <div class="flex gap-4 mt-4">
                                    <x-shop::button
                                        class="primary-button"
                                        :title="trans('giftcard::app.giftcard.check-status')"
                                        @click.prevent="checkGiftCardStatus"
                                    >Check Status</x-shop::button>

                                    <x-shop::button
                                        class="primary-button"
                                        :title="trans('giftcard::app.giftcard.activate')"
                                        @click.prevent="activateGiftCard"
                                    >Activate</x-shop::button>
                                </div>
                            </x-slot>
                        </x-shop::modal>

                        {!! view_render_event('bagisto.shop.checkout.cart.giftcard.giftcard.controls.after') !!}
                    </form>
                </x-shop::form>

                <!-- Applied Coupon Information Container -->
                <div
                    class="flex justify-between items-center text-xs font-small "
                    v-if="cart && cart.giftcard_number"
                >
                    <p
                        class="text-base font-medium text-navyBlue"
                        title="@lang('giftcard::app.giftcard.applied')"
                    >
                        <!-- Display gift card number only if it's available -->
                         @{{ cart.giftcard_number }} 
                    </p>

                    <span
                        class="icon-cancel text-2xl cursor-pointer"
                        title="@lang('giftcard::app.giftcard.remove')"
                        @click="destroyGiftCard"
                    >
                    </span>
                </div>
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.giftcard.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-gift-card', {
            template: '#v-gift-card-template',

            props: ['cart'],

            data() {
                return {
                    isStoring: false,
                    giftCardInfo: null,
                    giftCardNumber: ''
                };
            },

            methods: {
                checkGiftCardStatus() {
                    return new Promise((resolve, reject) => {
                        this.$axios.post("{{ route('shop.api.checkout.cart.giftcard.checkstatus') }}", { giftcard_number: this.giftCardNumber })
                            .then((response) => {
                                this.giftCardInfo = response.data;
                                if (this.giftCardInfo.giftcard_amount > 0) {
                                    resolve('active');
                                } else {
                                    reject('no_balance');
                                }
                            })
                            .catch((error) => {
                                this.isStoring = false;
                                this.$refs.giftCardModel.toggle();
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                reject(error);
                            });
                    });
                },

                activateGiftCard() {
                    this.isStoring = true;
                    this.checkGiftCardStatus().then((status) => {
                        if (status === 'active') {
                            this.$axios.post("{{ route('shop.api.checkout.cart.giftcard.activate') }}", { giftcard_number: this.giftCardNumber })
                            .then((response) => {
                                    this.isStoring = false;
                                    this.$refs.giftCardModel.toggle();
                                    this.$emit('giftcard-applied', response.data);
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch((error) => {
                                    this.isStoring = false;
                                    this.$refs.giftCardModel.toggle();
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                });
                        }
                    }).catch((reason) => {
                        this.isStoring = false;
                        this.$refs.giftCardModel.toggle();
                        if (reason === 'already_used') {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "Gift card has already been used." });
                        } else if (reason === 'expired') {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "Gift card is expired and cannot be activated." });
                        } else if (reason === 'no_balance') {
                            this.$emitter.emit('add-flash', { type: 'error', message: "Gift card has no balance." });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'error', message: "Failed to check gift card status." });
                        }
                    });
                },
                getGiftCardStatus() {
                    if (!this.giftCardInfo) {
                        return 'Unknown';
                    }
                    return this.giftCardInfo.giftcard_amount > 0 ? 'Active' : 'No Balance';
                },
                resetForm() {
                    this.giftCardNumber = '';
                    this.giftCardInfo = null;
                },
                destroyGiftCard() {
                    this.$axios.delete("{{ route('shop.api.checkout.cart.giftcard.remove') }}", { '_token': "{{ csrf_token() }}" })
                        .then((response) => {
                             // Reset form data
                            this.resetForm();
                            this.$emit('giftcard-removed');
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>

@endPushOnce

{!! view_render_event('bagisto.shop.checkout.cart.summary.giftcard.after') !!}

<!-- Giftcard Discount -->
{!! view_render_event('bagisto.shop.checkout.onepage.summary.giftcard_amount.before') !!}

<div
    class="flex text-right justify-between"
    v-if="cart.giftcard_amount && parseFloat(cart.giftcard_amount) > 0"
>
    <p class="text-base">
        @lang('giftcard::app.giftcard.giftcard_amount')
    </p>

    <p class="text-base font-medium">
      - $@{{ cart.giftcard_amount }}
    </p>
</div>

<div
    class="flex text-right justify-between"
    v-if="cart.giftcard_amount && parseFloat(cart.giftcard_amount) > 0"
>
    <p class="text-base">
        @lang('giftcard::app.giftcard.remaining_giftcard_amount')
    </p>

    <p class="text-base font-medium">
     $@{{ cart.remaining_giftcard_amount }}
    </p>
</div>

{!! view_render_event('bagisto.shop.checkout.onepage.summary.giftcard_amount.after') !!}