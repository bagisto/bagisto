@for ($i = 0; $i < 3; $i++)
    <div class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300">
        <!-- Left Information -->
        <div class="flex gap-[10px]">
            <!-- Image -->
            <div class="shimmer w-[46px] h-[46px] rounded-[4px]">
            </div>
            <!-- Details -->

            <div class="grid gap-[6px] place-content-start">
                <p class="shimmer w-[350px] h-[17px]"></p>
                <p class="shimmer w-[150px] h-[17px]"></p>
            </div>
        </div>

        <!-- Right Information -->
        <div class="grid gap-[4px] place-content-center text-right">
                <p class="shimmer w-[50px] h-[17px]"></p>
        </div>
    </div>
@endfor