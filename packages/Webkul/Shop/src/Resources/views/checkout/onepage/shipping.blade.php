<v-shipping-method ref="vShippingMethod"></v-shipping-method>

@pushOnce('scripts')
    <script type="text/x-template" id="v-shipping-method-template">
        <div v-if="isShowShippingMethod">
            <x-shop::accordion>
                <x-slot:header>
                    <div class="flex justify-between mt-2 items-center">
                        <h2 class="text-[26px] font-medium max-sm:text-[20px]">@lang('Shipping methods')</h2>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="flex">
                        <div
                            class="relative max-w-[218px] select-none max-sm:max-w-full max-sm:flex-auto m-2"
                            v-for="shippingMethod in shippingMethods"
                        >
                            <div v-for="rate in shippingMethod.rates">
                                <input 
                                    type="radio"
                                    name="shipping_method"
                                    :id="rate.method"
                                    :value="rate.method"
                                    class="hidden peer"
                                    @change="save(rate.method)"
                                >

                                <label 
                                    class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"
                                    :for="rate.method"
                                >
                                </label>

                                <label 
                                    class="block border border-[#E9E9E9] p-[20px] rounded-[12px] h-[190px] cursor-pointer"
                                    :for="rate.method"
                                >
                                    <span class="icon-flate-rate text-[60px] text-navyBlue"></span>

                                    <p class="text-[25px] font-semibold mt-[5px]">@{{ rate.base_price }}</p>
                                    
                                    <p class="text-[12px] font-medium mt-[10px]">
                                        <span class="font-medium">@{{ rate.method_title }}</span> - @{{ rate.method_description }}
                                    </p>
                                </label>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-shop::accordion>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-method', {
            template: '#v-shipping-method-template',

            data() {
                return {
                    shippingMethods: [],

                    isShowShippingMethod: false
                }
            },

            methods: {
                save(selectedShippingMethod) {
                    this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = false;

                    this.$axios.post("{{ route('shop.checkout.save_shipping') }}", {    
                            shipping_method: selectedShippingMethod,
                        })
                        .then(response => {
                            /*
                            * Calling v-checkout's getOrderSummary method component(parent component) getOrdersSummary for update cart (summary data/call getOrderSummary method).
                            */
                            this.$parent.getOrderSummary();

                            this.$parent.$refs.vPaymentMethod.paymentMethods = response.data.paymentMethods;
                                
                            this.$parent.$refs.vPaymentMethod.isShowPaymentMethod = true;

                            this.$parent.$refs.vReview.isShowReviewSummary = false;
                        })
                        .catch(error => {})
                },
            }
        })

    </script>
@endPushOnce