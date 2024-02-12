{!! view_render_event('bagisto.shop.checkout.payment.method.before') !!}

<v-payment-method>
    <x-shop::shimmer.checkout.onepage.payment-method />
</v-payment-method>

{!! view_render_event('bagisto.shop.checkout.payment.method.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-payment-method-template"
    >
        <div class="mb-7">
            <template v-if="! isShowPaymentMethods && isPaymentMethodLoading">
                <!-- Payment Method shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.payment-method />
            </template>
    
            <template v-if="isShowPaymentMethods">
                <div>
                    {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.before') !!}

                    <x-shop::accordion class="!border-b-0">
                        <x-slot:header class="!p-0">
                            <div class="flex justify-between items-center">
                                <h2 class="text-2xl font-medium max-sm:text-xl">
                                    @lang('shop::app.checkout.onepage.payment.payment-method')
                                </h2>
                            </div>
                        </x-slot>
        
                        <x-slot:content class="!p-0 mt-8">
                            <div class="flex flex-wrap gap-7">
                                <div 
                                    class="relative max-sm:max-w-full max-sm:flex-auto cursor-pointer"
                                    v-for="(payment, index) in paymentMethods"
                                >
                                    {!! view_render_event('bagisto.shop.checkout.payment-method.before') !!}

                                    <input 
                                        type="radio" 
                                        name="payment[method]" 
                                        :value="payment.payment"
                                        :id="payment.method"
                                        class="hidden peer"    
                                        @change="store(payment)"
                                    >
        
                                    <label 
                                        :for="payment.method" 
                                        class="absolute ltr:right-5 rtl:left-5 top-5 icon-radio-unselect text-2xl text-navyBlue peer-checked:icon-radio-select cursor-pointer"
                                    >
                                    </label>

                                    <label 
                                        :for="payment.method" 
                                        class="w-[190px] p-5 block border border-[#E9E9E9] rounded-xl max-sm:w-full cursor-pointer"
                                    >

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.image.before') !!}

                                        <img
                                            class="max-w-[55px] max-h-[45px]"
                                            :src="payment.image"
                                            width="55"
                                            height="55"
                                            :alt="payment.method_title"
                                            :title="payment.method_title"
                                        />

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.image.after') !!}

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.before') !!}

                                        <p class="text-sm font-semibold mt-1.5">
                                            @{{ payment.method_title }}
                                        </p>
                                        
                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.after') !!}

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.before') !!}

                                        <p class="text-xs font-medium mt-2.5">
                                            @{{ payment.description }}
                                        </p> 

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.after') !!}
                                    </label>

                                    {!! view_render_event('bagisto.shop.checkout.payment-method.after') !!}

                                    <!-- Todo implement the additionalDetails -->
                                    {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method'] --}}
                                </div>
                            </div>
                        </x-slot>
                    </x-shop::accordion>

                    {!! view_render_event('bagisto.shop.checkout.onepage.index.payment_method.accordion.before') !!}
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-payment-method', {
            template: '#v-payment-method-template',

            data() {
                return {
                    paymentMethods: [],

                    isShowPaymentMethods: false,

                    isPaymentMethodLoading: false,
                }
            },

            mounted() {
                this.$emitter.on('is-payment-loading', (value) => this.isPaymentMethodLoading = value);

                this.$emitter.on('is-show-payment-methods', (value) => this.isShowPaymentMethods = value);
                
                this.$emitter.on('payment-methods', (methods) => this.paymentMethods = methods);
            },

            methods: {
                store(selectedPaymentMethod) {
                    this.$axios.post("{{ route('shop.checkout.onepage.payment_methods.store') }}", {
                            payment: selectedPaymentMethod
                        })
                        .then(response => {
                            this.$emitter.emit('after-payment-method-selected', selectedPaymentMethod);

                            if (response.data.cart) {
                                this.$emitter.emit('can-place-order', true);
                            }
                        })
                        .catch(error => console.log(error));
                },
            },
        });
    </script>
@endPushOnce
