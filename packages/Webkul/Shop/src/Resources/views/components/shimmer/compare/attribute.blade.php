@props(['attributeCount' => 3, 'productCount' => 3])

@for ($i = 0;  $i < $attributeCount; $i++)
    <div class="flex max-w-full items-center border-b border-zinc-200 max-sm:border-0">
        <div class="min-w-76 max-w-full max-md:min-w-40 max-sm:min-w-27.5">
            <p class="shimmer h-5.25 w-[55%]"></p>
        </div>

        <div class="flex gap-3 border-l-[1px] border-zinc-200">
            @for ($j = 0;  $j < $productCount; $j++)
                <div class="w-77.75 max-w-77.75 p-5 max-md:max-w-60 max-sm:max-w-47.5 ltr:pr-0 rtl:pl-0">
                    <div class="grid gap-1.5">
                        <p class="shimmer hidden h-5.25 w-[55%] max-sm:block"></p>
                        <p class="shimmer h-5.25 w-[55%]"></p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
@endfor