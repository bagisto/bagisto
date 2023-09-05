<div class="w-[418px] max-w-full">
    <p class="text-[26px] font-medium">
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </p>

    <div class="grid gap-[15px] mt-[25px]">
        <div class="flex justify-between text-right">
            <p class="text-[16px]">
                @lang('shop::app.checkout.cart.summary.sub-total')
            </p>

            <p 
                class="text-[16px] font-medium"
                v-text="cart.formatted_sub_total"
            >
            </p>
        </div>

        <div 
            class="flex justify-between text-right"
            v-for="(amount, index) in cart.base_tax_amounts"
            v-if="parseFloat(cart.base_tax_total)"
        >
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">
                @lang('shop::app.checkout.cart.summary.tax') (@{{ index }})%
            </p>

            <p 
                class="text-[16px] font-medium max-sm:text-[14px] max-sm:font-medium"
                v-text="amount"
            >
            </p>
        </div>

        <div 
            class="flex justify-between text-right"
            v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
        >
            <p class="text-[16px]">
                @lang('shop::app.checkout.cart.summary.discount-amount')
            </p>

            <p 
                class="text-[16px] font-medium"
                v-text="cart.formatted_base_discount_amount"
            >
            </p>
        </div>
        
        @include('shop::checkout.cart.coupon')
   
        <div class="flex justify-between text-right">
            <p class="text-[18px] font-semibold">
                @lang('shop::app.checkout.cart.summary.grand-total')
            </p>

            <p 
                class="text-[18px] font-semibold" 
                v-text="cart.formatted_grand_total"
            >
            </p>
        </div>

        <a 
            href="{{ route('shop.checkout.onepage.index') }}" 
            class="block w-max place-self-end py-[11px] mt-[15px] px-[43px] bg-navyBlue rounded-[18px] text-white text-base font-medium text-center cursor-pointer"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>
    </div>
</div>