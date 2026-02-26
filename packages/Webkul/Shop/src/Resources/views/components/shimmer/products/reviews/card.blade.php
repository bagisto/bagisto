@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="rounded-xl border border-zinc-200 p-6">
        <div class="flex gap-5">
            <div class="shimmer h-[100px] w-[100px] rounded-xl"></div>

            <div class="flex flex-col gap-0.5">
                <p class="shimmer h-7 w-40"></p
>
                <p class="shimmer mb-2 h-4 w-40"></p>

                <div class="flex items-center gap-0.5">
                    <span class="shimmer h-[30px] w-[30px]"></span>
                    <span class="shimmer h-[30px] w-[30px]"></span>
                    <span class="shimmer h-[30px] w-[30px]"></span>
                    <span class="shimmer h-[30px] w-[30px]"></span>
                    <span class="shimmer h-[30px] w-[30px]"></span>
                </div>
            </div>
        </div>

        <div class="mt-3 flex flex-col gap-4">
            <div class="shimmer h-6 w-[250px]"></div>

            <div class="flex flex-col gap-0.5">
                <p class="shimmer h-6 w-[500px]"></p>
                <p class="shimmer h-6 w-[300px]"></p>
            </div>
        </div>
    </div>
@endfor