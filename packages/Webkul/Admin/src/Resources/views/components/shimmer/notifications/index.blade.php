<div>
    <!-- Heading Section -->
    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <div class="shimmer h-6 w-36 pt-1.5"></div>

            <div class="shimmer h-4 w-40"></div>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="box-shadow flex h-[calc(100vh-179px)] max-w-max flex-col justify-between rounded-md">
        <div>
            <!-- Multiple Tabs -->
            <div class="flex border-b dark:border-gray-800">
                <div class="flex w-[83px] gap-1 border-b-2 px-4 py-4 dark:border-gray-800">
                    <div class="shimmer h-[18px] w-28"></div>
                </div>
    
                @for ($i = 1; $i < 6; $i++)
                    <div class="flex w-[152px] gap-1 border-b-2 px-4 py-4 dark:border-gray-800">
                        <div class="shimmer h-[18px] w-28"></div>
                    </div>    
                @endfor
            </div>

            <!-- Notifications List -->
            <div class="journal-scroll grid max-h-[calc(100vh-330px)] overflow-auto">
                @for ($i = 1; $i < 10; $i++)
                    <div class="flex h-14 items-start gap-1.5 p-4">
                        <div class="shimmer h-6 w-6 rounded-full"></div>

                        <div class="grid gap-1">
                            <div class="shimmer h-4 w-32"></div>

                            <div class="shimmer h-3.5 w-20"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="flex items-center gap-x-2 border-t p-4 dark:border-gray-800">
            <div class="shimmer h-9 w-9 rounded ltr:ml-2 rtl:mr-2"></div>

            <div class="shimmer h-6 w-16"></div>

            <div class="shimmer h-6 w-14"></div>

            <div class="shimmer h-9 w-9 rounded"></div>

            <div class="shimmer h-9 w-9 rounded"></div>
        </div>
    </div>
</div>