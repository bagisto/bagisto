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
                (selectedBillingAddressId || selectedShippingAddressId)
                && ! toggleShippingForm
                && ! addNewBillingAddress
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
                    addNewBillingAddress: false,

                    countries: [],

                    customer: @json(auth()->guard('customer')->user()),

                    customerAddresses: {
                        billing: [],

                        shipping: [],
                    },

                    isAddressLoading: true,

                    isSameAsBilling: false,

                    selectedBillingAddressId: null,

                    selectedShippingAddressId: null,

                    shippingIsSameAsBilling: true,

                    tempAddressId: 1,

                    toggleShippingForm: false,

                    tempBillingAddress: {},

                    tempShippingAddress: {},

                    isAddressEtitable: false,
                };
            },

            mounted() {
                this.init();
                
                this.get();

                this.getCountries();
            },

            watch: {
                selectedAddresses: {
                    handler(newQuestion) {
                        this.resetState();
                    },
                    
                    deep: true
                }
            },
            
            computed: {
                savedBillingAddresses() {
                    const addresses = [];

                    this.customerAddresses.billing.forEach((address) => addresses.push(address));

                    return addresses;
                },

                savedShippingAddresses() {
                    const addresses = [];

                    this.customerAddresses.shipping.forEach((address) => addresses.push(address));

                    return addresses;
                },
            },

            methods: {
                init() {
                    const storedAddresses = localStorage.getItem('customerAddresses');

                    if (storedAddresses) {
                        this.customerAddresses.billing = JSON.parse(storedAddresses).billing;

                        this.customerAddresses.shipping = JSON.parse(storedAddresses).shipping;

                        this.tempAddressId = (this.customerAddresses.billing.length > this.customerAddresses.shipping.length ? this.customerAddresses.billing.length + 1 : this.customerAddresses.shipping.length) + 1;
                    }
                },

                get() {
                    this.isAddressLoading = true;

                    if (! this.customer) {
                        this.isAddressLoading = false;

                        return;
                    }

                    this.$axios.get('{{ route('api.shop.customers.account.addresses.index') }}')
                        .then(response => {
                            this.customerAddresses.billing = this.customerAddresses.shipping = response.data.data;

                            this.isAddressLoading = false;
                        })
                        .catch(() => {});
                },

                store(params, { resetForm }) {
                    if (params[params.type].id) {
                        return this.update(params);
                    }

                    if (! this.customer
                        || (
                            this.customer
                            && ! params[params.type].save_address
                        )
                    ) {
                        params[params.type].id = this.tempAddressId + 1;

                        this.customerAddresses[params.type].push(params[params.type]);

                        localStorage.setItem('customerAddresses', JSON.stringify(this.customerAddresses));

                        this.tempAddressId++;

                        this.addNewBillingAddress = false;

                        this.toggleShippingForm = false;

                        return;
                    }

                    if (localStorage.getItem('customerAddresses')) {
                        localStorage.removeItem('customerAddresses');
                    }

                    this.$axios.post('{{ route('api.shop.customers.account.addresses.store') }}', params[params.type])
                        .then(() => {
                            this.$emitter.emit('update-cart-summary');

                            this.addNewBillingAddress = false;

                            this.toggleShippingForm = false;

                            resetForm();

                            this.get();
                        })
                        .catch(() => {});
                },

                update(params) {
                    if (! this.customer) {
                        const existingAddressIndex = this.customerAddresses[params.type].findIndex(address => address.id === params[params.type].id);

                        if (existingAddressIndex !== -1) {
                            this.customerAddresses[params.type][existingAddressIndex] = {
                                ...this.customerAddresses[params.type][existingAddressIndex],
                                ...params[params.type]
                            };
                        }

                        const storedAddresses = JSON.parse(localStorage.getItem('customerAddresses')) || [];

                        const storedAddressIndex = storedAddresses[params.type].findIndex(address => address.id === params[params.type].id);

                        if (storedAddressIndex !== -1) {
                            storedAddresses[storedAddressIndex] = {
                                ...storedAddresses[storedAddressIndex],
                                ...params[params.type]
                            };

                            localStorage.setItem('customerAddresses', JSON.stringify(storedAddresses));
                        }

                        this.addNewBillingAddress = false;

                        this.toggleShippingForm = false;

                        this.isAddressEtitable = false;

                        return;
                    }

                    this.$axios.post("{{ route('api.shop.customers.account.addresses.update') }}", params[params.type])
                        .then(response => {
                            this.get();

                            this.$emitter.emit('update-cart-summary');

                            this.addNewBillingAddress = false;

                            this.toggleShippingForm = false;

                            this.isAddressEtitable = false;

                            resetForm();
                        })
                        .catch(() => {});
                },

                proceed() {
                    let params = {
                        billing: {
                            address1: [''],

                            address_id: this.selectedBillingAddressId,
                        },

                        shipping: {
                            address1: [''],

                            address_id: this.selectedShippingAddressId,
                        }
                    };

                    if (! this.customer) {
                        const billingId = this.selectedBillingAddressId;

                        const shippingId = this.selectedShippingAddressId;

                        params.billing = this.customerAddresses.billing.find((value) =>  this.selectedBillingAddressId = value.id);
                        
                        params.shipping = this.customerAddresses.shipping.find((value) => this.selectedShippingAddressId = value.id);
                        
                        this.selectedBillingAddressId = billingId;
                        
                        this.selectedShippingAddressId = shippingId;
                    }

                    this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}', params)
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

                resetState(state = false) {
                    this.$emitter.emit('is-show-shipping-methods', state);

                    this.$emitter.emit('is-show-payment-methods', false);
                }
            },
        });
    </script>
@endPushOnce