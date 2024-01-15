@props(['attributeCount' => 3, 'productCount' => 3])

<div class="flex justify-between items-center">
    <h2 class="shimmer w-[200px] h-[39px]"></h2>

    <div class="shimmer w-[150px] h-[50px] rounded-xl"></div>
</div>

<div class="grid mt-16 overflow-auto journal-scroll">
    <!-- Single row -->
    <div class="flex items-center max-w-full border-b border-[#E9E9E9] ">
        <div class="min-w-[304px] max-w-full max-sm:hidden">
            <p class="shimmer w-[55%] h-[21px]"></p>
        </div>

        <div class="flex gap-3 border-l-[1px] border-[#E9E9E9] max-sm:border-0">
            <x-shop::shimmer.products.cards.grid
                class="min-w-[311px] max-w-[311px] pt-0 ltr:pr-0 rtl:pl-0 p-5 max-sm:ltr:pl-0 max-sm:rtl:pr-0"
                count="3"
            />
        </div>
    </div>

    <!-- Single row -->
    <x-shop::shimmer.compare.attribute
        :attributeCount="$attributeCount"
        :productCount="$productCount"
    />
</div>