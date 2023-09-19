@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-2.5 relative w-full max-w-[291px] max-sm:grid-cols-1 {{ $attributes["class"] }}">
        <div class="shimmer relative w-full rounded-[4px]">
            <div class="relative after:content-[' '] after:block after:pb-[calc(100%+9px)]"></div>
        </div>

        <div class="grid gap-2.5 content-start">
            <p class="shimmer w-[75%] h-[24px]"></p>
            <p class="shimmer w-[55%] h-[24px]"></p>

            {{-- Needs to implement that in future --}}
            <div class="hidden flex gap-4 mt-[12px]">
                <span class="shimmer w-[30px] h-[30px] block rounded-full"></span>
                <span class="shimmer w-[30px] h-[30px] block rounded-full"></span>
            </div>
        </div>
    </div>
@endfor
