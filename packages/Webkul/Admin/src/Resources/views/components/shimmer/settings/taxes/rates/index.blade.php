<!-- Pannel Content -->
<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <!-- Left Component -->
    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
        <div class="box-shadow rounded bg-white p-4 last:pb-0 dark:bg-gray-900">
            <div class="shimmer mb-4 h-[17px] w-16"></div>

            @for ($i = 0; $i < 4; $i++)
                <div class="mb-4">
                    <div class="shimmer mb-1.5 h-4 w-24"></div>

                    <div class="shimmer flex h-[42px] w-full rounded-md"></div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Right Component -->
    <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">
        <div class="box-shadow rounded bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between p-4">
                <p class="shimmer h-[17px] w-24 p-2.5"></p>
                
                <span class="shimmer w-5 p-2.5"></span>
            </div>

            <div class="px-4">
                <div class="mb-4">
                    <div class="shimmer mb-2 h-4 w-24"></div>

                    <div class="shimmer h-5 w-9 rounded-full"></div>
                </div>

                <div class="mb-4">
                    <div class="shimmer mb-2 h-4 w-24"></div>

                    <div class="shimmer h-[42px] w-full rounded-md"></div>
                </div>
            </div> 
        </div>
    </div>
</div>