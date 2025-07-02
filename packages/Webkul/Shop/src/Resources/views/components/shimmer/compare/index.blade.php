@props(['attributeCount' => 3, 'productCount' => 3])

<div class="flex items-center justify-between">
    <h2 class="shimmer h-8 w-[200px] max-sm:w-[180px]"></h2>

    <div class="shimmer h-[50px] w-[150px] rounded-xl max-md:h-[42px] max-md:w-[115px] max-sm:h-[34px] max-sm:rounded-xl"></div>
</div>

<div class="journal-scroll mt-16 grid overflow-auto max-md:mt-7">
    <!-- Single row -->
    @for ($i = 1; $i <= $attributeCount; $i++)
        <div class="flex max-w-full items-center border-b border-zinc-200">
            <div class="min-w-[304px] max-w-full max-md:min-w-40 max-sm:min-w-[110px]">
                <p class="shimmer h-[21px] w-[55%]"></p>
            </div>

            <div class="flex gap-3 border-zinc-200 max-md:gap-0 max-sm:border-0 ltr:border-l-[1px] rtl:border-r-[1px]">
                <x-shop::shimmer.products.cards.grid
                    class="min-w-[311px] max-w-[311px] p-5 pt-0 max-md:min-w-60 max-md:px-2.5 max-sm:min-w-[190px] max-sm:pb-2.5"
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