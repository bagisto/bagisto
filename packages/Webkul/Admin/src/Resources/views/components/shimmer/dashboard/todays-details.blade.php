<div class="rounded-[4px] box-shadow">
    <div class="flex gap-[16px] flex-wrap p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800">
        <div class="flex gap-[10px] flex-1 min-w-[200px]">
            <div class="shimmer w-[60px] h-[60px]"></div>

            <div class="grid gap-[4px] place-content-start">
                <div class="shimmer w-[60px] h-[17px]"></div>

                <div class="shimmer w-[100px] h-[17px]"></div>
                
                <div class="shimmer w-[40px] h-[17px]"></div>
            </div>
        </div>

        <div class="flex gap-[10px] flex-1 min-w-[200px]">
            <div class="shimmer w-[60px] h-[60px]"></div>

            <div class="grid gap-[4px] place-content-start">
                <div class="shimmer w-[60px] h-[17px]"></div>

                <div class="shimmer w-[100px] h-[17px]"></div>
                
                <div class="shimmer w-[40px] h-[17px]"></div>
            </div>
        </div>

        <div class="flex gap-[10px] flex-1 min-w-[200px]">
            <div class="shimmer w-[60px] h-[60px]"></div>

            <div class="grid gap-[4px] place-content-start">
                <div class="shimmer w-[60px] h-[17px]"></div>

                <div class="shimmer w-[100px] h-[17px]"></div>
                
                <div class="shimmer w-[40px] h-[17px]"></div>
            </div>
        </div>
    </div>

    @for ($i = 1; $i <= 5; $i++)
        <div class="row grid grid-cols-4 gap-y-[24px] p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-1580:grid-cols-3 max-sm:grid-cols-1">
            <div class="flex gap-[10px]">
                <div class="flex flex-col gap-[6px]">
                    <div class="shimmer w-[30px] h-[17px]"></div>

                    <div class="shimmer w-[130px] h-[17px]"></div>

                    <div class="shimmer w-[60px] h-[19px] rounded-[35px]"></div>
                </div>
            </div>

            <div class="flex flex-col gap-[6px]">
                <div class="shimmer w-[50px] h-[17px]"></div>

                <div class="shimmer w-[180px] h-[17px]"></div>

                <div class="shimmer w-[60px] h-[17px]"></div>
            </div>

            <div class="flex flex-col gap-[6px]">
                <div class="shimmer w-[130px] h-[17px]"></div>

                <div class="shimmer w-[130px] h-[17px]"></div>

                <div class="shimmer w-[130px] h-[17px]"></div>
            </div>

            <div class="max-1580:col-span-full">
                <div class="flex gap-[6px] items-center justify-between">
                    <div class="flex gap-[6px] items-center flex-wrap">
                        <div class="shimmer w-[65px] h-[65px] rounded-[4px]"></div>
                    </div>
                    
                    <div class="shimmer w-[36px] h-[36px] rounded-[6px]"></div>
                </div>
            </div>
        </div>
    @endfor
</div>