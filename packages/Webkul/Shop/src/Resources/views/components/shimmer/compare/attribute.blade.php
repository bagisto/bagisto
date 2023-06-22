@props(['attributeCount' => 3, 'productCount' => 3])

@for ($i = 0;  $i < $attributeCount; $i++)
    <div class="flex items-center max-w-full border-b-[1px] border-[#E9E9E9] ">
        <div class="min-w-[304px] max-w-full">
            <p class="w-[55%] h-[21px] bg-[#E9E9E9] shimmer"></p>
        </div>
        
        <div class="flex gap-[12px] border-l-[1px] border-[#E9E9E9]">
            @for ($j = 0;  $j < $productCount; $j++)
                <div class="w-[311px] max-w-[311px]  pr-0 p-[20px]">
                        <p class="w-[55%] h-[21px] bg-[#E9E9E9] shimmer"></p>
                </div>
            @endfor
        </div>
    </div>
@endfor