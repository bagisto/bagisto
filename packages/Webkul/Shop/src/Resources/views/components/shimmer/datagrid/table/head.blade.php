@props([
    'columns'    => 6,
    'isMultiRow' => false,
])

@if (! $isMultiRow)
    <div
        class="row grid items-center gap-2.5 border-b border-zinc-200 bg-gray-50 px-6 py-4"
        style="grid-template-columns: repeat({{ (int) $columns }}, minmax(0, 1fr))"
    >
        @for ($i = 0;  $i < $columns; $i++)
            <div @class([
                'shimmer h-5.25 w-25',
                'place-self-end' => $i === $columns - 1,
            ])></div>
        @endfor
    </div>
@else
    <div class="row tems-center grid grid-cols-[2fr_1fr_1fr] items-center gap-2.5 border-b border-zinc-200 px-6 py-4">
        <!-- Mass Actions -->
        <div class="flex items-center gap-2.5">
            <div class="shimmer h-6 w-6"></div>

            <div class="shimmer h-5.25 w-50"></div>
        </div>

        <div class="shimmer h-5.25 w-50"></div>

        <div class="shimmer h-5.25 w-50"></div>
    </div>
@endif