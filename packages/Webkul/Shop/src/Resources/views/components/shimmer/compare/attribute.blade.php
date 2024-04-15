@props(['attributeCount' => 3, 'productCount' => 3])

@for ($i = 0;  $i < $attributeCount; $i++)
    <div class="flex max-w-full items-center border-b border-[#E9E9E9] max-sm:border-0">
        <div class="min-w-[304px] max-w-full max-sm:hidden">
            <p class="shimmer h-[21px] w-[55%]"></p>
        </div>
        
        <div class="flex gap-3 border-l-[1px] border-[#E9E9E9] max-sm:border-0">
            @for ($j = 0;  $j < $productCount; $j++)
                <div class="w-[311px] max-w-[311px] p-5 ltr:pr-0 max-sm:ltr:pl-0 rtl:pl-0 max-sm:rtl:pr-0">
                    <div class="grid gap-1.5">
                        <p class="shimmer hidden h-[21px] w-[55%] max-sm:block"></p>
                        <p class="shimmer h-[21px] w-[55%]"></p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
@endfor