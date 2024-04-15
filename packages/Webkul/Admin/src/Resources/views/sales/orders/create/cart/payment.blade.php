{!! view_render_event('bagisto.admin.sales.order.create.cart.payment.before') !!}

<v-cart-payment-methods
    :methods="paymentMethods"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Payment Method shimmer Effect -->
    <x-admin::shimmer.sales.orders.create.cart.payment />
</v-cart-payment-methods>

{!! view_render_event('bagisto.admin.sales.order.create.cart.payment.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-payment-methods-template"
    >
        <div
            class="bg-white dark:bg-gray-900 rounded box-shadow"
            id="payment-step-container"
        >
            <div class="flex items-center p-4 border-b dark:border-gray-800">
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    @lang('admin::app.sales.orders.create.cart.payment.title')
                </p>
            </div>

            <!-- Payment Cards -->
            <template v-if="! methods">
                <!-- Payment Method Shimmer Effect -->
                <x-admin::shimmer.sales.orders.create.cart.payment />
            </template>

            <template v-else>
                <div class="grid">
                    {!! view_render_event('bagisto.admin.sales.order.create.cart.payment.before') !!}

                    <label
                        class="flex gap-2 items-center p-4 border-b dark:border-gray-800 cursor-pointer transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        v-for="payment in methods"
                        :for="payment.method"
                    >
                        <x-admin::form.control-group.control
                            type="radio"
                            name="payment_method"
                            ::id="payment.method"
                            ::for="payment.method"
                            ::value="payment.method"
                            @change="store(payment)"
                        />

                        <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                            @{{ payment.method_title }}
                        </p>
                    </label>

                    {!! view_render_event('bagisto.admin.sales.order.create.cart.payment.after') !!}
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-cart-payment-methods', {
            template: '#v-cart-payment-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
            },

            emits: ['processing', 'processed'],

            methods: {
                store(selectedMethod) {
                    this.$emit('processing', 'review');

                    this.$axios.post("{{ route('admin.sales.cart.payment_methods.store', $cart->id) }}", {
                            payment: selectedMethod
                        })
                        .then(response => {
                            this.$emit('processed', response.data.cart);
                        })
                        .catch(error => {
                            this.$emit('processing', 'payment');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
