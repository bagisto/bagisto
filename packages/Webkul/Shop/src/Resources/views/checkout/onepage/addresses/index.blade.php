{!! view_render_event('bagisto.shop.checkout.addresses.before') !!}

<v-checkout-addresses ref="vCheckoutAddress"></v-checkout-addresses>

{!! view_render_event('bagisto.shop.checkout.addresses.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkout-addresses-template">
        <template v-if="isAddressLoading">
            <x-shop::shimmer.checkout.onepage.address></x-shop::shimmer.checkout.onepage.address>
        </template>
        
        <template v-else>
            <div class="mt-[30px]">
                @include('shop::checkout.onepage.addresses.billing')

                @include('shop::checkout.onepage.addresses.shipping')

                <div v-if="! forms.billing.isNew && ! forms.shipping.isNew">
                    <div class="flex justify-end mt-4 mb-4">
                        <button
                            class="block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                            @click="store"
                        >
                            @lang('Confirm')
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
         app.component('v-checkout-addresses', {
            template: '#v-checkout-addresses-template',

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

                getCustomerAddresses() {
                    if (this.isCustomer) {
                        this.$axios.get("{{ route('api.shop.customers.account.addresses.index') }}")
                            .then(response => {
                                this.addresses = response.data.data.map(address => {
                                    return {
                                        ...address,
                                        isSaved: true,
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
                    this.$axios.get("{{ route('shop.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(function (error) {});
                },

                getCountryStates() {
                    this.$axios.get("{{ route('shop.countries.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(function (error) {});
                },

                showNewBillingAddressForm() {
                    this.resetBillingAddressForm();

                    this.forms.billing.isNew = true;

                    this.resetPaymentAndShipping();
                },

                handleBillingAddressForm() {
                    if (this.forms.billing.isNew && ! this.forms.billing.address.isSaved) {
                        this.forms.billing.isNew = false;
                        
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

                    this.resetPaymentAndShipping();
                },

                handleShippingAddressForm() {
                    if (this.forms.shipping.isNew && ! this.forms.shipping.address.isSaved) {
                        this.forms.shipping.isNew = false;
                        
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
                            if (response.data.data.paymentMethods) {
                                this.$parent.$refs.vPaymentMethod.paymentMethods = response.data.data.paymentMethods;
                                
                                this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = true;
    
                                this.$parent.$refs.vPaymentMethod.isPaymentLoading = false;
                            } else {
                                this.$parent.$refs.vShippingMethod.shippingMethods = response.data.data.shippingMethods;

                                this.$parent.$refs.vShippingMethod.isShowShippingMethod = true;

                                this.$parent.$refs.vShippingMethod.isShippingLoading = false;
                            }
                            
                            if (this.forms.billing.isUsedForShipping) {
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

                resetPaymentAndShipping() {
                    this.$parent.$refs.vShippingMethod.isShowShippingMethod = false;

                    this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;
                },
            },
        });
    </script>
@endPushOnce