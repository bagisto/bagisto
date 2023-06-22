@props(['count' => 0])

<div class="container mt-[60px] max-lg:px-[30px] max-sm:mt-[20px]">
    <div class="bs-item-carousal-wrapper relative">
        <div class="flex gap-10 overflow-auto scrollbar-hide">
            @for ($i = 0;  $i < $count; $i++)
                <div class="grid grid-cols-1 justify-items-center gap-[15px] min-w-[120px]">
                    <div class="relative overflow-hidden rounded-full w-[110px] h-[110px] bg-[#E9E9E9] shimmer">
                        <img
                            class="rounded-sm bg-[#F5F5F5]"
                            src=""
                            alt=""
                        >
                    </div>

                    <p class="w-[90px] h-[32px] rounded-[18px] shimmer"></p>
                </div>
            @endfor
        </div>

        <span class="flex rounded-full w-[50px] h-[50px] absolute top-[37px] -left-[41px] shimmer"></span>

        <span class="flex rounded-full w-[50px] h-[50px] absolute top-[37px] -right-[22px] shimmer"></span>
    </div>
</div>
