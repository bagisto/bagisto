{!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Shipping Method Shimmer Effect -->
    <x-shop::shimmer.checkout.onepage.shipping-method />
</v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-methods-template"
    >
        <div class="mb-7">
            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <!-- Accordion Blade Component -->
                <x-shop::accordion class="!border-b-0">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="!px-0 !py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-medium max-sm:text-xl">
                                @lang('shop::app.checkout.onepage.shipping.shipping-method')
                            </h2>
                        </div>
                    </x-slot>

                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="mt-8 !p-0">
                        <div class="flex flex-wrap gap-8">
                            <template v-for="method in methods">
                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

                                <div
                                    class="relative max-w-[218px] select-none max-sm:max-w-full max-sm:flex-auto"
                                    v-for="rate in method.rates"
                                >
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="peer hidden"
                                        @change="store(rate.method)"
                                    >

                                    <label 
                                        class="icon-radio-unselect peer-checked:icon-radio-select absolute top-5 cursor-pointer text-2xl text-navyBlue ltr:right-5 rtl:left-5"
                                        :for="rate.method"
                                    >
                                    </label>

                                    <label 
                                        class="block cursor-pointer rounded-xl border border-[#E9E9E9] p-5"
                                        :for="rate.method"
                                    >
                                        <span class="icon-flate-rate text-6xl text-navyBlue"></span>

                                        <p class="mt-1.5 text-2xl font-semibold max-sm:text-xl">
                                            @{{ rate.base_formatted_price }}
                                        </p>
                                        
                                        <p class="mt-2.5 text-xs font-medium">
                                            <span class="font-medium">@{{ rate.method_title }}</span> - @{{ rate.method_description }}
                                        </p>
                                    </label>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}
                            </template>
                        </div>
                    </x-slot>
                </x-shop::accordion>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',

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
                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {    
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this.$emit('processed', response.data.payment_methods);
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'shipping');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
