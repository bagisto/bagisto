<div class="container px-[60px] max-lg:px-8 max-sm:px-4">
    <div class="flex items-start gap-10 max-lg:gap-5 md:mt-10">
        <!-- Desktop Filter Shimmer Effect -->
        <div class="max-md:hidden">
            <x-shop::shimmer.categories.filters />
        </div>

        <div class="flex-1">
            <!-- Desktop Toolbar Shimmer Effect -->
            <div class="max-md:hidden">
                <x-shop::shimmer.categories.toolbar />
            </div>

            <!-- Product Card Container -->
            @if(request()->query('mode') =='list')
                <div class="mt-8 grid grid-cols-1 gap-6">
                    <x-shop::shimmer.products.cards.list count="12" />
                </div>
            @else
                <div class="mt-8 grid grid-cols-3 gap-8 max-1060:grid-cols-2 max-md:mt-5 max-md:justify-items-center max-md:gap-x-4 max-md:gap-y-5">
                    <!-- Product Card Shimmer Effect -->
                    <x-shop::shimmer.products.cards.grid count="12" />
                </div> 
            @endif

            <button class="shimmer mx-auto mt-14 block h-12 w-[171.516px] rounded-2xl py-3"></button>
        </div>
    </div>
</div>