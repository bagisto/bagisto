{!! view_render_event('bagisto.shop.checkout.shipping.method.before') !!}

<v-shipping-method>
    <!-- Shipping Method Shimmer Effect -->
    <x-shop::shimmer.checkout.onepage.shipping-method />
</v-shipping-method>

{!! view_render_event('bagisto.shop.checkout.shipping.method.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-method-template"
    >
        <div class="mb-7">
            <template v-if="! isShowShippingMethods && isShippingMethodLoading">
                <!-- Shipping Method Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-if="isShowShippingMethods">
                {!! view_render_event('bagisto.shop.checkout.onepage.shipping-method.accordion.before') !!}

                <x-shop::accordion class="!border-b-0">
                    <x-slot:header class="!py-4 !px-0">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-medium max-sm:text-xl">
                                @lang('shop::app.checkout.onepage.shipping.shipping-method')
                            </h2>
                        </div>
                    </x-slot>

                    <x-slot:content class="!p-0 mt-8">
                        <div class="flex flex-wrap gap-8">
                            <div
                                class="relative max-w-[218px] max-sm:max-w-full max-sm:flex-auto select-none"
                                v-for="shippingMethod in shippingMethods"
                            >
                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping-method.before') !!}

                                <div v-for="rate in shippingMethod.rates">
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="hidden peer"
                                        @change="store(rate.method)"
                                    >

                                    <label 
                                        class="icon-radio-unselect absolute ltr:right-5 rtl:left-5 top-5 text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                        :for="rate.method"
                                    >
                                    </label>

                                    <label 
                                        class="block p-5 border border-[#E9E9E9] rounded-xl cursor-pointer"
                                        :for="rate.method"
                                    >
                                        <span class="icon-flate-rate text-6xl text-navyBlue"></span>

                                        <p class="text-2xl mt-1.5 font-semibold max-sm:text-xl">
                                            @{{ rate.base_formatted_price }}
                                        </p>
                                        
                                        <p class="text-xs mt-2.5 font-medium">
                                            <span class="font-medium">@{{ rate.method_title }}</span> - @{{ rate.method_description }}
                                        </p>
                                    </label>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping-method.after') !!}
                            </div>
                        </div>
                    </x-slot>
                </x-shop::accordion>

                {!! view_render_event('bagisto.shop.checkout.onepage.shipping-method.accordion.after') !!}
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-method', {
            template: '#v-shipping-method-template',

            data() {
                return {
                    shippingMethods: [],

                    isShowShippingMethods: false,

                    isShippingMethodLoading: false,
                }
            },

            mounted() {
                this.$emitter.on('is-shipping-loading', (value) => this.isShippingMethodLoading = value);

                this.$emitter.on('is-show-shipping-methods', (value) => this.isShowShippingMethods = value);
                
                this.$emitter.on('shipping-methods', (methods) => this.shippingMethods = methods);
            },

            methods: {
                store(selectedShippingMethod) {
                    this.$emitter.emit('is-show-payment-methods', false);

                    this.$emitter.emit('is-payment-loading', true);

                    this.$emitter.emit('can-place-order', false);

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {    
                            shipping_method: selectedShippingMethod,
                        })
                        .then(response => {
                            if (response.data.payment_methods) {
                                this.$emitter.emit('update-cart-summary');

                                this.$emitter.emit('payment-methods', response.data.payment_methods);

                                this.$emitter.emit('is-show-payment-methods', true);

                                this.$emitter.emit('is-payment-loading', false);
                            }
                        })
                        .catch(error => {});
                },
            },
        });
    </script>
@endPushOnce
