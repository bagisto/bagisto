<div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
    <div class="flex gap-[40px] mt-[40px] items-start max-lg:gap-[20px]">

        {{-- Filter Shimmer Effect --}}
        <x-shop::shimmer.categories.filters></x-shop::shimmer.categories.filters>

        <div class="flex-1">
            {{-- Toolbar Shimmer Effect --}}
            <x-shop::shimmer.categories.toolbar></x-shop::shimmer.categories.toolbar>

            @if(request()->query('mode') =='list')
                <div class="grid grid-cols-1 gap-[25px] mt-[30px]">
                    <x-shop::shimmer.products.cards.list count="12"></x-shop::shimmer.products.cards.list>
                </div>
            @else
                <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-868:grid-cols-1 max-sm:justify-items-center">
                    {{-- Product Card Shimmer Effect --}}
                    <x-shop::shimmer.products.cards.grid count="12"></x-shop::shimmer.products.cards.grid> 
                </div> 
            @endif

            <button class="w-[171.516px] h-[48px] block mx-auto py-[11px] rounded-[18px] mt-[60px] shimmer"></button>
        </div>
    </div>
</div>