<v-payment-method ref="vPaymentMethod"></v-payment-method>

@pushOnce('scripts')
    <script type="text/x-template" id="v-payment-method-template">
        <template v-if="! isShowPaymentMethod && isPaymentLoading">
            <x-shop::shimmer.checkout.onepage.payment-method></x-shop::shimmer.checkout.onepage.payment-method>
        </template>

        <div v-if="isShowPaymentMethod">
            <x-shop::accordion>
                <x-slot:header>
                    <div class="flex justify-between mt-2 items-center">
                        <h2 class="text-[26px] font-medium">@lang('Payment methods')</h2>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="flex flex-wrap gap-[29px] mb-5">
                        <div 
                            class="relative cursor-pointer"
                            v-for="(payment, index) in paymentMethods"
                        >
                            <input 
                                type="radio" 
                                name="payment[method]" 
                                :value="payment.payment"
                                :id="payment.method"
                                class="hidden peer"    
                                @change="paymentMethodSelected(payment)"
                            >

                            <label :for="payment.method" class="icon-radio-unselect text-[24px] text-navyBlue absolute right-[20px] top-[20px] peer-checked:icon-radio-select cursor-pointer"></label>

                            <label :for="payment.method" class="block border border-[#E9E9E9] p-[20px] rounded-[12px] w-[190px] cursor-pointer">
                                <img class="mx-w-[55px] max-h-[45px]" src="{{ bagisto_asset('images/paypal.png') }}" :alt="payment.method_title" :title="payment.method_title">
                                <p class="text-[14px] font-semibold mt-[5px]">@{{ payment.method_title }} </p>
                                <p class="text-[12px] font-medium mt-[10px]">@{{ payment.description }}</p>
                            </label>
                            {{-- Todo implement the additionalDetails --}}
                            {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method'] --}}
                        </div>
                    </div>
                </x-slot:content>
            </x-shop::accordion>
        </div>
    </script>

    <script type="module">
        app.component('v-payment-method', {
            template: '#v-payment-method-template',

            data() {
                return {
                    paymentMethods: [],

                    isShowPaymentMethod: false,

                    isPaymentLoading: false,
                }
            },

            methods: {
                paymentMethodSelected(selectedPaymentMethod) {
                    this.$parent.$refs.vReview.isReviewSummaryLoading = true;

                    this.$axios.post("{{ route('shop.checkout.save_payment') }}", {
                            'payment': selectedPaymentMethod
                        })
                        .then(response => {
                            this.$parent.$refs.vReview.reviewCart = response.data.cart;
                            
                            this.$parent.$refs.vReview.isShowReviewSummary = true;

                            this.$parent.$refs.vReview.isReviewSummaryLoading = false;
                        })
                        .catch(error => console.log(error));
                }
            }
        })
    </script>
@endPushOnce