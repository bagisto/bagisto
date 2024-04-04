<div class="w-[418px] max-w-full">
    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.before') !!}

    <p
        class="text-2xl font-medium"
        role="heading"
    >
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </p>

    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.after') !!}

    <!-- Cart Totals -->
    <div class="grid gap-4 mt-6">
        <!-- Sub Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.before') !!}

        <div class="flex justify-between text-right">
            <p class="text-base">
                @lang('shop::app.checkout.cart.summary.sub-total')
            </p>

            <p class="text-base font-medium">
                @{{ cart.formatted_sub_total }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.after') !!}

        <!-- Taxes -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.before') !!}

        <div 
            class="flex justify-between text-right"
            v-for="(amount, index) in cart.base_tax_amounts"
            v-if="parseFloat(cart.base_tax_total)"
        >
            <p class="text-base max-sm:text-sm max-sm:font-normal">
                @lang('shop::app.checkout.cart.summary.tax') (@{{ index }})%
            </p>

            <p class="text-base font-medium max-sm:text-sm max-sm:font-medium">
                @{{ amount }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.after') !!}

        <!-- Discount -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.before') !!}

        <div 
            class="flex justify-between text-right"
            v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
        >
            <p class="text-base">
                @lang('shop::app.checkout.cart.summary.discount-amount')
            </p>

            <p class="text-base font-medium">
                @{{ cart.formatted_base_discount_amount }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.after') !!}

        <!-- Shipping Rates -->
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

        <div
            class="flex text-right justify-between"
            v-if="cart.selected_shipping_rate"
        >
            <p class="text-base">
                @lang('shop::app.checkout.onepage.summary.delivery-charges')
            </p>

            <p class="text-base font-medium">
                @{{ cart.selected_shipping_rate }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

        <!-- Apply Coupon -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.before') !!}
        
        @include('shop::checkout.coupon')

        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.after') !!}
   
        <!-- Cart Grand Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.before') !!}

        <div class="flex justify-between text-right">
            <p class="text-lg font-semibold">
                @lang('shop::app.checkout.cart.summary.grand-total')
            </p>

            <p class="text-lg font-semibold">
                @{{ cart.formatted_grand_total }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.after') !!}

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.before') !!}

        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="primary-button place-self-end py-3 mt-4 px-11 rounded-2xl"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.after') !!}
    </div>
</div>