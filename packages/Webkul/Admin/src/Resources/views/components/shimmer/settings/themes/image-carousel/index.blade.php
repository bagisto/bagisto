<div>
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-x-3">
                    <div class="flex flex-col gap-1">
                        <div class="shimmer h-4 w-12"></div>
                        
                        <div class="shimmer h-4 w-52">
                        </div>
                    </div>
    
                    <!-- Add Slider Button -->
                    <div class="flex gap-2.5">
                        <div class="secondary-button h-[39px] w-[106px]"></div>
                    </div>
                </div>
    
                <!-- Image Carousel details -->
                @for ($i = 1; $i < 5; $i++)
                    <div class="grid border-b border-slate-300 pt-4 last:!border-0 dark:border-gray-800">
                        <div class="flex justify-between gap-2.5 py-5">
                            <div class="flex gap-3">
                                <div class="grid place-content-start gap-2">
                                    <div class="shimmer block h-4 w-16"></div>
            
                                    <div class="shimmer block h-4 w-16"></div>
            
                                    <div class="shimmer block h-4 w-[534px]"></div>
                                </div>
                            </div>
        
                            <div class="shimmer h-4 w-12"></div>
                        </div>
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