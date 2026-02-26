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
                <div class="shimmer h-10 w-24 rounded-md"></div>
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