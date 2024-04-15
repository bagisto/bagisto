@props(['attributeCount' => 3, 'productCount' => 3])

<div class="flex items-center justify-between">
    <h2 class="shimmer h-[39px] w-[200px]"></h2>

    <div class="shimmer h-[50px] w-[150px] rounded-xl"></div>
</div>

<div class="journal-scroll mt-16 grid overflow-auto">
    <!-- Single row -->
    <div class="flex max-w-full items-center border-b border-[#E9E9E9]">
        <div class="min-w-[304px] max-w-full max-sm:hidden">
            <p class="shimmer h-[21px] w-[55%]"></p>
        </div>

        <div class="flex gap-3 border-l-[1px] border-[#E9E9E9] max-sm:border-0">
            <x-shop::shimmer.products.cards.grid
                class="min-w-[311px] max-w-[311px] p-5 pt-0 ltr:pr-0 max-sm:ltr:pl-0 rtl:pl-0 max-sm:rtl:pr-0"
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