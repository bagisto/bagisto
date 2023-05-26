<x-shop::layouts.account>
    <div class="flex justify-between items-center">
        <div class="">
            <div class="flex gap-x-[4px] items-center mb-[10px]">
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                    @lang('shop::app.customers.account.title')
                </p>

                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                    @lang('shop::app.customers.account.orders.title')
                </p>

                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                    @lang('shop::app.customers.account.orders.view')
                </p>
            </div>

            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.orders.title')
                 #{{ $order->id }}
            </h2>
        </div>
    </div>

</x-shop::layouts.account>
