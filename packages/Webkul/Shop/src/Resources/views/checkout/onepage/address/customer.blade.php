{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.before') !!}

<!-- Customer Address Vue Component -->
<v-checkout-address-customer
    :cart="cart"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Billing Address Shimmer -->
    <x-shop::shimmer.checkout.onepage.address />
</v-checkout-address-customer>

{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-customer-template"
    >
        <template v-if="isLoading">
            <!-- Billing Address Shimmer -->
            <x-shop::shimmer.checkout.onepage.address />
        </template>

        <template v-else>
            <!-- Saved Addresses -->
            <template v-if="! activeAddressForm && customerSavedAddresses.billing.length">
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addAddressToCart)">
                        <!-- Billing Address Header -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-medium max-sm:text-xl">
                                @lang('shop::app.checkout.onepage.address.billing-address')
                            </h2>
                        </div>

                        <!-- Saved Customer Addresses Cards -->
                        <div class="grid gap-5 grid-cols-2 mb-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                            <div
                                class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                                v-for="address in customerSavedAddresses.billing"
                            >
                                <!-- Actions -->
                                <div class="flex gap-2 absolute top-5 ltr:right-5 rtl:left-5">
                                    <x-shop::form.control-group class="flex items-center gap-2.5 !mb-0">
                                        <x-shop::form.control-group.control
                                            type="radio"
                                            name="billing.id"
                                            ::id="`billing_address_id_${address.id}`"
                                            ::for="`billing_address_id_${address.id}`"
                                            ::value="address.id"
                                            v-model="selectedAddresses.billing_address_id"
                                            rules="required"
                                            label="{{ trans('shop::app.checkout.onepage.address.billing-address') }}"
                                        />
                                    </x-shop::form.control-group>

                                    <!-- Edit Icon -->
                                    <span
                                        class="icon-edit text-2xl cursor-pointer"
                                        @click="
                                            selectedAddressForEdit = address;
                                            activeAddressForm = 'billing';
                                            saveAddress = address.address_type == 'customer'
                                        "
                                    ></span>
                                </div>

                                <!-- Details -->
                                <label
                                    class="block p-5 rounded-xl cursor-pointer"
                                    :for="`billing_address_id_${address.id}`"
                                >
                                    <span class="icon-checkout-address text-6xl text-navyBlue"></span>

                                    <div class="flex justify-between items-center">
                                        <p class="text-base font-medium">
                                            @{{ address.first_name + ' ' + address.last_name }}

                                            <template v-if="address.company_name">
                                                (@{{ address.company_name }})
                                            </template>
                                        </p>
                                    </div>

                                    <p class="mt-6 text-sm text-[#6E6E6E]">
                                        <template v-if="address.address">
                                            @{{ address.address.join(', ') }},
                                        </template>

                                        @{{ address.city }},
                                        @{{ address.state }}, @{{ address.country }},
                                        @{{ address.postcode }}
                                    </p>
                                </label>
                            </div>

                            <!-- New Address Card -->
                            <div
                                class="flex justify-center items-center max-w-[414px] p-5 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap cursor-pointer"
                                @click="activeAddressForm = 'billing'"
                                v-if="! cart.billing_address"
                            >
                                <div
                                    class="flex gap-x-2.5 items-center"
                                    role="button"
                                    tabindex="0"
                                >
                                    <span
                                        class="icon-plus p-2.5 border border-black rounded-full text-3xl"
                                        role="presentation"
                                    ></span>

                                    <p class="text-base">@lang('shop::app.checkout.onepage.address.add-new-address')</p>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message Block -->
                        <x-shop::form.control-group.error name="billing.id" />

                        <!-- Shipping Address Block if have stockable items -->
                        <template v-if="cart.have_stockable_items">
                            <!-- Use for Shipping Checkbox -->
                            <x-shop::form.control-group class="flex items-center gap-2.5 mt-5 !mb-0">
                                <x-shop::form.control-group.control
                                    type="checkbox"
                                    name="billing.use_for_shipping"
                                    id="use_for_shipping"
                                    for="use_for_shipping"
                                    value="1"
                                    @change="useBillingAddressForShipping = ! useBillingAddressForShipping"
                                    ::checked="!! useBillingAddressForShipping"
                                />

                                <label
                                    class="text-base text-[#6E6E6E] max-sm:text-xs ltr:pl-0 rtl:pr-0 select-none cursor-pointer"
                                    for="use_for_shipping"
                                >
                                    @lang('shop::app.checkout.onepage.address.same-as-billing')
                                </label>
                            </x-shop::form.control-group>


                            <!-- Customer Shipping Address -->
                            <div
                                class="mt-8"
                                v-if="! useBillingAddressForShipping"
                            >
                                <!-- Shipping Address Header -->
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-medium max-sm:text-xl">
                                        @lang('shop::app.checkout.onepage.address.shipping-address')
                                    </h2>
                                </div>

                                <!-- Saved Customer Addresses Cards -->
                                <div class="grid gap-5 grid-cols-2 mb-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                                    <div
                                        class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                                        v-for="address in customerSavedAddresses.shipping"
                                    >
                                        <!-- Actions -->
                                        <div class="flex gap-5 absolute top-5 ltr:right-5 rtl:left-5">
                                            <x-shop::form.control-group class="flex items-center gap-2.5 !mb-0">
                                                <x-shop::form.control-group.control
                                                    type="radio"
                                                    name="shipping.id"
                                                    ::id="`shipping_address_id_${address.id}`"
                                                    ::for="`shipping_address_id_${address.id}`"
                                                    ::value="address.id"
                                                    v-model="selectedAddresses.shipping_address_id"
                                                    rules="required"
                                                    label="{{ trans('shop::app.checkout.onepage.address.shipping-address') }}"
                                                />
                                            </x-shop::form.control-group>

                                            <!-- Edit Icon -->
                                            <span
                                                class="icon-edit text-2xl cursor-pointer"
                                                @click="
                                                    selectedAddressForEdit = address;
                                                    activeAddressForm = 'shipping';
                                                    saveAddress = address.address_type == 'customer'
                                                "
                                            ></span>
                                        </div>

                                        <!-- Details -->
                                        <label
                                            class="block p-5 rounded-xl cursor-pointer"
                                            :for="`shipping_address_id_${address.id}`"
                                        >
                                            <span class="icon-checkout-address text-6xl text-navyBlue"></span>

                                            <div class="flex justify-between items-center">
                                                <p class="text-base font-medium">
                                                    @{{ address.first_name + ' ' + address.last_name }}

                                                    <template v-if="address.company_name">
                                                        (@{{ address.company_name }})
                                                    </template>
                                                </p>
                                            </div>

                                            <p class="mt-6 text-sm text-[#6E6E6E]">
                                                <template v-if="address.address">
                                                    @{{ address.address.join(', ') }},
                                                </template>

                                                @{{ address.city }},
                                                @{{ address.state }}, @{{ address.country }},
                                                @{{ address.postcode }}
                                            </p>
                                        </label>
                                    </div>

                                    <!-- New Address Card -->
                                    <div
                                        class="flex justify-center items-center max-w-[414px] p-5 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap cursor-pointer"
                                        @click="selectedAddressForEdit = null; activeAddressForm = 'shipping'"
                                        v-if="! cart.shipping_address"
                                    >
                                        <div
                                            class="flex gap-x-2.5 items-center"
                                            role="button"
                                            tabindex="0"
                                        >
                                            <span
                                                class="icon-plus p-2.5 border border-black rounded-full text-3xl"
                                                role="presentation"
                                            ></span>

                                            <p class="text-base">@lang('shop::app.checkout.onepage.address.add-new-address')</p>
                                        </div>
                                    </div>
                                </div>

                                <x-shop::form.control-group.error name="shipping.id" />
                            </div>
                        </template>

                        <!-- Proceed Button -->
                        <div class="flex justify-end mt-4">
                            <x-shop::button
                                class="primary-button py-3 px-11 rounded-2xl"
                                :title="trans('shop::app.checkout.onepage.address.proceed')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />
                        </div>
                    </form>
                </x-shop::form>
            </template>

            <!-- Create/Edit Address Form -->
            <template v-else>
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, updateOrCreateAddress)">
                        <!-- Billing Address Header -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-medium max-sm:text-xl">
                                <template v-if="activeAddressForm == 'billing'">
                                    @lang('shop::app.checkout.onepage.address.billing-address')
                                </template>

                                <template v-else>
                                    @lang('shop::app.checkout.onepage.address.shipping-address')
                                </template>
                            </h2>

                            <span
                                class="flex justify-end cursor-pointer"
                                v-show="customerSavedAddresses.billing.length && ['billing', 'shipping'].includes(activeAddressForm)"
                                @click="selectedAddressForEdit = null; activeAddressForm = null"
                            >
                                <span class="icon-arrow-left text-2xl"></span>

                                @lang('shop::app.checkout.onepage.address.back')
                            </span>
                        </div>
                        
                        <!-- Address Form Vue Component -->
                        <v-checkout-address-form
                            :control-name="activeAddressForm"
                            :address="selectedAddressForEdit || undefined"
                        ></v-checkout-address-form>

                        <!-- Save Address to Address Book Checkbox -->
                        <x-shop::form.control-group class="flex items-center gap-2.5 !mb-0">
                            <x-shop::form.control-group.control
                                type="checkbox"
                                ::name="activeAddressForm + '.save_address'"
                                id="save_address"
                                for="save_address"
                                value="1"
                                v-model="saveAddress"
                                @change="saveAddress = ! saveAddress"
                            />

                            <label
                                class="text-base text-[#6E6E6E] max-sm:text-xs ltr:pl-0 rtl:pr-0 select-none cursor-pointer"
                                for="save_address"
                            >
                                @lang('shop::app.checkout.onepage.address.save-address')
                            </label>
                        </x-shop::form.control-group>

                        <!-- Save Button -->
                        <div class="flex justify-end mt-4">
                            <x-shop::button
                                class="primary-button py-3 px-11 rounded-2xl"
                                :title="trans('shop::app.checkout.onepage.address.save')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />
                        </div>
                    </form>
                </x-shop::form>
            </template>
        </template>
    </script>

    <script type="module">
        app.component('v-checkout-address-customer', {
            template: '#v-checkout-address-customer-template',

            props: ['cart'],

            emits: ['processing', 'processed'],

            data() {
                return {
                    customerSavedAddresses: {
                        'billing': [],
                        
                        'shipping': [],
                    },

                    useBillingAddressForShipping: true,

                    activeAddressForm: null,

                    selectedAddressForEdit: null,

                    saveAddress: false,

                    selectedAddresses: {
                        billing_address_id: null,

                        shipping_address_id: null,
                    },

                    isLoading: true,

                    isStoring: false,
                }
            },

            created() {
                if (this.cart.billing_address) {
                    this.useBillingAddressForShipping = this.cart.billing_address.use_for_shipping;
                }
            },

            mounted() {
                this.getCustomerSavedAddresses();
            },

            methods: {
                getCustomerSavedAddresses() {
                    this.$axios.get('{{ route('shop.api.customers.account.addresses.index') }}')
                        .then(response => {
                            this.initializeAddresses('billing', structuredClone(response.data.data));

                            this.initializeAddresses('shipping', structuredClone(response.data.data));

                            if (! this.customerSavedAddresses.billing.length) {
                                this.activeAddressForm = 'billing';
                            }

                            this.isLoading = false;
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },

                initializeAddresses(type, addresses) {
                    this.customerSavedAddresses[type] = addresses;

                    let cartAddress = this.cart[type + '_address'];

                    if (! cartAddress) {
                        addresses.forEach(address => {
                            if (address.default_address) {
                                this.selectedAddresses[type + '_address_id'] = address.id;
                            }
                        });

                        return addresses;
                    }

                    if (cartAddress.parent_address_id) {
                        addresses.forEach(address => {
                            if (address.id == cartAddress.parent_address_id) {
                                this.selectedAddresses[type + '_address_id'] = address.id;
                            }
                        });
                    } else {
                        this.selectedAddresses[type + '_address_id'] = cartAddress.id;
                        
                        addresses.unshift(cartAddress);
                    }

                    return addresses;
                },

                updateOrCreateAddress(params, { setErrors }) {
                    this.$emit('processing', 'address');

                    params = params[this.activeAddressForm];

                    let address = this.customerSavedAddresses[this.activeAddressForm].find(address => {
                        return address.id == params.id;
                    });

                    if (! address) {
                        if (params.save_address) {
                            this.createCustomerAddress(params, { setErrors })
                                .then((response) => {
                                    this.addAddressToList(response.data.data);
                                })
                                .catch((error) => {});
                        } else {
                            this.addAddressToList(params);
                        }

                        return;
                    }

                    if (params.save_address) {
                        if (address.address_type == 'customer') {
                            this.updateCustomerAddress(params.id, params, { setErrors })
                                .then((response) => {
                                    this.updateAddressInList(response.data.data);
                                })
                                .catch((error) => {});
                        } else {
                            this.removeAddressFromList(params);

                            this.createCustomerAddress(params, { setErrors })
                                .then((response) => {
                                    this.addAddressToList(response.data.data);
                                })
                                .catch((error) => {});
                        }
                    } else {
                        this.updateAddressInList(params);
                    }
                },

                addAddressToList(address) {
                    this.cart[this.activeAddressForm + '_address'] = address;

                    this.customerSavedAddresses[this.activeAddressForm].unshift(address);

                    this.selectedAddresses[this.activeAddressForm + '_address_id'] = address.id;

                    this.activeAddressForm = null;
                },

                updateAddressInList(params) {
                    this.customerSavedAddresses[this.activeAddressForm].forEach((address, index) => {
                        if (address.id == params.id) {
                            params = {
                                ...address,
                                ...params,
                            };

                            this.cart[this.activeAddressForm + '_address'] = params;

                            this.customerSavedAddresses[this.activeAddressForm][index] = params;

                            this.selectedAddresses[this.activeAddressForm + '_address_id'] = params.id;

                            this.activeAddressForm = null;
                        }
                    });
                },

                removeAddressFromList(params) {
                    this.customerSavedAddresses[this.activeAddressForm] = this.customerSavedAddresses[this.activeAddressForm].filter(address => address.id != params.id);
                },

                createCustomerAddress(params, { setErrors }) {
                    this.isStoring = true;

                    return this.$axios.post('{{ route('shop.api.customers.account.addresses.store') }}', params)
                        .then((response) => {
                            this.isStoring = false;

                            return response;
                        })
                        .catch(error => {
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                let errors = {};

                                Object.keys(error.response.data.errors).forEach(key => {
                                    errors[this.activeAddressForm + '.' + key] = error.response.data.errors[key];
                                });

                                setErrors(errors);
                            }

                            return Promise.reject(error);
                        });
                },

                updateCustomerAddress(id, params, { setErrors }) {
                    this.isStoring = true;

                    return this.$axios.put('{{ route('shop.api.customers.account.addresses.update') }}/' + id, params)
                        .then((response) => {
                            this.isStoring = false;

                            return response;
                        })
                        .catch(error => {
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                let errors = {};

                                Object.keys(error.response.data.errors).forEach(key => {
                                    errors[this.activeAddressForm + '.' + key] = error.response.data.errors[key];
                                });

                                setErrors(errors);
                            }

                            return Promise.reject(error);
                        });
                },

                addAddressToCart(params, { setErrors }) {
                    let payload = {
                        billing: {
                            ...this.getSelectedAddress('billing', params.billing.id),
                            ...{
                                use_for_shipping: this.useBillingAddressForShipping
                            }
                        },
                    };

                    if (params.shipping !== undefined) {
                        payload.shipping = this.getSelectedAddress('shipping', params.shipping.id);
                    }

                    this.isStoring = true;

                    this.moveToNextStep();

                    this.$axios.post('{{ route('shop.checkout.onepage.addresses.store') }}', payload)
                        .then((response) => {
                            this.isStoring = false;

                            if (response.data.data.redirect_url) {
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                if (this.cart.have_stockable_items) {
                                    this.$emit('processed', response.data.data.shippingMethods);
                                } else {
                                    this.$emit('processed', response.data.data.payment_methods);
                                }
                            }
                        })
                        .catch(error => {
                            this.isStoring = false;

                            this.$emit('processing', 'address');

                            if (error.response.status == 422) {
                                const billingRegex = /^billing\./;

                                if (Object.keys(error.response.data.errors).some(key => billingRegex.test(key))) {
                                    setErrors({
                                        'billing.id': error.response.data.message
                                    });
                                } else {
                                    setErrors({
                                        'shipping.id': error.response.data.message
                                    });
                                }
                            }
                        });
                },

                getSelectedAddress(type, id) {
                    let address = Object.assign({}, this.customerSavedAddresses[type].find(address => address.id == id));

                    if (id == 0) {
                        address.id = null;
                    }

                    return address;
                },

                moveToNextStep() {
                    if (this.cart.have_stockable_items) {
                        this.$emit('processing', 'shipping');
                    } else {
                        this.$emit('processing', 'payment');
                    }
                },
            }
        });
    </script>
@endPushOnce