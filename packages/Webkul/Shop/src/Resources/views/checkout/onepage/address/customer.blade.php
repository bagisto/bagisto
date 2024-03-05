{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.before') !!}

<!-- Customer Address Vue Component -->
<v-checkout-address-customer :cart="cart"></v-checkout-address-customer>

{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-customer-template"
    >
        <!-- Address Form -->
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, store)">
                <!-- Billing Address Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-medium max-sm:text-xl">
                        @lang('shop::app.checkout.onepage.address.billing-address')
                    </h2>

                    <span
                        class="flex justify-end cursor-pointer"
                        v-show="isFormOpen.billing"
                        @click="isFormOpen.billing = false"
                    >
                        <span class="icon-arrow-left text-2xl"></span>

                        @lang('shop::app.checkout.onepage.address.back')
                    </span>
                </div>

                <!-- Saved Customer Addresses Cards -->
                <template v-if="! isFormOpen.billing">
                    <div class="grid gap-5 grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                        <div
                            class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                            v-for="(address, index) in customerSavedAddresses"
                        >
                            <v-field
                                type="radio"
                                name="billing_address_id"
                                :value="address.id"
                                :id="`billing_address_id_${address.id}`"
                                class="hidden peer"
                                rules="required"
                                label="@lang('shop::app.checkout.onepage.address.billing-address')"
                                v-slot="{ field }"
                            >
                                <input
                                    type="radio"
                                    name="billing_address_id"
                                    :value="address.id"
                                    :id="`billing_address_id_${address.id}`"
                                    class="hidden peer"
                                    v-bind="field"
                                    :checked="address.id === customer.applied.selectedBillingAddressId"
                                />
                            </v-field>

                            <label
                                class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                :for="`billing_address_id_${address.id}`"
                            >
                            </label>

                            <!-- Edit Icon -->
                            <span
                                class="icon-edit absolute ltr:right-14 rtl:left-14 top-5 text-2xl cursor-pointer"
                                @click="openUpdateOrCreateCustomerAddressForm(address)"
                            ></span>

                            <!-- Details -->
                            <label
                                :for="`billing_address_id_${address.id}`"
                                class="block p-5 rounded-xl cursor-pointer"
                            >
                                <span class="icon-checkout-address text-6xl text-navyBlue"></span>

                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium">
                                        @{{ address.firstName }} @{{ address.lastName }}

                                        <span v-if="address.companyName">(@{{ address.companyName }})</span>
                                    </p>
                                </div>

                                <p class="mt-6 text-sm text-[#6E6E6E]">
                                    <template v-if="address.address1">
                                        @{{ address.address1 }},
                                    </template>

                                    <template v-if="address.address2">
                                        @{{ address.address2 }},
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
                            @click="isFormOpen.billing = true"
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
                    <v-error-message
                        class="text-red-500 text-xs italic"
                        name="billing_address_id"
                    >
                    </v-error-message>
                </template>

                <!-- Billing Address Form -->
                <template v-else>
                    <!-- Billing Address Form -->
                    <v-checkout-address-form
                        control-name="billing"
                    ></v-checkout-address-form>
                </template>

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

                        <span
                            class="flex justify-end cursor-pointer"
                            v-show="isFormOpen.shipping"
                            @click="isFormOpen.shipping = false"
                        >
                            <span class="icon-arrow-left text-2xl"></span>

                            @lang('shop::app.checkout.onepage.address.back')
                        </span>
                    </div>

                    <!-- Saved Customer Addresses Cards -->
                    <template v-if="! isFormOpen.shipping">
                        <div class="grid gap-5 grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-4">
                            <div
                                class="relative max-w-[414px] p-0 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap select-none cursor-pointer"
                                v-for="(address, index) in customerSavedAddresses"
                            >
                                <v-field
                                    type="radio"
                                    name="shipping_address_id"
                                    :value="address.id"
                                    :id="`shipping_address_id_${address.id}`"
                                    class="hidden peer"
                                    rules="required"
                                    label="@lang('shop::app.checkout.onepage.address.shipping-address')"
                                    v-slot="{ field }"
                                >
                                    <input
                                        type="radio"
                                        name="shipping_address_id"
                                        :value="address.id"
                                        :id="`shipping_address_id_${address.id}`"
                                        class="hidden peer"
                                        v-bind="field"
                                        :checked="address.id === customer.applied.selectedBillingAddressId"
                                    />
                                </v-field>

                                <label
                                    class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                    :for="`shipping_address_id_${address.id}`"
                                >
                                </label>

                                <!-- Edit Icon -->
                                <span
                                    class="icon-edit absolute ltr:right-14 rtl:left-14 top-5 text-2xl cursor-pointer"
                                    @click="openUpdateOrCreateCustomerAddressForm(address)"
                                ></span>

                                <!-- Details -->
                                <label
                                    :for="`shipping_address_id_${address.id}`"
                                    class="block p-5 rounded-xl cursor-pointer"
                                >
                                    <span class="icon-checkout-address text-6xl text-navyBlue"></span>

                                    <div class="flex justify-between items-center">
                                        <p class="text-base font-medium">
                                            @{{ address.firstName }} @{{ address.lastName }}

                                            <span v-if="address.companyName">(@{{ address.companyName }})</span>
                                        </p>
                                    </div>

                                    <p class="mt-6 text-sm text-[#6E6E6E]">
                                        <template v-if="address.address1">
                                            @{{ address.address1 }},
                                        </template>

                                        <template v-if="address.address2">
                                            @{{ address.address2 }},
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
                                @click="isFormOpen.shipping = true"
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
                        <v-error-message
                            class="text-red-500 text-xs italic"
                            name="shipping_address_id"
                        >
                        </v-error-message>
                    </template>

                    <!-- Billing Address Form -->
                    <template v-else>

                    </template>
                </div>

                <!-- Proceed Button -->
                <div class="flex justify-end mt-4">
                    <x-shop::button
                        class="primary-button py-3 px-11 rounded-2xl"
                        :title="trans('shop::app.checkout.onepage.address.proceed')"
                        ::loading="isLoading"
                        ::disabled="isLoading"
                    />
                </div>
            </form>
        </x-shop::form>
    </script>

    <script type="module">
        app.component('v-checkout-address-customer', {
            template: '#v-checkout-address-customer-template',

            props: ['cart'],

            data() {
                return {
                    customerSavedAddresses: [],

                    useBillingAddressForShipping: true,

                    isFormOpen: {
                        billing: false,

                        shipping: false,
                    },

                    isLoading: false,
                }
            },

            mounted() {
                this.getCustomerSavedAddresses();
            },

            methods: {
                getCustomerSavedAddresses() {
                    this.$axios.get('{{ route('api.shop.customers.account.addresses.index') }}')
                        .then(response => {
                            this.customerSavedAddresses = response.data.data;

                            this.customer.isAddressLoading = false;
                        })
                        .catch(() => {});
                },

                openAddressForm(type) {

                },

                store(params, { setErrors }) {
                    
                },
            }
        });
    </script>
@endPushOnce