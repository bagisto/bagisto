@props(['attributeCount' => 3, 'productCount' => 3])

@for ($i = 0;  $i < $attributeCount; $i++)
    <div class="flex items-center max-w-full border-b-[1px] border-[#E9E9E9] max-sm:border-0">
        <div class="min-w-[304px] max-w-full max-sm:hidden">
            <p class="w-[55%] h-[21px] bg-[#E9E9E9] shimmer"></p>
        </div>
        
        <div class="flex gap-[12px] border-l-[1px] border-[#E9E9E9] max-sm:border-0">
            @for ($j = 0;  $j < $productCount; $j++)
                <div class="w-[311px] max-w-[311px] pr-0 p-[20px] max-sm:pl-0">
                    <div class="grid gap-[5px]">
                        <p class="hidden w-[55%] h-[21px] bg-[#E9E9E9] max-sm:block shimmer"></p>
                        <p class="w-[55%] h-[21px] bg-[#E9E9E9] shimmer"></p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
@endfor