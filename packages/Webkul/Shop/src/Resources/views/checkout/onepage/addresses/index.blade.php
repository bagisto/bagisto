<v-checkout-addresses
    :cart="cart"
    v-if="cart"
>
</v-checkout-addresses>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-addresses-template"
    >
        <div>
            <!-- Guest Section -->
            <div
                class="mt-8 mb-7"
                v-if="cart.is_guest"
            >
                <x-shop::accordion class="!border-b-0">
                    <x-slot:header class="!p-0">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-medium max-sm:text-xl">
                                @lang('shop::app.checkout.onepage.addresses.title')
                            </h2>
                        </div>
                    </x-slot>

                    <x-slot:content class="!p-0 mt-8">
                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.guest.form.before') !!}

                        <!-- Address Form -->
                        <x-shop::form
                            v-slot="{ meta, errors, values, handleSubmit }"
                            as="div"
                            id="modalForm"
                        >
                            <form @submit="handleSubmit($event, storeGuestAddressToCart)">
                                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.guest.billing.before') !!}

                                @include('shop::checkout.onepage.addresses.guest.billing')

                                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.guest.billing.after') !!}

                                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.guest.shipping.before') !!}

                                @include('shop::checkout.onepage.addresses.guest.shipping')

                                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.guest.shipping.after') !!}
                            </form>
                        </x-shop::form>

                        {!! view_render_event('bagisto.shop.checkout.onepage.addresses.guest.form.after') !!}
                    </x-slot>
                </x-shop::accordion>
            </div>

            <!-- Customer Section -->
            <div
                class="mt-8 mb-7"
                v-else
            >
                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.before') !!}

                @include('shop::checkout.onepage.addresses.customer.billing')

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.billing.after') !!}

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.before') !!}

                @include('shop::checkout.onepage.addresses.customer.shipping')

                {!! view_render_event('bagisto.shop.checkout.onepage.addresses.customer.shipping.after') !!}
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-addresses', {
            template: '#v-checkout-addresses-template',

            props: ['cart'],

            data() {
                return {
                    guest: {
                        applied: {
                            useDifferentAddressForShipping: false,
                        },

                        cart: {},
                    },

                    customer: {
                        available: {
                            types: {
                                id: {
                                    billing: 'billing_address_id',

                                    shipping: 'shipping_address_id',
                                },

                                selectedAddressId: {
                                    billing: 'selectedBillingAddressId',

                                    shipping: 'selectedShippingAddressId',
                                },

                                addressForm: {
                                    billing: 'customerBillingAddressForm',

                                    shipping: 'customerShippingAddressForm',
                                },

                                updateOrCreate: {
                                    billing: 'updateOrCreateBillingAddress',

                                    shipping: 'updateOrCreateShippingAddress',
                                },
                            },
                        },

                        applied: {
                            useDifferentAddressForShipping: false,

                            selectedBillingAddressId: -1,

                            selectedShippingAddressId: -1,
                        },

                        isAddressLoading: true,

                        updateOrCreateBillingAddress: {
                            isEnabled: false,

                            params: {},
                        },

                        updateOrCreateShippingAddress: {
                            isEnabled: false,

                            params: {},
                        },

                        addresses: [],

                        cart: {},
                    },

                    countries: [],

                    states: [],

                    isLoading: false,
                };
            },

            created() {
                this.init();
            },

            computed: {
                customerBillingAddresses() {
                    let addresses = this.customer.addresses.map((address) => {
                        return {
                            id: address.id,
                            companyName: address.company_name,
                            firstName: address.first_name,
                            lastName: address.last_name,
                            email: address.email,
                            address1: address.address1,
                            country: address.country,
                            state: address.state,
                            city: address.city,
                            postcode: address.postcode,
                            phone: address.phone,
                        };
                    });

                    if (
                        ! this.isAddressEmpty(this.customer.cart.billingAddress)
                        && ! this.customer.cart.billingAddress.parentAddressId
                    ) {
                        addresses.unshift(this.customer.cart.billingAddress);
                    }

                    return addresses;
                },

                customerShippingAddresses() {
                    let addresses = this.customer.addresses.map((address) => {
                        return {
                            id: address.id,
                            companyName: address.company_name,
                            firstName: address.first_name,
                            lastName: address.last_name,
                            email: address.email,
                            address1: address.address1,
                            country: address.country,
                            state: address.state,
                            city: address.city,
                            postcode: address.postcode,
                            phone: address.phone,
                        };
                    });

                    if (
                        ! this.isAddressEmpty(this.customer.cart.shippingAddress)
                        && ! this.customer.cart.shippingAddress.parentAddressId
                    ) {
                        addresses.unshift(this.customer.cart.shippingAddress);
                    }

                    return addresses;
                },
            },

            methods: {
                init() {
                    this.getCountries();

                    this.getStates();

                    this.getCartAddresses();

                    if (! this.cart.is_guest) {
                        this.getCustomerAddresses();
                    }
                },

                isAddressEmpty(address) {
                    return Object.keys(address).length === 0;
                },

                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },

                getStates() {
                    this.$axios.get("{{ route('shop.api.core.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(() => {});
                },

                haveStates(countryCode) {
                    return !!this.states[countryCode]?.length;
                },

                getCartAddresses() {
                    let cart = {
                        billingAddress: {},

                        shippingAddress: {},
                    };

                    if (this.cart.billing_address) {
                        if (this.cart.is_guest) {
                            this.guest.applied.useDifferentAddressForShipping = ! (this.cart.billing_address?.use_for_shipping ?? false);
                        } else {
                            this.customer.applied.selectedBillingAddressId = this.cart.billing_address.parent_address_id ?? 0;

                            this.customer.applied.useDifferentAddressForShipping = ! (this.cart.billing_address?.use_for_shipping ?? false);
                        }

                        cart.billingAddress = {
                            id: 0,
                            parentAddressId: this.cart.billing_address?.parent_address_id,
                            companyName: this.cart.billing_address?.company_name,
                            firstName: this.cart.billing_address?.first_name,
                            lastName: this.cart.billing_address?.last_name,
                            email: this.cart.billing_address?.email,
                            address1: this.cart.billing_address?.address1,
                            country: this.cart.billing_address?.country,
                            state: this.cart.billing_address?.state,
                            city: this.cart.billing_address?.city,
                            postcode: this.cart.billing_address?.postcode,
                            phone: this.cart.billing_address?.phone,
                        };
                    }

                    if (this.cart.shipping_address) {
                        if (! this.cart.is_guest) {
                            this.customer.applied.selectedShippingAddressId = this.cart.shipping_address.parent_address_id ?? 0;
                        }

                        cart.shippingAddress = {
                            id: 0,
                            parentAddressId: this.cart.shipping_address?.parent_address_id,
                            companyName: this.cart.shipping_address?.company_name,
                            firstName: this.cart.shipping_address?.first_name,
                            lastName: this.cart.shipping_address?.last_name,
                            email: this.cart.shipping_address?.email,
                            address1: this.cart.shipping_address?.address1,
                            country: this.cart.shipping_address?.country,
                            state: this.cart.shipping_address?.state,
                            city: this.cart.shipping_address?.city,
                            postcode: this.cart.shipping_address?.postcode,
                            phone: this.cart.shipping_address?.phone,
                        };
                    }

                    if (this.cart.is_guest) {
                        this.guest.cart = cart;

                        return;
                    }

                    this.customer.cart = cart;
                },

                getCustomerAddresses() {
                    this.customer.isAddressLoading = true;

                    this.$axios.get('{{ route('api.shop.customers.account.addresses.index') }}')
                        .then(response => {
                            this.customer.addresses = response.data.data;

                            this.customer.isAddressLoading = false;
                        })
                        .catch(() => {});
                },

                openUpdateOrCreateCustomerAddressForm(address, type = 'billing') {
                    this.customer[this.customer.available.types.updateOrCreate[type]].params = address;

                    this.customer[this.customer.available.types.updateOrCreate[type]].isEnabled = true;
                },

                closeUpdateOrCreateCustomerAddressForm(type = 'billing') {
                    this.customer[this.customer.available.types.updateOrCreate[type]].params = {};

                    this.customer[this.customer.available.types.updateOrCreate[type]].isEnabled = false;
                },

                storeCustomerAddress(params, type = 'billing') {
                    return this.$axios.post('{{ route('api.shop.customers.account.addresses.store') }}', params)
                        .then((response) => {
                            this.getCustomerAddresses();

                            this.closeUpdateOrCreateCustomerAddressForm(type);

                            return response;
                        });
                },

                updateCustomerAddress(params, type = 'billing') {
                    this.isLoading = true;

                    return this.$axios.post('{{ route('api.shop.customers.account.addresses.update') }}', params)
                        .then((response) => {
                            this.getCustomerAddresses();

                            this.closeUpdateOrCreateCustomerAddressForm(type);

                            this.isLoading = false;

                            return response;
                        });
                },

                updateOrCreateCustomerAddress(params) {
                    let addressType = params.type;

                    if (params[addressType]['save_address']) {
                        this.storeCustomerAddress(params[addressType], addressType)
                            .then((response) => {
                                const { id } = response.data.data;

                                this.customer.applied[this.customer.available.types.selectedAddressId[addressType]] = id;

                                if (addressType === 'billing') {
                                    this.$refs.customerBillingAddressForm.setValues({
                                        billing_address_id: id,
                                    });
                                } else {
                                    this.$refs.customerShippingAddressForm.setValues({
                                        shipping_address_id: id,
                                    });
                                }
                            });

                        return;
                    }

                    if (params[addressType]['id']) {
                        this.updateCustomerAddress(params[addressType], addressType)
                            .then((response) => {
                                const { id } = response.data.data;

                                this.customer.applied[this.customer.available.types.selectedAddressId[addressType]] = id;

                                if (addressType === 'billing') {
                                    this.$refs.customerBillingAddressForm.setValues({
                                        billing_address_id: id,
                                    });
                                } else {
                                    this.$refs.customerShippingAddressForm.setValues({
                                        shipping_address_id: id,
                                    });
                                }
                            });

                        return;
                    }

                    this.customer.cart[`${addressType}Address`] = {
                        id: 0,
                        companyName: params[addressType].company_name,
                        firstName: params[addressType].first_name,
                        lastName: params[addressType].last_name,
                        email: params[addressType].email,
                        address1: params[addressType].address1.join('\n'),
                        country: params[addressType].country,
                        state: params[addressType].state,
                        city: params[addressType].city,
                        postcode: params[addressType].postcode,
                        phone: params[addressType].phone,
                    };

                    this.closeUpdateOrCreateCustomerAddressForm(addressType);

                    this.customer.applied[this.customer.available.types.selectedAddressId[addressType]] = 0;

                    if (addressType === 'billing') {
                        this.$refs.customerBillingAddressForm.setValues({
                            billing_address_id: 0,
                        });
                    } else {
                        this.$refs.customerShippingAddressForm.setValues({
                            shipping_address_id: 0,
                        });
                    }
                },

                storeCustomerAddressToCart(params) {
                    return this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}', params);
                },

                storeCustomerBillingAddressToCart(params) {
                    this.isLoading = true;

                    if (this.cart.have_stockable_items) {
                        this.$emitter.emit('is-show-shipping-methods', false);

                        this.$emitter.emit('is-shipping-loading', true);

                        this.$emitter.emit('is-show-payment-methods', false);
                    } else {
                        this.$emitter.emit('is-show-payment-methods', false);

                        this.$emitter.emit('is-payment-loading', true);
                    }

                    this.$emitter.emit('can-place-order', false);

                    let address = this.customerBillingAddresses.find((address) => address.id == params['billing_address_id']);

                    if (! address) {
                        this.isLoading = false;

                        return;
                    }

                    this.storeCustomerAddressToCart({
                            billing: {
                                id: address.id ? address.id : null,
                                company_name: address.companyName,
                                first_name: address.firstName,
                                last_name: address.lastName,
                                email: address.email,
                                address1: address.address1.split('\n'),
                                country: address.country,
                                state: address.state,
                                city: address.city,
                                postcode: address.postcode,
                                phone: address.phone,
                                use_for_shipping: true,
                            }
                        })
                        .then((response) => {
                            this.$emitter.emit('update-cart-summary');

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

                            this.isLoading = false;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                },

                storeCustomerShippingAddressToCart(params) {
                    this.$refs.customerBillingAddressForm.validate().then((customerBillingAddressForm) => {
                        let payload = {};

                        if (customerBillingAddressForm.valid) {
                            this.isLoading = true;

                            if (this.cart.have_stockable_items) {
                                this.$emitter.emit('is-show-shipping-methods', false);

                                this.$emitter.emit('is-shipping-loading', true);

                                this.$emitter.emit('is-show-payment-methods', false);
                            } else {
                                this.$emitter.emit('is-show-payment-methods', false);

                                this.$emitter.emit('is-payment-loading', true);
                            }

                            this.$emitter.emit('can-place-order', false);

                            let customerBillingAddressFormValues = this.$refs.customerBillingAddressForm.getValues();

                            let billingAddress = this.customerBillingAddresses.find((address) => address.id == customerBillingAddressFormValues.billing_address_id);

                            if (! billingAddress) {
                                this.isLoading = false;

                                return;
                            }

                            payload['billing'] = {
                                id: billingAddress.id ? billingAddress.id : null,
                                company_name: billingAddress.companyName,
                                first_name: billingAddress.firstName,
                                last_name: billingAddress.lastName,
                                email: billingAddress.email,
                                address1: billingAddress.address1.split('\n'),
                                country: billingAddress.country,
                                state: billingAddress.state,
                                city: billingAddress.city,
                                postcode: billingAddress.postcode,
                                phone: billingAddress.phone,
                                use_for_shipping: false,
                            };

                            let shippingAddress = this.customerShippingAddresses.find((address) => address.id == params['shipping_address_id']);

                            if (! shippingAddress) {
                                this.isLoading = false;

                                return;
                            }

                            payload['shipping'] = {
                                id: shippingAddress.id ? shippingAddress.id : null,
                                company_name: shippingAddress.companyName,
                                first_name: shippingAddress.firstName,
                                last_name: shippingAddress.lastName,
                                email: shippingAddress.email,
                                address1: shippingAddress.address1.split('\n'),
                                country: shippingAddress.country,
                                state: shippingAddress.state,
                                city: shippingAddress.city,
                                postcode: shippingAddress.postcode,
                                phone: shippingAddress.phone,
                            };

                            this.storeCustomerAddressToCart(payload)
                                .then((response) => {
                                    this.$emitter.emit('update-cart-summary');

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

                                    this.isLoading = false;
                                })
                                .catch(() => {
                                    this.isLoading = false;
                                });
                        }
                    });
                },

                storeGuestAddressToCart(params) {
                    this.isLoading = true;

                    if (this.cart.have_stockable_items) {
                        this.$emitter.emit('is-show-shipping-methods', false);

                        this.$emitter.emit('is-shipping-loading', true);

                        this.$emitter.emit('is-show-payment-methods', false);
                    } else {
                        this.$emitter.emit('is-show-payment-methods', false);

                        this.$emitter.emit('is-payment-loading', true);
                    }

                    this.$emitter.emit('can-place-order', false);

                    params['billing']['use_for_shipping'] = ! params.billing.use_different_address_for_shipping;

                    delete params.billing.use_different_address_for_shipping;

                    this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}', params)
                        .then((response) => {
                            this.$emitter.emit('update-cart-summary');

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

                            this.isLoading = false;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
