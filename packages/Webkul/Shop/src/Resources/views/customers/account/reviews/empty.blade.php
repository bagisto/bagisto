<x-shop::layouts.account>
    <div class="flex-auto">
        <div class="max-lg:hidden">
            <div class="flex gap-x-[4px] items-center mb-[10px]">
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                    @lang('shop::app.customers.account.profile')
                </p>
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                    @lang('shop::app.customers.account.reviews.title')
                </p>
            </div>
            <h2 class="text-[26px] font-medium">{{ trans('shop::app.customers.account.reviews.title')}}</h2>

            <div class="grid items-center justify-items-center w-max m-auto h-[476px] place-content-center">
                <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">
                <p class="text-[20px]">
                    @lang('shop::app.customers.account.reviews.empty-review')
                </p>
            </div>

        </div>
    </div>
</x-shop::layouts.account>
