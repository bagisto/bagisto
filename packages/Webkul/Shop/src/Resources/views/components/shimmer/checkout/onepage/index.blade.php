<div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
    {{-- Breadcrumb --}}
    <div class="flex justify-start mt-[30px] max-lg:hidden">
        <div class="flex gap-x-[14px] items-center">
            <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                @lang('shop::app.checkout.onepage.index.home')
                <span class="icon-arrow-right text-[24px]"></span>
            </p>
            <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                @lang('shop::app.checkout.onepage.index.checkout')
            </p>
        </div>
    </div>

    <div class="grid grid-cols-[1fr_auto] gap-[30px] max-lg:grid-cols-[1fr]">
        <div>
            {{-- Billing Address --}}
            <x-shop::shimmer.checkout.onepage.address></x-shop::shimmer.checkout.onepage.address>

            {{-- Shipping method --}}
            <x-shop::shimmer.checkout.onepage.shipping-method></x-shop::shimmer.checkout.onepage.shipping-method>

            {{-- Payment method --}}
            <x-shop::shimmer.checkout.onepage.payment-method></x-shop::shimmer.checkout.onepage.payment-method>
        </div>
    
        <x-shop::shimmer.checkout.onepage.cart-summary></x-shop::shimmer.checkout.onepage.cart-summary>
    </div>
</div>
