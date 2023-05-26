<x-shop::layouts.account>
    <div class="max-lg:hidden">
        <div class="flex gap-x-[4px] items-center mb-[10px]">
            <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                 @lang('shop::app.layouts.profile')
            </p>

            <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                @lang('shop::app.customer.account.wishlist.title')
            </p>
        </div>

        <h2 class="text-[26px] font-medium">
            @lang('shop::app.customer.account.wishlist.page-title')
        </h2>

        <!-- wishlist Products -->

        @if ($items->count())
            @foreach ($items as $item)  
                @include('shop::customers.account.wishlist.wishlist-product', [
                    'item' => $item,
                ])
            @endforeach
        @endif
    </div>
</x-shop::layouts.account>