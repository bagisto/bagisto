@props([
    'columns'    => 6,
    'isMultiRow' => false,
])

@for ($i = 0;  $i < 10; $i++)
    @if (! $isMultiRow)
        <div
            class="row grid gap-2.5 border-b border-gray-300 px-4 py-4 text-gray-600"
            style="grid-template-columns: repeat({{ (int) $columns }}, minmax(0, 1fr))"
        >
            @for ($j = 0;  $j < $columns; $j++)
                <div @class([
                    'shimmer h-6 w-[100px]',
                    'place-self-end' => $j === $columns - 1,
                ])></div>
            @endfor
        </div>
    @else
        <div class="row grid grid-cols-[2fr_1fr_1fr] gap-2.5 border-b border-gray-300 px-4 py-2.5 text-gray-600">
            <div class="flex gap-2.5">
                <div class="shimmer h-6 w-6"></div>

                <div class="flex flex-col gap-1.5">
                    <div class="shimmer h-6 w-[250px]"></div>

                    <div class="shimmer h-6 w-[150px]"></div>

                    <div class="shimmer h-6 w-[150px]"></div>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <div class="shimmer h-[19px] w-[250px]"></div>

                <div class="shimmer h-6 w-[150px]"></div>

                <div class="shimmer h-6 w-[150px]"></div>
            </div>

            <div class="flex flex-col gap-1.5">
                <div class="shimmer h-[19px] w-[250px]"></div>

                <div class="shimmer h-6 w-[150px]"></div>

                <div class="shimmer h-6 w-[150px]"></div>
            </div>
        </div>
    @endif
@endfor
