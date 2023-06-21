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
            <x-shop::shimmer.checkout.onepage.cart-summary></x-shop::shimmer.checkout.onepage.cart-summary>
        </template>

        <template v-else>
            <div class="max-w-full w-[442px] pl-[30px] h-max sticky top-[30px] max-lg:w-auto max-lg:max-w-[442px] max-lg:pl-0 mt-[30px]">

                <h2 class="text-[26px] font-medium max-sm:text-[20px]">@lang('Cart Summary')</h2>
                
                <div class="grid border-b-[1px] border-[#E9E9E9] mt-[40px] max-sm:mt-[20px]">
                    <div 
                        class="flex gap-x-[15px] pb-[20px]"
                        v-for="item in cart.items"
                    >
                        <img
                            class="max-w-[90px] max-h-[90px] w-[90px] h-[90px] rounded-md"
                            :src="item.image.medium_image_url"
                            :title="item.name"
                            :alt="item.name"
                        />
                        <div class="">
                            <p class="text-[16px] max-sm:text-[14px] max-sm:font-medium text-navyBlue">@{{ item.name }}</p>
                            <p class="text-[18px] font-medium mt-[10px] max-sm:text-[14px] max-sm:font-normal">@{{ item.formatted_total }} X @{{ item.quantity }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-[15px] mt-[25px] mb-[30px]">
                    <div class="flex text-right justify-between">
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">@lang('Subtotal')</p>
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-medium font-medium">@{{ cart.base_sub_total  }}</p>
                    </div>

                    <div 
                        class="flex text-right justify-between"
                        v-for="(amount, index) in cart.base_tax_amounts"
                        v-if="parseFloat(cart.base_tax_total)"
                    >
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">Tax (@{{ index }})%</p>
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-medium font-medium">@{{ amount }}</p>
                    </div>

                    <div 
                        class="flex text-right justify-between"
                        v-if="cart.selected_shipping_rate"
                    >
                        <p class="text-[16px]">@lang('Delivery Charges')</p>
                        <p class="text-[16px] font-medium">@{{ cart.selected_shipping_rate }}</p>
                    </div>

                    <div 
                        class="flex text-right justify-between"
                        v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
                    >
                        <p class="text-[16px]">@lang('Discount amount')</p>
                        <p class="text-[16px] font-medium">@{{ cart.formatted_base_discount_amount }}</p>
                    </div>

                    @include('shop::checkout.onepage.coupon')

                    <div class="flex text-right justify-between">
                        <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal mr-2">@lang('Grand total')</p>
                        <p class="text-[26px] max-sm:text-[14px] font-medium"> @{{ cart.base_grand_total }}</p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-cart-summary', {
            template: '#v-cart-summary-template',
            
            props: ['cart', 'isCartLoading'],
        })
    </script>
@endPushOnce