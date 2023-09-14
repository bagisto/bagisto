@props(['count' => 0])

<div class="container mt-[60px] max-lg:px-[30px] max-sm:mt-[20px]">
    <div class="relative">
        <div class="flex gap-10 overflow-auto scrollbar-hide max-sm:gap-4">
            @for ($i = 0;  $i < $count; $i++)
                <div class="grid grid-cols-1 gap-[15px] justify-items-center min-w-[120px]">
                    <div class="shimmer relative w-[110px] h-[110px] overflow-hidden rounded-full">
                        <img class="bg-[#F5F5F5] rounded-sm">
                    </div>

                    <p class="shimmer w-[90px] h-[27px] rounded-[18px]"></p>
                </div>
            @endfor
        </div>

        <span class="shimmer flex absolute top-[37px] -left-[41px] w-[50px] h-[50px] rounded-full"></span>

        <span class="shimmer flex absolute top-[37px] -right-[22px] w-[50px] h-[50px] rounded-full"></span>
    </div>
</div>
