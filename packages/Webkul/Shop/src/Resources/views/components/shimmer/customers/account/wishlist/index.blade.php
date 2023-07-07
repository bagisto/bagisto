@props(['count' => 0])

<div class="flex justify-between items-center overflow-auto journal-scroll">
    <h2 class="w-[110px] h-[39px] shimmer"></h2>

    <div class="flex items-center gap-x-[10px] rounded-[12px] py-[12px] px-[20px] shimmer">
        <span class="w-[108px] h-[24px]"></span>
    </div>
</div>

<div class="overflow-auto journal-scroll">
    @for ($i = 0;  $i < $count; $i++)
        <div>
            <div class="flex gap-[40px] py-[25px] items-center  border-[#E9E9E9] justify-between">
                <div class="flex gap-x-[15px] max-w-[276px] min-w-[276px]">
                    <div>
                        <div class="w-[80px] h-[80px] shimmer rounded-[12px]"></div>
                    </div>

                    <div class="grid gap-y-[10px] place-content-start">
                        <p class="w-[180px] h-[24px] shimmer"></p>

                        <p class="w-[180px] h-[24px] shimmer" ></p>

                        <a class="w-[100px] h-[24px] shimmer"></a>
                    </div>
                </div>

                <p class="w-[100px] h-[27px] shimmer"></p>

                <div class="flex gap-x-[25px] rounded-[54px] py-[10px] px-[20px] items-center max-w-full w-[140px] h-[46px] shimmer"></div>
                
                <div class="block py-[11px] px-[25px] rounded-[54px] w-[157px] h-[46px] shimmer"></div>
            </div>
        </div>
    @endfor
</div>

