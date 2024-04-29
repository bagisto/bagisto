<div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
                <!-- Heading -->
                <div class="flex flex-col gap-1">
                    <div class="shimmer h-4 w-24"></div>
                    
                    <div class="shimmer h-4 w-[434px]"></div>
                </div>

                <!-- Button -->
                <div class="flex gap-2.5">
                    <div class="secondary-button h-10 w-24"></div>
                </div>
            </div>

            <!-- Footer Links -->
            @for ($i = 0; $i < 4; $i++)
                <div class="grid border-b border-slate-300 last:border-b-0 dark:border-gray-800">
                    <div class="flex cursor-pointer justify-between gap-2.5 py-5">
                        <div class="flex gap-2.5">
                            <div class="grid place-content-start gap-1.5">                                    
                                <div class="shimmer h-5 w-32"></div>

                                <div class="shimmer h-4 w-[475px]"></div>

                                <div class="shimmer h-4 w-24"></div>

                                <div class="shimmer h-4 w-20"></div>
                            </div>
                        </div>

                        <div class="grid place-content-start gap-1 text-right">
                            <div class="flex items-center gap-x-5">
                                <div class="shimmer h-4 w-8"></div>

                                <div class="shimmer h-4 w-10"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
            
    <!-- General -->
    <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
        <div class="box-shadow rounded bg-white px-4 dark:bg-gray-900">
            <div class="flex items-center justify-between py-4">
                <div class="shimmer h-4 w-20"></div>

                <div class="shimmer h-4 w-4"></div>
            </div>

            @for ($i = 1; $i < 4; $i++)    
                <div class="mb-4">
                    <div class="shimmer mb-1.5 flex h-4 w-16 items-center gap-1"></div>

                    <div class="flex h-10 w-full rounded-md border px-3 py-2 transition-all dark:border-gray-800 dark:bg-gray-900"></div>
                </div>
            @endfor

            <!-- Status -->
            <div class="mb-5 grid grid-cols-1 gap-2">
                <div class="shimmer h-4 w-12"></div>

                <div class="shimmer h-5 w-10 rounded-full"></div>
            </div>
        </div>
    </div>
</div>