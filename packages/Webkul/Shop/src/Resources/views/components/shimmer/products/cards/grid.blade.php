@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-2.5 relative w-full max-w-72.75 max-sm:grid-cols-1 {{ $attributes["class"] }}">
        <div class="shimmer relative w-full rounded-sm max-sm:rounded-lg!">
            <div class="after:content-[' '] relative after:block after:pb-[calc(100%+9px)]"></div>
        </div>

        <div class="grid content-start gap-2.5 max-sm:gap-1">
            <p class="shimmer h-4 w-3/4"></p>
            <p class="shimmer h-4 w-[55%]"></p>
        </div>
    </div>
@endfor
