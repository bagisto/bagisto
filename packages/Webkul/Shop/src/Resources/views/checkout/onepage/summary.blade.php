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
            <div class="sticky top-8 h-max w-[442px] max-w-full pl-8 max-lg:w-auto max-lg:max-w-[442px] max-lg:pl-0">
                <h1 class="text-[26px] font-medium max-sm:text-[20px]">
                    @lang('shop::app.checkout.onepage.summary.cart-summary')
                </h1>
                
                <div class="grid mt-10 border-b border-[#E9E9E9] max-sm:mt-5">
                    <div 
                        class="flex gap-x-1.5 pb-5"
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

                            <p class="mt-2.5 text-[18px] font-medium max-sm:text-[14px] max-sm:font-normal">
                                @lang('shop::app.checkout.onepage.summary.price_&_qty', ['price' => '@{{ item.formatted_price }}', 'qty' => '@{{ item.quantity }}'])
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 mt-6 mb-8">
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

                    @include('shop::checkout.cart.coupon')

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
                        <x-shop::button
                            class="primary-button w-max py-3 px-11 bg-navyBlue rounded-[18px] max-sm:text-[14px] max-sm:px-6 max-sm:mb-10"
                            :title="trans('shop::app.checkout.onepage.summary.place-order')"
                            :loading="false"
                            ref="placeOrder"
                            @click="placeOrder"
                        >
                        </x-shop::button>
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
                    this.$refs.placeOrder.isLoading = true;

                    this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}')
                        .then(response => {
                            if (response.data.data.redirect) {
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                            }

                            this.$refs.placeOrder.isLoading = false;

                        })
                        .catch(error => {
                            this.$refs.placeOrder.isLoading = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
