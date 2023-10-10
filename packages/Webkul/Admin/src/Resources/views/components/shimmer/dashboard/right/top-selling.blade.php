<div class="flex flex-col">
    @for ($i = 1; $i <= 3; $i++)
        <div class="flex gap-[10px] p-[16px] border-b-[1px] dark:border-gray-800 last:border-b-0">
            {{-- Product Image --}}
            <div class="shimmer w-[65px] h-[65px] rounded-[4px]"></div>

            <!-- Product Detailes -->
            <div class="flex flex-col gap-[6px] w-[251px]">
                {{-- Product Name --}}
                <div class="shimmer w-full h-[17px]"></div>

                <div class="flex justify-between">
                    {{-- Product Price --}}
                    <div class="shimmer w-[52px] h-[17px]"></div>

                    {{-- Grand Total --}}
                    <div class="shimmer w-[72px] h-[17px]"></div>
                </div>
            </div>
        </div>
    @endfor
</div>