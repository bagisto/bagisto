@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-2.5 relative w-full max-w-[291px] max-sm:grid-cols-1 {{ $attributes["class"] }}">
        <div class="shimmer relative w-full rounded">
            <div class="relative after:content-[' '] after:block after:pb-[calc(100%+9px)]"></div>
        </div>

        <div class="grid gap-2.5 content-start">
            <p class="shimmer w-3/4 h-6"></p>
            <p class="shimmer w-[55%] h-6"></p>

            <!-- Needs to implement that in future -->
            <div class="hidden flex gap-4 mt-3">
                <span class="shimmer w-[30px] h-[30px] block rounded-full"></span>
                <span class="shimmer w-[30px] h-[30px] block rounded-full"></span>
            </div>
        </div>
    </div>
@endfor
