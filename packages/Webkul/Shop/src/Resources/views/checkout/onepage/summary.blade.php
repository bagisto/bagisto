{!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}

<v-cart-summary
    :cart="cart"
    :is-cart-loading="isCartLoading"
>
</v-cart-summary>

{!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-summary-template"
    >
        <template v-if="isCartLoading">
            <!-- onepage Summary Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage.cart-summary />
        </template>

        <template v-else>
            <div class="sticky top-8 h-max w-[442px] max-w-full ltr:pl-8 rtl:pr-8 max-lg:w-auto max-lg:max-w-[442px] max-lg:ltr:pl-0 max-lg:rtl:pr-0">
                <h1 class="text-2xl font-medium max-sm:text-xl">
                    @lang('shop::app.checkout.onepage.summary.cart-summary')
                </h1>
                
                <div class="grid mt-10 border-b border-[#E9E9E9] max-sm:mt-5">
                    <div 
                        class="flex gap-x-4 pb-5"
                        v-for="item in cart.items"
                    >
                    
                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.before') !!}

                        <img
                            class="max-w-[90px] max-h-[90px] w-[90px] h-[90px] rounded-md"
                            :src="item.base_image.small_image_url"
                            :alt="item.name"
                            width="110"
                            height="110"
                        />

                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.after') !!}

                        <div>
                            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.before') !!}

                            <p 
                                class="text-base text-navyBlue max-sm:text-sm max-sm:font-medium" 
                                v-text="item.name"
                            >
                            </p>

                            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.after') !!}

                            <p class="mt-2.5 text-lg font-medium max-sm:text-sm max-sm:font-normal">
                                @lang('shop::app.checkout.onepage.summary.price_&_qty', ['price' => '@{{ item.formatted_price }}', 'qty' => '@{{ item.quantity }}'])
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 mt-6 mb-8">

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.before') !!}

                    <div class="flex text-right justify-between">
                        <p class="text-base max-sm:text-sm max-sm:font-normal">
                            @lang('shop::app.checkout.onepage.summary.sub-total')
                        </p>

                        <p 
                            class="text-base font-medium max-sm:text-sm"
                            v-text="cart.base_sub_total"
                        >
                        </p>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.after') !!}


                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.before') !!}

                    <div 
                        class="flex text-right justify-between"
                        v-for="(amount, index) in cart.base_tax_amounts"
                        v-if="parseFloat(cart.base_tax_total)"
                    >
                        <p class="text-base max-sm:text-sm max-sm:font-normal">
                            @lang('shop::app.checkout.onepage.summary.tax') (@{{ index }})%
                        </p>

                        <p 
                            class="text-base font-medium max-sm:text-sm"
                            v-text="amount"
                        >
                        </p>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.after') !!}

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

                    <div 
                        class="flex text-right justify-between"
                        v-if="cart.selected_shipping_rate"
                    >
                        <p class="text-base">
                            @lang('shop::app.checkout.onepage.summary.delivery-charges')
                        </p>

                        <p 
                            class="text-base font-medium"
                            v-text="cart.selected_shipping_rate"
                        >
                        </p>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.before') !!}

                    <div 
                        class="flex text-right justify-between"
                        v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
                    >
                        <p class="text-base">
                            @lang('shop::app.checkout.onepage.summary.discount-amount')
                        </p>

                        <p 
                            class="text-base font-medium"
                            v-text="cart.formatted_base_discount_amount"
                        >
                        </p>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.after') !!}

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.before') !!}

                    @include('shop::checkout.cart.coupon')

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.after') !!}

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.before') !!}

                    <div class="flex text-right justify-between">
                        <p class="text-lg font-semibold">
                            @lang('shop::app.checkout.onepage.summary.grand-total')
                        </p>

                        <p 
                            class="text-lg font-semibold"
                            v-text="cart.base_grand_total"
                        >
                        </p>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.after') !!}
                </div>

                <template v-if="canPlaceOrder">
                    <div v-if="selectedPaymentMethod?.method == 'paypal_smart_button'">
                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.before') !!}

                        <v-paypal-smart-button></v-paypal-smart-button>

                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.after') !!}
                    </div>

                    <div
                        class="flex justify-end"
                        v-else
                    >
                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.place_order_button.before') !!}

                        <x-shop::button
                            v-if="!isLoading"
                            type="button"
                            class="primary-button w-max py-3 px-11 bg-navyBlue rounded-2xl max-sm:text-sm max-sm:px-6 max-sm:mb-10"
                            :title="trans('shop::app.checkout.onepage.summary.place-order')"
                            :loading="false"
                            @click="placeOrder"
                        />

                        <x-shop::button
                            type="button"
                            class="primary-button w-max py-3 px-11 bg-navyBlue rounded-2xl max-sm:text-sm max-sm:px-6 max-sm:mb-10"
                            v-else
                            :title="trans('shop::app.checkout.onepage.summary.place-order')"
                            :loading="true"
                            :disabled="true"
                        />

                        {!! view_render_event('bagisto.shop.checkout.onepage.summary.place_order_button.after') !!}
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

            mounted() {
                this.$emitter.on('can-place-order', (value) => this.canPlaceOrder = true);

                this.$emitter.on('after-payment-method-selected', (value) => this.selectedPaymentMethod = value);
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

                            if (localStorage.getItem('customerAddresses')) {
                                localStorage.removeItem('customerAddresses');
                            }

                            this.isLoading = false;
                        })
                        .catch(error => this.isProcessing = false);
                },
            },
        });
    </script>
@endPushOnce
