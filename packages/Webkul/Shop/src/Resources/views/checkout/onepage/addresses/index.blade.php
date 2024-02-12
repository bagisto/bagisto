{!! view_render_event('bagisto.shop.checkout.onepage.addresses.before') !!}

<v-checkout-addresses :cart="cart"></v-checkout-addresses>

{!! view_render_event('bagisto.shop.checkout.onepage.addresses.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-addresses-template"
    >
        <template v-if="isAddressLoading">
            <!-- Onepage Shimmer Effect --> 
            <x-shop::shimmer.checkout.onepage.address />
        </template>
        
        <template v-else>
            <div class="mt-8 mb-7">
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing.before') !!} 

                @include('shop::checkout.onepage.addresses.billing')

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.billing.after') !!}

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.before') !!}

                @include('shop::checkout.onepage.addresses.shipping')

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping.after') !!} 
            </div>

            <div 
                class="flex justify-end mt-4"
                v-if="
                (selectedAddresses.billing.id || selectedAddresses.shipping.id)
                && ! shippingAddress.isShowShippingForm
                "
            >
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.confirm_button.before') !!}

                <x-shop::button
                    type="button"
                    class="primary-button py-3 px-11 rounded-2xl"
                    :title="trans('shop::app.checkout.onepage.addresses.shipping.confirm')"
                    :loading="false"
                    @click="proceed"
                />

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.shipping_address.confirm_button.after') !!}
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-checkout-addresses', {
            template: '#v-checkout-addresses-template',

            props: ['cart'],

            data() {
                return {
                    customerAddresses: [],

                    addNewBillingAddress: false,

                    shippingAddress: {
                        sameAsBilling: true,

                        isShowShippingForm: false,
                    },

                    cartAddresses: {
                        billing: {},

                        shipping: {},
                    },

                    selectedAddresses: {
                        billing: {
                            id: null,
                        },

                        shipping: {
                            id: null,
                        }
                    },

                    countries: [],

                    customer: @json(auth()->guard('customer')->user()),

                    isSameAsBilling: false,

                    isAddressLoading: true,
                };
            },

            mounted() {
                this.get();

                this.getCountries();
            },

            computed: {
                savedBillingAddresses() {
                    const addresses = [];

                    // this.cartAddresses.billing = this.cart.billing_address;

                    // if (this.cartAddresses.billing) {
                    //     this.cartAddresses.billing.default_address = true;

                    //     addresses.push(this.cartAddresses.billing);
                    // }

                    this.customerAddresses.forEach((address) => {
                        if (
                            this.customer.id
                            && this.cartAddresses?.billing?.cart_id
                        ) {
                            return;
                        }

                        address.default_address = false;

                        addresses.push(address);
                    });

                    return addresses;
                },

                savedShippingAddresses() {
                    const addresses = [];

                    // this.cartAddresses.shipping = this.cart.shipping_address;

                    // if (this.cartAddresses.shipping) {
                    //     this.cartAddresses.shipping.default_address = true;

                    //     addresses.push(this.cartAddresses.shipping);
                    // }

                    this.customerAddresses.forEach((address) => {
                        if (
                            this.customer.id
                            && this.cartAddresses?.shipping?.cart_id
                        ) {
                            return;
                        }

                        address.default_address = false;

                        addresses.push(address);
                    });

                    return addresses;
                },
            },

            methods: {
                get() {
                    this.isAddressLoading = true;

                    if (! this.customer) {
                        this.isAddressLoading = false;

                        return;
                    }

                    this.$axios.get('{{ route('api.shop.customers.account.addresses.index') }}')
                        .then(response => {
                            this.customerAddresses = response.data.data;

                            this.isAddressLoading = false;
                        })
                        .catch(() => {});
                },

                store(params, { resetForm }) {
                    this.customerAddresses.forEach((address) => {
                        const propertiesToCopy = ['email', 'first_name', 'last_name', 'country', 'phone', 'city', 'state', 'postcode', 'company_name'];

                        if (address.id == this.selectedAddresses.billing.id) {
                            if (! params.billing) {
                                params.billing = {};
                            }

                            params.billing.address1 = [address.address1];

                            propertiesToCopy['use_for_shipping'] = true;

                            propertiesToCopy.forEach((property) => {
                                if (address[property]) {
                                    params.billing[property] = address[property];
                                }
                            });
                        }

                        if (address.id == this.selectedAddresses.shipping.id) {
                            if (! params.shipping) {
                                params.shipping = {};
                            }

                            params.shipping.address1 = [address.address1];

                            propertiesToCopy.forEach((property) => {
                                if (address[property]) {
                                    params.billing[property] = address[property];
                                }
                            });
                        }
                    });

                    this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}', params)
                        .then((_) => {
                            this.$emitter.emit('update-cart-summary');

                            this.addNewBillingAddress = false;

                            this.shippingAddress.isShowShippingForm = false;

                            resetForm();

                            this.get();
                        })
                        .catch(() => {});
                },

                proceed() {
                    this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}',  {
                        billing: {
                            address1: [''],

                            address_id: this.selectedAddresses.billing.id,
                        }, 

                        shipping: {
                            address1: [''],

                            address_id: this.selectedAddresses.shipping.id,
                        }
                    })
                        .then((response) => {
                            if (response.data.data.shippingMethods) {
                                this.$emitter.emit('shipping-methods', response.data.data.shippingMethods);

                                this.$emitter.emit('is-show-shipping-methods', true);

                                this.$emitter.emit('is-shipping-loading', false);
                            }

                            if (response.data.data.payment_methods) {
                                this.$emitter.emit('payment-methods', response.data.data.payment_methods);

                                this.$emitter.emit('is-show-payment-methods', true);

                                this.$emitter.emit('is-payment-loading', false);
                            }

                            this.$emitter.emit('update-cart-summary');

                            resetForm();
                        })
                        .catch(() => {});
                },

                haveStates(addressType) {
                    return false;
                },

                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },
            },
        });
    </script>
@endPushOnce