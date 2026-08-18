<div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="flex items-center justify-between gap-x-2.5">
            <div class="flex flex-col gap-1">
                <div class="shimmer h-[17px] w-16"></div>
                
                <div class="shimmer h-[17px] w-52">
                </div>
            </div>

            <!-- Add Slider Button -->
            <div class="flex gap-2.5">
                <div class="shimmer h-10 w-[105px] rounded-md"></div>
            </div>
        </div>

        <!-- Image Carousel details -->
        @for ($i = 0; $i < 4; $i++)
            <div class="grid border-b border-slate-300 pt-4 last:!border-0 dark:border-gray-800">
                <div class="flex justify-between gap-2.5 py-5">
                    <div class="flex gap-2.5">
                        <div class="grid place-content-start gap-1.5">
                            <div class="shimmer block h-[17px] w-72"></div>
    
                            <div class="shimmer block h-[17px] w-72"></div>
    
                            <div class="shimmer block h-[17px] w-[534px]"></div>
                        </div>
                    </div>

                    <div class="shimmer h-4 w-12"></div>
                </div>
            </div>
        @endfor
    </div>
</div>
