@props(['count' => 0])

@for ($i = 0; $i < $count; $i++)
<div class="grid grid-cols-1 gap-[25px]">
    <div class="grid gap-2.5 grid-cols-2 relative max-sm:grid-cols-1 max-w-max">
        <div class="relative overflow-hidden rounded-sm min-w-[250px] min-h-[258px] bg-[#E9E9E9] shimmer "> 
            <img
                class="rounded-sm bg-[#F5F5F5]" 
                src=""
            >
        </div>

        <div class="grid gap-[15px] content-start">
            <p class="w-[75%] h-[24px] bg-[#E9E9E9] shimmer"></p>

            <p class="w-[55%] h-[24px] bg-[#E9E9E9] shimmer"></p>

            <div class="flex gap-4"> 
                <span class="rounded-full w-[30px] h-[30px] block bg-[#E9E9E9] shimmer"></span> 

                <span class="rounded-full w-[30px] h-[30px] block bg-[#E9E9E9] shimmer"></span> 
            </div>

            <p class="w-[100%] h-[24px] bg-[#E9E9E9] shimmer"></p>

            <div class="w-[152px] h-[46px] rounded-[12px] shimmer"></div>
        </div>
    </div>
</div>
@endfor
