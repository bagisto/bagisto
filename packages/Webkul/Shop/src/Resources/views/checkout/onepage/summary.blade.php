{!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}

<v-cart-summary
    ref="vCartSummary"
    :cart="cart"
    :is-cart-loading="isCartLoading"
>
</v-cart-summary>

{!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-cart-summary-template">
        <template v-if="isCartLoading">
            <!-- onepage Summary Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage.cart-summary/>
        </template>

        <template v-else>
            <div class="sticky top-[30px] h-max w-[442px] max-w-full pl-[30px] max-lg:w-auto max-lg:max-w-[442px] max-lg:pl-0 ">
                <h2 class="text-[26px] font-medium max-sm:text-[20px]">
                    @lang('shop::app.checkout.onepage.summary.cart-summary')
                </h2>
                
                <div class="grid mt-[40px] border-b-[1px] border-[#E9E9E9] max-sm:mt-[20px]">
                    <div 
                        class="flex gap-x-[15px] pb-[20px]"
                        v-for="item in cart.items"
                    >
                        <img
                            class="max-w-[90px] max-h-[90px] w-[90px] h-[90px] rounded-md"
                            :src="item.base_image.small_image_url"
                            :alt="item.name"
                            width="110"
                            height="110"
                        />

                        <div>
                            <p 
                                class="text-[16px] text-navyBlue max-sm:text-[14px] max-sm:font-medium" 
                                v-text="item.name"
                            >
                            </p>

                            <p class="mt-[10px] text-[18px] font-medium max-sm:text-[14px] max-sm:font-normal">
                                @{{ item.formatted_price }} X @{{ item.quantity }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-[15px] mt-[25px] mb-[30px]">
                    <div class="flex text-right justify-between">
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">
                            @lang('shop::app.checkout.onepage.summary.sub-total')
                        </p>

                        <p 
                            class="text-[16px] font-medium max-sm:text-[14px]"
                            v-text="cart.base_sub_total"
                        >
                        </p>
                    </div>

                    <div 
                        class="flex text-right justify-between"
                        v-for="(amount, index) in cart.base_tax_amounts"
                        v-if="parseFloat(cart.base_tax_total)"
                    >
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">
                            @lang('shop::app.checkout.onepage.summary.tax') (@{{ index }})%
                        </p>

                        <p 
                            class="text-[16px] font-medium max-sm:text-[14px]"
                            v-text="amount"
                        >
                        </p>
                    </div>

                    <div 
                        class="flex text-right justify-between"
                        v-if="cart.selected_shipping_rate"
                    >
                        <p class="text-[16px]">
                            @lang('shop::app.checkout.onepage.summary.delivery-charges')
                        </p>

                        <p 
                            class="text-[16px] font-medium"
                            v-text="cart.selected_shipping_rate"
                        >
                        </p>
                    </div>

                    <div 
                        class="flex text-right justify-between"
                        v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
                    >
                        <p class="text-[16px]">
                            @lang('shop::app.checkout.onepage.summary.discount-amount')
                        </p>

                        <p 
                            class="text-[16px] font-medium"
                            v-text="cart.formatted_base_discount_amount"
                        >
                        </p>
                    </div>

                    @include('shop::checkout.onepage.coupon')

                    <div class="flex text-right justify-between">
                        <p class="text-[18px] font-semibold">
                            @lang('shop::app.checkout.onepage.summary.grand-total')
                        </p>

                        <p 
                            class="text-[18px] font-semibold"
                            v-text="cart.base_grand_total"
                        >
                        </p>
                    </div>
                </div>

                <template v-if="canPlaceOrder">
                    <div v-if="selectedPaymentMethod?.method == 'paypal_smart_button'">
                        <v-paypal-smart-button></v-paypal-smart-button>
                    </div>

                    <div
                        class="flex justify-end"
                        v-else
                    >
                        <button
                            v-if="! isLoading"
                            class="block w-max py-[11px] px-[43px] bg-navyBlue text-white text-base font-medium rounded-[18px] text-center cursor-pointer max-sm:text-[14px] max-sm:px-[25px] max-sm:mb-[40px]"
                            @click="placeOrder"
                        >
                            @lang('shop::app.checkout.onepage.summary.place-order')    
                        </button>

                        <button
                            v-else
                            class="flex gap-[10px] items-center w-max py-[11px] px-[32px] bg-navyBlue text-white text-base font-medium rounded-[18px] text-center max-sm:text-[14px] max-sm:px-[25px] max-sm:mb-[40px]"
                        >
                            <!-- Spinner -->
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                >
                                </circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                >
                                </path>
                            </svg>

                            @lang('shop::app.checkout.onepage.summary.processing')
                        </button>
                    </div>
                </template>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-cart-summary', {
            template: '#v-cart-summary-template',
            
            props: ['cart', 'isCartLoading'],

            data() {
                return {
                    canPlaceOrder: false,

                    selectedPaymentMethod: null,

                    isLoading: false,
                }
            },

            methods: {
                placeOrder() {
                    this.isLoading = true;

                    this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}')
                        .then(response => {
                            if (response.data.data.redirect) {
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                            }
                        })
                        .catch(error => console.log(error));
                },
            },
        });
    </script>
@endPushOnce
