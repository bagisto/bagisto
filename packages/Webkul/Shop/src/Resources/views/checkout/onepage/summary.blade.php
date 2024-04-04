<!-- Header -->
<h1 class="text-2xl font-medium max-sm:text-xl">
    @lang('shop::app.checkout.onepage.summary.cart-summary')
</h1>

<!-- Cart Items -->
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

            <p class="text-base text-navyBlue max-sm:text-sm max-sm:font-medium">
                @{{ item.name }}
            </p>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.after') !!}

            <p class="mt-2.5 text-lg font-medium max-sm:text-sm max-sm:font-normal">
                @lang('shop::app.checkout.onepage.summary.price_&_qty', ['price' => '@{{ item.formatted_price }}', 'qty' => '@{{ item.quantity }}'])
            </p>
        </div>
    </div>
</div>

<!-- Cart Totals -->
<div class="grid gap-4 mt-6 mb-8">
    <!-- Sub Total -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.before') !!}

    <div class="flex text-right justify-between">
        <p class="text-base max-sm:text-sm max-sm:font-normal">
            @lang('shop::app.checkout.onepage.summary.sub-total')
        </p>

        <p class="text-base font-medium max-sm:text-sm">
            @{{ cart.base_sub_total }}
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.after') !!}


    <!-- Taxes -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.before') !!}

    <div
        class="flex text-right justify-between"
        v-for="(amount, index) in cart.base_tax_amounts"
        v-if="parseFloat(cart.base_tax_total)"
    >
        <p class="text-base max-sm:text-sm max-sm:font-normal">
            @lang('shop::app.checkout.onepage.summary.tax') (@{{ index }})%
        </p>

        <p class="text-base font-medium max-sm:text-sm">
            @{{ amount }}
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.after') !!}

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

    <!-- Discount -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.before') !!}

    <div
        class="flex text-right justify-between"
        v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
    >
        <p class="text-base">
            @lang('shop::app.checkout.onepage.summary.discount-amount')
        </p>

        <p class="text-base font-medium">
            @{{ cart.formatted_base_discount_amount }}
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.after') !!}

    <!-- Apply Coupon -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.before') !!}

    @include('shop::checkout.coupon')

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.after') !!}

    <!-- Cart Grand Total -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.before') !!}

    <div class="flex text-right justify-between">
        <p class="text-lg font-semibold">
            @lang('shop::app.checkout.onepage.summary.grand-total')
        </p>

        <p class="text-lg font-semibold">
            @{{ cart.base_grand_total }}
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.after') !!}
</div>