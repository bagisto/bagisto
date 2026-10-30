@props(['count' => 2])

@for ($i = 0; $i < $count; $i++)
    <div class="mb-3 rounded-lg border border-zinc-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex gap-4">
            <div class="shimmer mt-1 h-4 w-4 shrink-0 rounded-sm"></div>

            <div class="shimmer h-20 w-20 shrink-0 rounded-lg"></div>

            <div class="flex w-full flex-col gap-2">
                <div class="flex justify-between gap-4">
                    <div class="shimmer h-5 w-1/2"></div>

                    <div class="shimmer h-5 w-16"></div>
                </div>

                <div class="shimmer h-4 w-1/3"></div>

                <div class="shimmer h-4 w-2/5"></div>

                <div class="shimmer h-4 w-1/4"></div>
            </div>
        </div>
    </div>
@endfor
