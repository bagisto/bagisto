<!-- General -->
<div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
    <div class="box-shadow rounded bg-white px-4 dark:bg-gray-900">
        <div class="flex items-center justify-between py-4">
            <div class="shimmer h-5 w-20"></div>

            <div class="shimmer h-5 w-6"></div>
        </div>

        @for ($i = 0; $i < 4; $i++)    
            <div class="mb-4">
                <div class="shimmer mb-2 flex h-3.5 w-16 items-center gap-1"></div>

                <div class="shimmer flex h-10 w-full rounded-md py-px"></div>
            </div>
        @endfor

        <div class="mb-5 grid grid-cols-1 gap-2">
            <div class="shimmer h-3.5 w-16"></div>

            <div class="shimmer h-5 w-10 rounded-full"></div>
        </div>
    </div>
</div>