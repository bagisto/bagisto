<div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
    {{-- Breadcrumb --}}
    <div class="flex justify-start mt-[30px] max-lg:hidden">
        <div class="flex gap-x-[14px] items-center">
            <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                {{-- @translations --}}
                @lang('Home')
                <span class="icon-arrow-right text-[24px]"></span>
            </p>
            <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                {{-- @translations --}}
                @lang('Checkout')
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

            {{--Bottom Buttons --}}
            <div class="flex justify-between items-center flex-wrap gap-[15px] mb-[60px] max-sm:mb-[10px]">
                <div class="w-[40%] h-[24px] shimmer"></div>
                <div class="w-[30%] h-[46px] shimmer rounded-[18px] "></div>
            </div>
        </div>
    
        <x-shop::shimmer.checkout.onepage.cart-summary></x-shop::shimmer.checkout.onepage.cart-summary>
    </div>
</div>
