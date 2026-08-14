<div class="box-shadow rounded-sm">
    <div class="flex flex-wrap gap-4 border-b bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex min-w-50 flex-1 gap-2.5">
            <div class="shimmer h-15 w-15"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-4.25 w-15"></div>

                <div class="shimmer h-4.25 w-25"></div>
                
                <div class="shimmer h-4.25 w-10"></div>
            </div>
        </div>

        <div class="flex min-w-50 flex-1 gap-2.5">
            <div class="shimmer h-15 w-15"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-4.25 w-15"></div>

                <div class="shimmer h-4.25 w-25"></div>
                
                <div class="shimmer h-4.25 w-10"></div>
            </div>
        </div>

        <div class="flex min-w-50 flex-1 gap-2.5">
            <div class="shimmer h-15 w-15"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-4.25 w-15"></div>

                <div class="shimmer h-4.25 w-25"></div>
                
                <div class="shimmer h-4.25 w-10"></div>
            </div>
        </div>
    </div>

    @for ($i = 1; $i <= 5; $i++)
        <div class="border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
            <div class="flex flex-wrap gap-4">
                <div class="flex min-w-45 flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-4.25 w-7.5"></div>

                        <div class="shimmer h-4.25 w-32.5"></div>

                        <div class="shimmer h-4.75 w-15 rounded-[35px]"></div>
                    </div>
                </div>

                <div class="flex min-w-45 flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-4.25 w-12.5"></div>

                        <div class="shimmer h-4.25 w-45"></div>

                        <div class="shimmer h-4.25 w-15"></div>
                    </div>
                </div>

                <div class="flex min-w-45 flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-4.25 w-32.5"></div>

                        <div class="shimmer h-4.25 w-32.5"></div>

                        <div class="shimmer h-4.25 w-32.5"></div>
                    </div>
                </div>

                <div class="flex min-w-45 flex-1 items-center justify-between gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <div class="shimmer h-16.25 w-16.25 rounded-sm"></div>
                            
                            <div class="shimmer h-16.25 w-16.25 rounded-sm"></div>
                        </div>
                    </div>

                    <div class="shimmer h-9 w-9 rounded-md"></div>
                </div>
            </div>
        </div>
    @endfor
</div>