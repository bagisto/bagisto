@for ($i = 0; $i < 3; $i++)
    <div class="flex justify-between gap-2.5 border-b border-slate-300 p-4 dark:border-gray-800">
        <!-- Left Information -->
        <div class="flex gap-2.5">
            <!-- Image -->
            <div class="shimmer h-11.5 w-11.5 rounded-sm">
            </div>
            <!-- Details -->

            <div class="grid place-content-start gap-1.5">
                <p class="shimmer h-4.25 w-87.5"></p>
                <p class="shimmer h-4.25 w-37.5"></p>
            </div>
        </div>

        <!-- Right Information -->
        <div class="grid place-content-center gap-1 text-right">
                <p class="shimmer h-4.25 w-12.5"></p>
        </div>
    </div>
@endfor