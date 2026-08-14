<div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
    <div class="box-shadow rounded-sm bg-white p-4 dark:bg-gray-900">
        <div class="flex items-center justify-between gap-x-2.5">
            <div class="flex flex-col gap-1">
                <div class="shimmer h-4 w-24"></div>

                <div class="shimmer h-4 w-108.5"></div>
            </div>

            <!-- Add Service Content Button -->
            <div class="flex gap-2.5">
                <div class="shimmer h-10 w-31.75 rounded-md"></div>
            </div>
        </div>

        <!-- Service details -->
        @for ($i = 0; $i < 4; $i++)
            <div class="grid border-b border-slate-300 pt-4 last:border-b-0 dark:border-gray-800">
                <div class="flex cursor-pointer justify-between gap-2.5 py-5">
                    <div class="flex gap-2.5">
                        <div class="grid place-content-start gap-1.5">                    
                            <div class="shimmer h-4.25 w-72"></div>

                            <div class="shimmer h-4.25 w-72"></div>

                            <div class="shimmer h-4.25 w-36"></div>
                        </div>
                    </div>

                    <div class="grid place-content-start gap-1 text-right">
                        <div class="flex items-center gap-x-5">
                            <div class="shimmer h-4.25 w-8"></div>

                            <div class="shimmer h-4.25 w-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

