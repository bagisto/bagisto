<v-cart-summary
    ref="vCartSummary"
    :cart="cart"
>
</v-cart-summary>

@pushOnce('scripts')
    <script type="text/x-template" id="v-cart-summary-template">
        <div class="w-[442px] max-w-full pl-[30px] h-max sticky top-[30px] mt-[30px]">

            <h2 class="text-[26px] font-medium">@lang('Cart Summary')</h2>
            
            <div class="grid border-b-[1px] border-[#E9E9E9] mt-[40px]">
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
                        <p class="text-[16px] text-navyBlue">@{{ item.name }}</p>
                        <p class="text-[18px] font-medium mt-[10px]">@{{ item.formatted_total }}</p>
                        <p class="text-[15px]">@{{ item.formatted_price }} X @{{ item.quantity }} (@lang('Quantity'))</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-[15px] mt-[25px] mb-[30px]">
                <div class="flex text-right justify-between">
                    <p class="text-[16px]">@lang('Subtotal')</p>
                    <p class="text-[16px] font-medium">@{{ cart.base_sub_total  }}</p>
                </div>

                <div 
                    class="flex text-right justify-between"
                    v-for="(amount, index) in cart.base_tax_amounts"
                    v-if="parseFloat(cart.base_tax_total)"
                >
                    <p class="text-[16px]">Tax (@{{ index }})%</p>
                    <p class="text-[16px] font-medium">@{{ amount }}</p>
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
                    <p class="text-[16px] mr-2">@lang('Grand total')</p>
                    <p class="text-[16px] font-medium"> @{{ cart.base_grand_total }}</p>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-cart-summary', {
            template: '#v-cart-summary-template',
            
            props: ['cart'],
        })
    </script>
@endPushOnce