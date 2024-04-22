<div>
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
                    <div class="flex flex-col gap-1">
                        <div class="shimmer h-4 w-16"></div>
                        
                        <div class="shimmer h-4 w-[434px]"></div>
                    </div>
                </div>
    
                @for ($i = 1; $i < 5; $i++)
                    <div class="pt-4">
                        <div class="shimmer mb-1.5 flex h-4 w-16 items-center"></div>

                        <div class="flex h-10 w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"></div>
                    </div>
                @endfor
            </div>
        </div>
                
        <!-- General -->
        <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
            <div class="box-shadow rounded bg-white px-4 dark:bg-gray-900">
                <div class="flex items-center justify-between py-4">
                    <div class="shimmer h-5 w-20"></div>

                    <div class="shimmer h-5 w-6"></div>
                </div>

                @for ($i = 1; $i < 4; $i++)    
                    <div class="mb-4">
                        <div class="shimmer mb-1.5 flex h-4 w-16 items-center gap-1"></div>

                        <div class="flex h-10 w-full rounded-md border px-3 py-2 transition-all dark:border-gray-800 dark:bg-gray-900"></div>
                    </div>
                @endfor

                <div class="mb-5 grid grid-cols-1 gap-2">
                    <div class="shimmer h-4 w-12"></div>

                    <div class="shimmer h-5 w-10 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</div>