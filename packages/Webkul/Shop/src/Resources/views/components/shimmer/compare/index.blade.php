@props(['attributeCount' => 3, 'productCount' => 3])

<div class="flex items-center justify-between">
    <h2 class="shimmer h-8 w-50 max-sm:w-45"></h2>

    <div class="shimmer h-12.5 w-37.5 rounded-xl max-md:h-10.5 max-md:w-28.75 max-sm:h-8.5 max-sm:rounded-xl"></div>
</div>

<div class="journal-scroll mt-16 grid overflow-auto max-md:mt-7">
    <!-- Single row -->
    @for ($i = 1; $i <= $attributeCount; $i++)
        <div class="flex max-w-full items-center border-b border-zinc-200">
            <div class="min-w-76 max-w-full max-md:min-w-40 max-sm:min-w-27.5">
                <p class="shimmer h-5.25 w-[55%]"></p>
            </div>

            <div class="flex gap-3 border-zinc-200 max-md:gap-0 max-sm:border-0 ltr:border-l-[1px] rtl:border-r-[1px]">
                <x-shop::shimmer.products.cards.grid
                    class="min-w-77.75 max-w-77.75 p-5 pt-0 max-md:min-w-60 max-md:px-2.5 max-sm:min-w-47.5 max-sm:pb-2.5"
                    count="3"
                />
            </div>
        </div>
    @endfor

    <!-- Single row -->
    <x-shop::shimmer.compare.attribute
        :attributeCount="$attributeCount"
        :productCount="$productCount"
    />
</div>