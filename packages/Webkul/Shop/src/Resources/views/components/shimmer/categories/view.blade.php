<div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
    <div class="flex gap-[40px] md:mt-[40px] items-start max-lg:gap-[20px]">
        {{-- Desktop Filter Shimmer Effect --}}
        <div class="max-md:hidden">
            <x-shop::shimmer.categories.filters/>
        </div>

        <div class="flex-1">
            {{-- Desktop Toolbar Shimmer Effect --}}
            <div class="max-md:hidden">
                <x-shop::shimmer.categories.toolbar/>
            </div>

            {{-- Product Card Container --}}
            @if(request()->query('mode') =='list')
                <div class="grid grid-cols-1 gap-[25px] mt-[30px]">
                    <x-shop::shimmer.products.cards.list count="12"></x-shop::shimmer.products.cards.list>
                </div>
            @else
                <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-sm:justify-items-center max-sm:gap-[16px]">
                    {{-- Product Card Shimmer Effect --}}
                    <x-shop::shimmer.products.cards.grid count="12"></x-shop::shimmer.products.cards.grid> 
                </div> 
            @endif

            <button class="shimmer block w-[171.516px] h-[48px] mt-[60px] mx-auto py-[11px] rounded-[18px]"></button>
        </div>
    </div>
</div>