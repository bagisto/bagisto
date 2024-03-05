@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-2">
        <div class="shimmer w-32 h-6"></div>

        <div class="shimmer w-full rounded-lg mb-8 h-12"></div>
    </div>
@endfor