{!! view_render_event('bagisto.admin.sales.order.create.cart.address.before') !!}

<!-- Vue JS Component -->
<v-cart-addresses
    :cart="cart"
    @processing="stepForward"
    @processed="stepProcessed"
></v-cart-addresses>

{!! view_render_event('bagisto.admin.sales.order.create.cart.address.after') !!}

@include('admin::sales.orders.create.cart.address.form')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-addresses-template"
    >
        <div
            class="box-shadow rounded bg-white dark:bg-gray-900"
            id="address-step-container"
        >
            <div class="flex items-center border-b p-4 dark:border-gray-800">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.sales.orders.create.cart.address.title')
                </p>
            </div>

            <!-- Saved Customer Addresses Cards -->
            <div class="p-4">
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addAddressToCart)">
                        <!-- Billing Address Header -->
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-base font-medium text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.create.cart.address.billing-address')
                            </p>

                            <p
                                class="cursor-pointer text-blue-600 transition-all hover:underline"
                                @click="activeAddressForm = 'billing'; selectedAddressForEdit = null; $refs.updateCreateModal.open()"
                                v-if="! cart.billing_address"
                            >
                                @lang('admin::app.sales.orders.create.cart.address.add-btn')
                            </p>
                        </div>

                        <!-- Billing Address Container -->
                        <div class="flex flex-wrap gap-8">
                            <!-- Address Cards -->
                            <div
                                class="flex gap-2"
                                v-for="address in customerSavedAddresses.billing"
                            >
                                <x-admin::form.control-group class="!mb-0 flex items-center gap-2.5 self-start">
                                    <x-admin::form.control-group.control
                                        type="radio"
                                        name="billing.id"
                                        ::id="`billing_address_id_${address.id}`"
                                        ::for="`billing_address_id_${address.id}`"
                                        ::key="`billing_address_id_${address.id}`"
                                        ::value="address.id"
                                        v-model="selectedAddresses.billing_address_id"
                                        rules="required"
                                        label="{{ trans('admin::app.sales.orders.create.cart.address.billing-address') }}"
                                    />
                                </x-admin::form.control-group>

                                <div class="grid gap-3">
                                    <label
                                        class="grid cursor-pointer gap-1.5"
                                        :for="`billing_address_id_${address.id}`"
                                    >
                                        <p class="text-base text-gray-800 dark:text-white">
                                            @{{ address.first_name + ' ' + address.last_name }}

                                            <template v-if="address.company_name">
                                                (@{{ address.company_name }})
                                            </template>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ address.email }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{
                                                [
                                                    address.address.join(', '),
                                                    address.city,
                                                    address.state
                                                ].filter(string => string !== '').join(', ')
                                            }}
                                        </p>
                                    </label>

                                    <!-- Edit Action -->
                                    <p
                                        class="cursor-pointer text-blue-600 transition-all hover:underline"
                                        @click="
                                            selectedAddressForEdit = address;
                                            activeAddressForm = 'billing';
                                            saveAddress = address.address_type == 'customer';
                                            $refs.updateCreateModal.open()
                                        "
                                    >
                                        @lang('admin::app.sales.orders.create.cart.address.edit-btn')
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message Block -->
                        <x-admin::form.control-group.error name="billing.id" />

                        <!-- Shipping Address Block if have stockable items -->
                        <template v-if="cart.have_stockable_items">
                            <!-- Use for Shipping Checkbox -->
                            <x-admin::form.control-group class="!mb-0 mt-5 flex items-center gap-2.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    name="billing.use_for_shipping"
                                    id="use_for_shipping"
                                    for="use_for_shipping"
                                    value="1"
                                    @change="useBillingAddressForShipping = ! useBillingAddressForShipping"
                                    ::checked="!! useBillingAddressForShipping"
                                />

                                <label
                                    class="cursor-pointer select-none text-base text-[#6E6E6E] dark:text-gray-400 max-sm:text-xs ltr:pl-0 rtl:pr-0"
                                    for="use_for_shipping"
                                >
                                    @lang('admin::app.sales.orders.create.cart.address.same-as-billing')
                                </label>
                            </x-admin::form.control-group>


                            <!-- Customer Shipping Address -->
                            <div
                                class="mt-8"
                                v-if="! useBillingAddressForShipping"
                            >
                                <!-- Shipping Address Header -->
                                <div class="mb-4 flex items-center justify-between">
                                    <p class="text-base font-medium text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.create.cart.address.shipping-address')
                                    </p>

                                    <p
                                        class="cursor-pointer text-blue-600 transition-all hover:underline"
                                        @click="
                                            activeAddressForm = 'shipping';
                                            selectedAddressForEdit = null;
                                            $refs.updateCreateModal.open()
                                        "
                                        v-if="! cart.shipping_address"
                                    >
                                        @lang('admin::app.sales.orders.create.cart.address.add-btn')
                                    </p>
                                </div>

                                <!-- Shipping Address Container -->
                                <div class="flex flex-wrap gap-8">
                                    <!-- Address Cards -->
                                    <div
                                        class="flex gap-2"
                                        v-for="address in customerSavedAddresses.shipping"
                                    >
                                        <x-admin::form.control-group class="!mb-0 flex items-center gap-2.5 self-start">
                                            <x-admin::form.control-group.control
                                                type="radio"
                                                name="shipping.id"
                                                ::id="`shipping_address_id_${address.id}`"
                                                ::for="`shipping_address_id_${address.id}`"
                                                ::key="`shipping_address_id_${address.id}`"
                                                ::value="address.id"
                                                v-model="selectedAddresses.shipping_address_id"
                                                rules="required"
                                                label="{{ trans('admin::app.sales.orders.create.cart.address.shipping-address') }}"
                                            />
                                        </x-admin::form.control-group>

                                        <div class="grid gap-3">
                                            <label
                                                class="grid cursor-pointer gap-1.5"
                                                :for="`shipping_address_id_${address.id}`"
                                            >
                                                <p class="text-base text-gray-800 dark:text-white">
                                                    @{{ address.first_name + ' ' + address.last_name }}

                                                    <template v-if="address.company_name">
                                                        (@{{ address.company_name }})
                                                    </template>
                                                </p>

                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @{{ address.email }}
                                                </p>

                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @{{
                                                        [
                                                            address.address.join(', '),
                                                            address.city,
                                                            address.state
                                                        ].filter(string => string !== '').join(', ')
                                                    }}
                                                </p>
                                            </label>

                                            <!-- Edit Action -->
                                            <p
                                                class="cursor-pointer text-blue-600 transition-all hover:underline"
                                                @click="
                                                    selectedAddressForEdit = address;
                                                    activeAddressForm = 'shipping';
                                                    saveAddress = address.address_type == 'customer';
                                                    $refs.updateCreateModal.open()
                                                "
                                            >
                                                @lang('admin::app.sales.orders.create.cart.address.edit-btn')
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message Block -->
                                <x-admin::form.control-group.error name="shipping.id" />
                            </div>
                        </template>

                        <!-- Proceed Button -->
                        <div
                            class="mt-4 flex justify-end"
                            v-if="customerSavedAddresses.billing.length"
                        >
                            <x-admin::button
                                class="primary-button"
                                :title="trans('shop::app.checkout.onepage.address.proceed')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />
                        </div>
                    </form>
                </x-shop::form>
            </div>

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, updateOrCreateAddress)">
                    <!-- Drawer Form -->
                    <x-admin::drawer
                        width="350px"
                        ref="updateCreateModal"
                    >
                        <!-- Drawer Header -->
                        <x-slot:header>
                            <p class="py-2 text-lg font-bold text-gray-800 dark:text-white">
                                <template v-if="activeAddressForm == 'billing'">
                                    @lang('admin::app.sales.orders.create.cart.address.billing-address')
                                </template>

                                <template v-else>
                                    @lang('admin::app.sales.orders.create.cart.address.shipping-address')
                                </template>
                            </p>
                        </x-slot>

                        <!--Drawer Content -->
                        <x-slot:content>
                            <!-- Address Form Vue Component -->
                            <v-checkout-address-form
                                :control-name="activeAddressForm"
                                :address="selectedAddressForEdit || undefined"
                            ></v-checkout-address-form>

                            <!-- Save Address to Address Book Checkbox -->
                            <x-admin::form.control-group class="flex items-center gap-2.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    ::name="activeAddressForm + '.save_address'"
                                    id="save_address"
                                    for="save_address"
                                    value="1"
                                    v-model="saveAddress"
                                    @change="saveAddress = ! saveAddress"
                                />

                                <label
                                    class="cursor-pointer select-none text-base text-[#6E6E6E] max-sm:text-xs ltr:pl-0 rtl:pr-0"
                                    for="save_address"
                                >
                                    @lang('shop::app.checkout.onepage.address.save-address')
                                </label>
                            </x-admin::form.control-group>

                            <x-admin::button
                                class="primary-button w-full max-w-full"
                                :title="trans('shop::app.checkout.onepage.address.save')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />
                        </x-slot>
                    </x-admin::drawer>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-cart-addresses', {
            template: '#v-cart-addresses-template',

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
                this.initializeAddresses('billing', @json($addresses));

                this.initializeAddresses('shipping', @json($addresses));
            },

            methods: {
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
                                });
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

                    this.$refs.updateCreateModal.close();

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

                            this.$refs.updateCreateModal.close();

                            this.activeAddressForm = null;
                        }
                    });
                },

                removeAddressFromList(params) {
                    this.customerSavedAddresses[this.activeAddressForm] = this.customerSavedAddresses[this.activeAddressForm].filter(address => address.id != params.id);
                },

                createCustomerAddress(params, { setErrors }) {
                    this.isStoring = true;

                    return this.$axios.post('{{ route('admin.customers.customers.addresses.store', $cart->customer_id) }}', params)
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

                    params['default_address'] = this.selectedAddressForEdit.default_address;

                    return this.$axios.put('{{ route('admin.customers.customers.addresses.update', ':id') }}'.replace(':id', id), params)
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
                            use_for_shipping: this.useBillingAddressForShipping,
                        },
                    };

                    if (params.shipping !== undefined) {
                        payload.shipping = this.getSelectedAddress('shipping', params.shipping.id);
                    }

                    this.isStoring = true;

                    this.moveToNextStep();

                    this.$axios.post("{{ route('admin.sales.cart.addresses.store', $cart->id) }}", payload)
                        .then((response) => {
                            this.isStoring = false;

                            if (this.cart.have_stockable_items) {
                                this.$emit('processed', response.data.data.shippingMethods);
                            } else {
                                this.$emit('processed', response.data.data.payment_methods);
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
                            } else {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            }
                        });
                },

                getSelectedAddress(type, id) {
                    let address = Object.assign({}, this.customerSavedAddresses[type].find(address => address.id == id));

                    if (id == 0) {
                        address.id = null;
                    }

                    return {
                        ...address,
                        default_address: 0,
                    };
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
