@props(['count' => 0])

<div class="mt-14 grid gap-5 max-1060:grid-cols-[1fr] max-md:mt-5">
    @for ($i = 0;  $i < $count; $i++)
        <!-- Single Card -->
        <div class="rounded-xl border border-zinc-200 p-6 max-md:grid max-md:gap-2.5 max-md:p-4">
            <div class="flex gap-5 max-md:gap-2.5">
                <div class="shimmer h-[146px] max-h-[146px] w-32 min-w-32 max-w-32 rounded-xl max-md:h-20 max-md:w-20 max-md:min-w-20 max-md:rounded-lg"></div>

                <div class="w-full">
                    <div class="flex justify-between max-md:grid">
                        <p class="shimmer h-7 w-40 max-md:h-6 max-md:w-32"></p>

                        <!-- For Desktop Screen -->
                        <div class="flex items-center gap-0.5 max-md:hidden">
                            <span class="shimmer h-9 w-7"></span>
                            <span class="shimmer h-9 w-7"></span>
                            <span class="shimmer h-9 w-7"></span>
                            <span class="shimmer h-9 w-7"></span>
                            <span class="shimmer h-9 w-7"></span>
                        </div>
                    </div>

                    <p class="shimmer mt-2.5 h-5 w-28 max-md:mt-0 max-md:h-4 max-md:w-24"></p>

                    <!-- For Mobile Screen -->
                    <div class="mt-1 hidden items-center max-md:flex">
                        <span class="shimmer h-9 w-7"></span>
                        <span class="shimmer h-9 w-7"></span>
                        <span class="shimmer h-9 w-7"></span>
                        <span class="shimmer h-9 w-7"></span>
                        <span class="shimmer h-9 w-7"></span>
                    </div>

                    <p class="shimmer mt-5 h-6 w-full max-md:hidden"></p>

                    <p class="shimmer mt-2.5 h-6 w-3/4 max-md:hidden"></p>
                </div>
            </div>

            <!-- For Mobile Screen -->
            <div class="grid md:hidden">
                <p class="shimmer h-4 w-full"></p>

                <p class="shimmer h-4 w-3/4"></p>
            </div>
        </div>
    @endfor
</div>
