<div class="w-[418px] max-w-full">
    <p
        class="text-2xl font-medium"
        role="heading"
    >
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </p>

    <div class="grid gap-4 mt-6">
        <div class="flex justify-between text-right">
            <p class="text-base">
                @lang('shop::app.checkout.cart.summary.sub-total')
            </p>

            <p 
                class="text-base font-medium"
                v-text="cart.formatted_sub_total"
            >
            </p>
        </div>

        <div 
            class="flex justify-between text-right"
            v-for="(amount, index) in cart.base_tax_amounts"
            v-if="parseFloat(cart.base_tax_total)"
        >
            <p class="text-base max-sm:text-sm max-sm:font-normal">
                @lang('shop::app.checkout.cart.summary.tax') (@{{ index }})%
            </p>

            <p 
                class="text-base font-medium max-sm:text-sm max-sm:font-medium"
                v-text="amount"
            >
            </p>
        </div>

        <div 
            class="flex justify-between text-right"
            v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
        >
            <p class="text-base">
                @lang('shop::app.checkout.cart.summary.discount-amount')
            </p>

            <p 
                class="text-base font-medium"
                v-text="cart.formatted_base_discount_amount"
            >
            </p>
        </div>
        
        @include('shop::checkout.cart.coupon')
   
        <div class="flex justify-between text-right">
            <p class="text-lg font-semibold">
                @lang('shop::app.checkout.cart.summary.grand-total')
            </p>

            <p 
                class="text-lg font-semibold" 
                v-text="cart.formatted_grand_total"
            >
            </p>
        </div>

        <a 
            href="{{ route('shop.checkout.onepage.index') }}" 
            class="block w-max place-self-end py-3 mt-4 px-11 bg-navyBlue rounded-2xl text-white text-base font-medium text-center cursor-pointer"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>
    </div>
</div>