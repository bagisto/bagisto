{!! view_render_event('bagisto.admin.sales.order.create.cart.summary.before') !!}

<v-cart-summary
    :cart="cart"
    @coupon-applied="setCart"
    @coupon-removed="setCart"
></v-cart-summary>

{!! view_render_event('bagisto.admin.sales.order.create.cart.summary.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-summary-template"
    >
        <div
            class="bg-white dark:bg-gray-900 rounded box-shadow"
            id="review-step-container"
        >
            <div class="flex items-center p-4 border-b dark:border-gray-800">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('admin::app.sales.orders.create.cart.summary.title')
                </p>
            </div>

            <!-- Cart Totals -->
            <div class="grid w-full gap-2.5 justify-end p-4 border-b dark:border-gray-800">
                <div class="grid gap-4">
                    <!-- Sub Total -->
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.sub_total.before') !!}

                    <div class="row grid-cols-2 grid-rows-1 grid gap-4 text-right justify-between">
                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @lang('admin::app.sales.orders.create.cart.summary.sub-total')
                        </p>

                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @{{ cart.base_sub_total }}
                        </p>
                    </div>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.sub_total.after') !!}


                    <!-- Taxes -->
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.tax.before') !!}

                    <div
                        class="row grid-cols-2 grid-rows-1 grid gap-4 text-right justify-between"
                        v-for="(amount, index) in cart.base_tax_amounts"
                        v-if="parseFloat(cart.base_tax_total)"
                    >
                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @lang('admin::app.sales.orders.create.cart.summary.tax') (@{{ index }})%
                        </p>

                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @{{ amount }}
                        </p>
                    </div>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.tax.after') !!}

                    <!-- Shipping Rates -->
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.delivery_charges.before') !!}

                    <div
                        class="row grid-cols-2 grid-rows-1 grid gap-4 text-right justify-between"
                        v-if="cart.selected_shipping_rate"
                    >
                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @lang('admin::app.sales.orders.create.cart.summary.shipping-amount')
                        </p>

                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @{{ cart.selected_shipping_rate }}
                        </p>
                    </div>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.delivery_charges.after') !!}

                    <!-- Discount -->
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.discount_amount.before') !!}

                    <div
                        class="row grid-cols-2 grid-rows-1 grid gap-4 text-right justify-between"
                        v-if="parseFloat(cart.base_discount_amount)"
                    >
                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @lang('admin::app.sales.orders.create.cart.summary.discount-amount')
                        </p>

                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @{{ cart.formatted_base_discount_amount }}
                        </p>
                    </div>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.discount_amount.after') !!}


                    <!-- Discount -->
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.coupon.before') !!}

                    <div class="row grid-cols-2 grid-rows-1 grid gap-4 text-right justify-items-end">
                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @lang('admin::app.sales.orders.create.cart.summary.apply-coupon')
                        </p>

                        <template v-if="cart.coupon_code">
                            <p class="flex gap-1 items-center text-base text-green-600 font-medium">
                                @{{ cart.coupon_code }}

                                <span
                                    class="icon-cancel text-2xl cursor-pointer"
                                    @click="destroyCoupon"
                                >
                                </span>
                            </p>
                        </template>

                        <template v-else>
                            <p class="text-base text-gray-600 font-medium">
                                <span
                                    class="text-blue-600 cursor-pointer"
                                    @click="$refs.couponModel.open()"
                                >
                                    @lang('admin::app.sales.orders.create.cart.summary.apply-coupon')
                                </span>
                            </p>
                        </template>
                    </div>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.coupon.after') !!}

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.coupon.after') !!}

                    <!-- Cart Grand Total -->
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.grand_total.before') !!}

                    <div class="row grid-cols-2 grid-rows-1 grid gap-4 text-right justify-between">
                        <p class="text-lg font-semibold dark:text-white">
                            @lang('admin::app.sales.orders.create.cart.summary.grand-total')
                        </p>

                        <p class="text-lg font-semibold dark:text-white">
                            @{{ cart.base_grand_total }}
                        </p>
                    </div>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.grand_total.after') !!}
                </div>
            </div>

            <div class="flex w-full justify-end p-4">
                <x-shop::button
                    type="button"
                    class="primary-button w-max py-3 px-11"
                    :title="trans('shop::app.checkout.onepage.summary.place-order')"
                    ::disabled="isPlacingOrder"
                    ::loading="isPlacingOrder"
                    @click="placeOrder"
                />
            </div>

            <!-- Apply Coupon Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <!-- Apply coupon form -->
                <form @submit="handleSubmit($event, applyCoupon)">
                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.coupon_form_controls.before') !!}

                    <!-- Apply coupon modal -->
                    <x-admin::modal ref="couponModel">
                        <!-- Modal Header -->
                        <x-slot:header class="dark:text-white">
                            @lang('admin::app.sales.orders.create.cart.summary.apply-coupon')
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="code"
                                    rules="required"
                                    :placeholder="trans('admin::app.sales.orders.create.cart.summary.enter-your-code')"
                                />

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <x-admin::button
                                class="primary-button"
                                :title="trans('admin::app.sales.orders.create.cart.summary.apply-coupon')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />
                        </x-slot>
                    </x-admin::modal>

                    {!! view_render_event('bagisto.admin.sales.order.create.left_component.summary.coupon_form_controls.after') !!}
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-cart-summary', {
            template: '#v-cart-summary-template',

            props: ['cart'],

            data() {
                return {
                    isStoring: false,

                    isPlacingOrder: false,
                }
            },

            methods: {
                applyCoupon(params, { resetForm }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('admin.sales.cart.store_coupon', $cart->id) }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            this.$emit('coupon-applied', response.data.data);
                  
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.couponModel.toggle();

                            resetForm();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            this.$refs.couponModel.toggle();

                            if ([400, 422].includes(error.response.request.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                resetForm();

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('admin.sales.cart.remove_coupon', $cart->id) }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {
                            this.$emit('coupon-removed', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },

                placeOrder() {
                    this.isPlacingOrder = true;

                    this.$axios.post('{{ route('admin.sales.orders.store', $cart->id) }}')
                        .then(response => {
                            if (response.data.data.redirect) {
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                            }

                            this.isPlacingOrder = false;
                        })
                        .catch(error => {
                            this.isPlacingOrder = false

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                }
            }
        });
    </script>
@endPushOnce
