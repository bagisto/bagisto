<div>
    <!-- Panel Header -->
    <div class="flex flex-wrap items-center justify-between gap-2.5 border-b p-4 dark:border-gray-800">
        <div class="flex flex-col gap-1">
            <div class="shimmer h-5 w-16"></div>

            <div class="shimmer h-4 w-44"></div>
        </div>

        <!-- Group Actions -->
        <div class="flex flex-wrap items-center gap-1">
            <div class="shimmer h-10 w-28 rounded-md"></div>

            <div class="shimmer h-10 w-24 rounded-md"></div>

            <div class="shimmer h-10 w-28 rounded-md"></div>
        </div>
    </div>

    <!-- Panel Content -->
    <div class="grid grid-cols-3 gap-4 p-4 max-lg:grid-cols-1">
        @for ($column = 0; $column < 3; $column++)
            <div class="flex h-[calc(100vh-270px)] min-h-96 flex-col rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950 max-lg:h-[60vh]">
                <!-- Column Header -->
                <div class="flex items-start justify-between gap-2.5 border-b border-gray-200 px-3 py-2.5 dark:border-gray-800">
                    <div class="flex flex-col gap-1">
                        <div class="shimmer h-4 w-24"></div>

                        <div class="shimmer h-3.5 w-36"></div>
                    </div>

                    <div class="shimmer h-5 w-7 rounded-full"></div>
                </div>

                <!-- Groups -->
                <div class="flex flex-col gap-2.5 overflow-hidden p-2.5">
                    @for ($group = 0; $group < 2; $group++)
                        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex items-center gap-1.5 px-2 py-2">
                                <div class="shimmer h-4.5 w-4.5"></div>

                                <div class="shimmer h-4.5 w-4.5"></div>

                                <div class="shimmer h-4 w-24"></div>

                                <div class="shimmer h-4.5 w-4.5"></div>
                            </div>

                            <div class="flex flex-col gap-0.5 border-t border-gray-100 p-1.5 dark:border-gray-800">
                                @for ($attribute = 0; $attribute < 3; $attribute++)
                                    <div class="flex items-center gap-2 px-2 py-1.5">
                                        <div class="shimmer h-4 w-4"></div>

                                        <div class="shimmer h-4 w-4"></div>

                                        <div class="shimmer h-4 w-28"></div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endfor
    </div>
</div>
