@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-2.5  content-start relative max-sm:grid-cols-1 max-w-[291px] {{ $attributes["class"] }}">
        <div class="relative overflow-hidden  rounded-sm min-h-[300px] bg-[#E9E9E9] shimmer ">
            <img class="rounded-sm bg-[#F5F5F5]" src="">
        </div>

        <div class="grid gap-2.5 content-start">
            <p class="w-[75%] h-[24px] bg-[#E9E9E9] shimmer"></p>
            <p class="w-[55%] h-[24px] bg-[#E9E9E9] shimmer"></p>
            
            <div class="flex gap-4 mt-[12px]">
                <span class="rounded-full w-[30px] h-[30px] block bg-[#E9E9E9] shimmer"></span>
                <span class="rounded-full w-[30px] h-[30px] block bg-[#E9E9E9] shimmer"></span>
            </div>
        </div>
    </div>
@endfor