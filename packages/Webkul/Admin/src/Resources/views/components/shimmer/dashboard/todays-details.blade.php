<div class="box-shadow rounded">
    <div class="flex flex-wrap gap-4 border-b bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex min-w-[200px] flex-1 gap-2.5">
            <div class="shimmer h-[60px] w-[60px]"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-[17px] w-[60px]"></div>

                <div class="shimmer h-[17px] w-[100px]"></div>
                
                <div class="shimmer h-[17px] w-10"></div>
            </div>
        </div>

        <div class="flex min-w-[200px] flex-1 gap-2.5">
            <div class="shimmer h-[60px] w-[60px]"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-[17px] w-[60px]"></div>

                <div class="shimmer h-[17px] w-[100px]"></div>
                
                <div class="shimmer h-[17px] w-10"></div>
            </div>
        </div>

        <div class="flex min-w-[200px] flex-1 gap-2.5">
            <div class="shimmer h-[60px] w-[60px]"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-[17px] w-[60px]"></div>

                <div class="shimmer h-[17px] w-[100px]"></div>
                
                <div class="shimmer h-[17px] w-10"></div>
            </div>
        </div>
    </div>

    @for ($i = 1; $i <= 5; $i++)
        <div class="border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
            <div class="flex flex-wrap gap-4">
                <div class="flex min-w-[180px] flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-[17px] w-[30px]"></div>

                        <div class="shimmer h-[17px] w-[130px]"></div>

                        <div class="shimmer h-[19px] w-[60px] rounded-[35px]"></div>
                    </div>
                </div>

                <div class="flex min-w-[180px] flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-[17px] w-[50px]"></div>

                        <div class="shimmer h-[17px] w-[180px]"></div>

                        <div class="shimmer h-[17px] w-[60px]"></div>
                    </div>
                </div>

                <div class="flex min-w-[180px] flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-[17px] w-[130px]"></div>

                        <div class="shimmer h-[17px] w-[130px]"></div>

                        <div class="shimmer h-[17px] w-[130px]"></div>
                    </div>
                </div>

                <div class="flex min-w-[180px] flex-1 items-center justify-between gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <div class="shimmer h-[65px] w-[65px] rounded"></div>
                            
                            <div class="shimmer h-[65px] w-[65px] rounded"></div>
                        </div>
                    </div>

                    <div class="shimmer h-9 w-9 rounded-md"></div>
                </div>
            </div>
        </div>
    @endfor
</div>