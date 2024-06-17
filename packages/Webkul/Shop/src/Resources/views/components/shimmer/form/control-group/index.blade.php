@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-2">
        <div class="shimmer h-6 w-32 max-md:h-5"></div>

        <div class="shimmer mb-8 h-12 w-full rounded-lg max-md:-mt-1 max-md:h-10"></div>
    </div>
@endfor