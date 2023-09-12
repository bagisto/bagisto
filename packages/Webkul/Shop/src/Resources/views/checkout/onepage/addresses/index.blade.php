{!! view_render_event('bagisto.shop.checkout.addresses.before') !!}

<v-checkout-addresses 
    ref="vCheckoutAddress"
    :have-stockable-items="cart.haveStockableItems"
>
</v-checkout-addresses>

{!! view_render_event('bagisto.shop.checkout.addresses.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkout-addresses-template">
        <template v-if="isAddressLoading">
            <!-- Onepage Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage.address/>
        </template>
        
        <template v-else>
            <div class="mt-[30px]">
                @include('shop::checkout.onepage.addresses.billing')

                @include('shop::checkout.onepage.addresses.shipping')
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

                    addresses: [],

                    countries: [],

                    states: [],

                    isAddressLoading: true,

                    isCustomer: "{{ auth()->guard('customer')->check() }}",

                    isTempAddress: false,
                }
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
                    if (this.isCustomer) {
                        this.$axios.get("{{ route('api.shop.customers.account.addresses.index') }}")
                            .then(response => {
                                this.addresses = response.data.data.map((address, index) => {
                                    let isDefault = address.default_address ? address.default_address : index === 0;

                                    if (isDefault) {
                                        this.forms.billing.address.address_id = address.id;

                                        this.forms.shipping.address.address_id = address.id;
                                    }

                                    return {
                                        ...address,
                                        isSaved: true,
                                        isDefault: isDefault
                                    };
                                });

                                this.isAddressLoading = false;
                            })
                            .catch((error) => {});
                    } else {
                        this.isAddressLoading = false;
                    }
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

                showNewBillingAddressForm() {
                    this.resetBillingAddressForm();

                    this.forms.billing.isNew = true;

                    this.resetPaymentAndShippingMethod();
                },

                handleBillingAddressForm() {
                    if (this.forms.billing.isNew && ! this.forms.billing.address.isSaved) {
                        this.forms.billing.isNew = false;

                        this.isTempAddress = true;

                        this.addresses.push({
                            ...this.forms.billing.address,
                            isSaved: false,
                        });
                    } else if (this.forms.billing.isNew && this.forms.billing.address.isSaved) {
                        this.$axios.post('{{ route("api.shop.customers.account.addresses.store") }}', this.forms.billing.address)
                            .then(response => {
                                this.forms.billing.isNew = false;

                                this.resetBillingAddressForm();
                                
                                this.getCustomerAddresses();
                            })
                            .catch(error => {                 
                                console.log(error);
                            });
                    }
                },

                showNewShippingAddressForm() {
                    this.resetShippingAddressForm();

                    this.forms.shipping.isNew = true;

                    this.resetPaymentAndShippingMethod();
                },

                handleShippingAddressForm() {
                    if (this.forms.shipping.isNew && ! this.forms.shipping.address.isSaved) {
                        this.forms.shipping.isNew = false;

                        this.isTempAddress = true;
                        
                        this.addresses.push({
                            ...this.forms.shipping.address,
                            isSaved: false,
                        });
                    } else if (this.forms.shipping.isNew && this.forms.shipping.address.isSaved) {
                        this.$axios.post('{{ route("api.shop.customers.account.addresses.store") }}', this.forms.shipping.address)
                            .then(response => {
                                this.forms.shipping.isNew = false;

                                this.resetShippingAddressForm();
                                
                                this.getCustomerAddresses();
                            })
                            .catch(error => {                 
                                console.log(error);
                            });
                    }
                },

                store() {
                    if (this.haveStockableItems) {
                        this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;
                        
                        this.$parent.$refs.vShippingMethod.isShippingMethodLoading = true;
                    } else {
                        this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;
    
                        this.$parent.$refs.vPaymentMethod.isPaymentMethodLoading = true;
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
                            if (response.data.data.payment_methods) {
                                this.$parent.$refs.vPaymentMethod.payment_methods = response.data.data.payment_methods;
                                
                                this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = true;
    
                                this.$parent.$refs.vPaymentMethod.isPaymentMethodLoading = false;
                            } else {
                                this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;

                                this.$parent.$refs.vShippingMethod.isShowShippingMethod = true;

                                this.$parent.$refs.vShippingMethod.isShippingMethodLoading = false;
                            }
                            
                            this.$parent.getOrderSummary();
                            
                            if (this.forms.billing.isUsedForShipping
                                && this.forms.billing.address_id
                            ) {
                                this.getCustomerAddresses();
                            }
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