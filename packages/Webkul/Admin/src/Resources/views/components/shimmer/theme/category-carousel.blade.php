@for ($i = 0; $i < 3; $i++)
    <div class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300">
        <!-- Information -->
        <div class="flex gap-[10px]">
            <!-- Image -->
            <div class="shimmer w-[57px] h-[57px] rounded-[4px]"></div>

            <!-- Details -->
            <div class="grid gap-[6px] place-content-start">
                <p class="shimmer w-[150px] h-[19px]"></p>
                <p class="shimmer w-[80px] h-[19px]"></p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-[4px] place-content-start text-right">
            <div class="shimmer w-[30px] h-[17px]"></div>
            <div class="shimmer w-[52px] h-[17px]"></div>
        </div>
    </div>
@endfor