<div>
    <!-- Heading Section -->
    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <div class="shimmer h-6 w-[151px] pt-1.5"></div>

            <div class="shimmer h-4 w-[151px]"></div>
        </div>
    </div>

    <!-- Main Content Pannel -->
    <div class="box-shadow flex h-[calc(100vh-179px)] max-w-max flex-col justify-between rounded-md bg-white dark:bg-gray-900">
        <div>
            <!-- Multiple Tabs -->
            <div class="journal-scroll flex overflow-auto border-b dark:border-gray-800">
                <div class="flex w-[85px] gap-1 border-b-2 px-4 py-4">
                    <div class="shimmer h-[18px] w-[110px]"></div>
                </div>
    
                @for ($i = 1; $i < 6; $i++)
                    <div class="flex w-[153px] gap-1 border-b-2 px-4 py-4">
                        <div class="shimmer h-[18px] w-[110px]"></div>
                    </div>    
                @endfor
            </div>

            <!-- Notifications List -->
            <div class="journal-scroll grid max-h-[calc(100vh-330px)] overflow-auto">
                @for ($i = 1; $i < 7; $i++)
                    <div class="flex h-14 items-start gap-1.5 p-4 hover:bg-gray-50 dark:hover:bg-gray-950">
                        <div class="shimmer h-6 w-6 rounded-full"></div>

                        <div class="grid gap-1">
                            <div class="shimmer h-[17px] w-[122px]"></div>

                            <div class="shimmer h-[14px] w-[80px]"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="flex items-center gap-x-2 border-t p-4 dark:border-gray-800">
            <div class="shimmer h-[38px] w-[38px] rounded ltr:ml-2 rtl:mr-2"></div>

            <div class="shimmer h-6 w-[64px]"></div>

            <div class="shimmer h-6 w-[54px]"></div>

            <div class="shimmer h-[38px] w-[38px] rounded"></div>

            <div class="shimmer h-[38px] w-[38px] rounded"></div>
        </div>
    </div>
</div>