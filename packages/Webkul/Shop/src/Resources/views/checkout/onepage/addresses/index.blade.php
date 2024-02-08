{!! view_render_event('bagisto.shop.checkout.onepage.addresses.before') !!}

<v-checkout-addresses 
    ref="vCheckoutAddress"
    :have-stockable-items="cart.haveStockableItems"
>
</v-checkout-addresses>

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
        </template>
    </script>

    <script type="module">
        app.component('v-checkout-addresses', {
            template: '#v-checkout-addresses-template',

            props: ['haveStockableItems'],

            data() {
                return  {
                    forms: {
                        billing: {
                            address: {
                                address1: [''],

                                isSaved: false,
                            },

                            isNew: false,

                            isUsedForShipping: true,
                        },

                        shipping: {
                            address: {
                                address1: [''],

                                isSaved: false,
                            },

                            isNew: false,
                        },
                    },

                    savedCartAddresses: {
                        billing: @json($cart->billing_address) ?? [],

                        shipping: [@json($cart->shipping_address)] ?? [],
                    },

                    countries: [],

                    states: [],

                    isAddressLoading: true,

                    isCustomer: "{{ auth()->guard('customer')->check() }}",

                    isTempAddress: false,
                };
            }, 
            
            created() {
                this.getCustomerAddresses();

                this.getCountryStates();

                this.getCountries();
            },

            methods: {
                resetBillingAddressForm() {
                    this.forms.billing.address = {
                        address1: [''],

                        isSaved: false,
                    };
                },

                resetShippingAddressForm() {
                    this.forms.shipping.address = {
                        address1: [''],

                        isSaved: false,
                    };
                },

                resetPaymentAndShippingMethod() {
                    this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;

                    this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;
                },

                getCustomerAddresses() {
                    if (! this.isCustomer) { 
                        this.isAddressLoading = false;

                        return;
                    }

                    this.$axios.get("{{ route('api.shop.customers.account.addresses.index') }}")
                        .then(response => {
                            this.savedCartAddresses.billing = response.data.data.map((address, index, row) => {
                                if (! this.forms.billing.address.address_id) {
                                    let isDefault = address.default_address ? address.default_address : index === 0;

                                    if (isDefault) {
                                        this.forms.billing.address.address_id = address.id;

                                        this.forms.shipping.address.address_id = address.id;
                                    }
                                }

                                if (! this.forms.billing.isUsedForShipping) {
                                    this.forms.shipping.address.address_id = row[row.length - 1].id;
                                }

                                return {
                                    ...address,
                                    isSaved: true,
                                    isDefault: typeof isDefault === 'undefined' ? false : isDefault,
                                };
                            });

                            this.isAddressLoading = false;
                        })
                        .catch((error) => {});
                },

                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(function (error) {});
                },

                getCountryStates() {
                    this.$axios.get("{{ route('shop.api.core.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(function (error) {});
                },

                addNewBillingAddress() {
                    this.resetBillingAddressForm();

                    this.forms.billing.isNew = true;

                    this.resetPaymentAndShippingMethod();
                },

                storeBillingAddress() {
                    if (! this.forms.billing.address.isSaved) {
                        this.forms.billing.isNew = false;

                        this.isTempAddress = true;

                        this.savedCartAddresses.billing.push({
                            ...this.forms.billing.address,
                            isSaved: false,
                        });
                    }

                    if (this.isCustomer) {
                        this.saveAddress(this.forms.billing);

                        this.resetBillingAddressForm();

                        this.getCustomerAddresses();
                    }
                },

                addNewShippingAddress() {
                    this.resetShippingAddressForm();

                    this.forms.shipping.isNew = true;

                    this.resetPaymentAndShippingMethod();
                },

                storeShippingAddress() {
                    if (! this.forms.shipping.address.isSaved) {
                        this.forms.shipping.isNew = false;

                        this.isTempAddress = true;
                        
                        this.savedCartAddresses.shipping.push({
                            ...this.forms.shipping.address,
                            isSaved: false,
                        });
                    }

                    if (this.isCustomer) {
                        this.saveAddress(this.forms.shipping);
    
                        this.resetShippingAddressForm();
                            
                        this.getCustomerAddresses();
                    }
                },

                store() {
                    this.$refs.storeAddress.isLoading = true;

                    let shippingMethod = this.$parent.$refs.vShippingMethod;

                    let paymentMethod = this.$parent.$refs.vPaymentMethod;

                    if (this.haveStockableItems) {
                        shippingMethod.isShowShippingMethod = false;
                        
                        shippingMethod.isShippingMethodLoading = true;
                    } else {
                        paymentMethod.isShowPaymentMethod = false;
    
                        paymentMethod.isPaymentMethodLoading = true;
                    }

                    this.$axios.post('{{ route("shop.checkout.onepage.addresses.store") }}', {
                            billing: {
                                ...this.forms.billing.address,

                                use_for_shipping: this.forms.billing.isUsedForShipping,
                            },

                            shipping: {
                                ...this.forms.shipping.address,
                            },
                        })
                        .then(response => {
                            paymentMethod.isShowPaymentMethod = false;

                            this.$parent.$refs.vCartSummary.canPlaceOrder = false;

                            if (response.data.data.payment_methods) {
                                paymentMethod.payment_methods = response.data.data.payment_methods;
                                
                                paymentMethod.isShowPaymentMethod = true;
    
                                paymentMethod.isPaymentMethodLoading = false;
                            } else {
                                shippingMethod.shippingMethods = response.data.data.shippingMethods;

                                shippingMethod.isShowShippingMethod = true;

                                shippingMethod.isShippingMethodLoading = false;
                            }
                            
                            this.$parent.getOrderSummary();
                            
                            if (this.forms.billing.isUsedForShipping
                                && this.forms.billing.address_id
                            ) {
                                this.getCustomerAddresses();
                            }

                            this.$refs.storeAddress.isLoading = false;
                        })
                        .catch(error => {
                            this.$refs.storeAddress.isLoading = false;
                        });
                },

                saveAddress(params) {
                    this.$axios.post('{{ route("api.shop.customers.account.addresses.store") }}', params.address)
                        .then(response => {
                            params.isNew = false;
                        })
                        .catch(error => {                 
                            console.log(error);
                        });
                },

                haveStates(addressType) {
                    if (
                        this.states[this.forms[addressType].address.country]
                        && this.states[this.forms[addressType].address.country].length
                    ) {
                        return true;
                    }

                    return false;
                },
            },
        });
    </script>
@endPushOnce