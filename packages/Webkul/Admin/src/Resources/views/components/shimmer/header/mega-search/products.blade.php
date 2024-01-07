@for ($i = 0; $i < 3; $i++)
    <div class="flex gap-2.5 justify-between p-4 border-b border-slate-300 dark:border-gray-800">
        <!-- Left Information -->
        <div class="flex gap-2.5">
            <!-- Image -->
            <div class="shimmer w-[46px] h-[46px] rounded">
            </div>
            <!-- Details -->

            <div class="grid gap-1.5 place-content-start">
                <p class="shimmer w-[350px] h-[17px]"></p>
                <p class="shimmer w-[150px] h-[17px]"></p>
            </div>
        </div>

        <!-- Right Information -->
        <div class="grid gap-1 place-content-center text-right">
                <p class="shimmer w-[50px] h-[17px]"></p>
        </div>
    </div>
@endfor