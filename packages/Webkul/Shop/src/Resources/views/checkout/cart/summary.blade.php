<div class="w-[418px] max-w-full">
    <p class="text-[26px] font-medium">
        @lang('shop::app.checkout.cart.cart-summary')
    </p>

    <div class="grid gap-[15px] mt-[25px]">
        <div class="flex text-right justify-between">
            <p class="text-[16px]">
                @lang('shop::app.checkout.cart.subtotal')
            </p>

            <p 
                class="text-[16px] font-medium"
                v-text="cart.formatted_sub_total"
            >
            </p>
        </div>

        <div 
            class="flex text-right justify-between"
            v-for="(amount, index) in cart.base_tax_amounts"
            v-if="parseFloat(cart.base_tax_total)"
        >
            <p class="text-[16px] max-sm:text-[14px] max-sm:font-normal">
                @lang('Tax') (@{{ index }})%
            </p>

            <p 
                class="text-[16px] max-sm:text-[14px] max-sm:font-medium font-medium"
                v-text="amount"
            >
            </p>
        </div>

        <div 
            class="flex text-right justify-between"
            v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
        >
            <p class="text-[16px]">
                @lang('Discount amount')
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
                @lang('shop::app.checkout.cart.grand-total')
            </p>

            <p 
                class="text-[18px] font-semibold" 
                v-text="cart.formatted_grand_total"
            >
            </p>
        </div>

        <a 
            href="{{ route('shop.checkout.onepage.index') }}" 
            class="block place-self-end bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer mt-[15px]"
        >
            @lang('shop::app.checkout.cart.proceed-to-checkout')
        </a>
    </div>
</div>