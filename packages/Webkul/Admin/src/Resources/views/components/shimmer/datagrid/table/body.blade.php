@props([
    'isMultiRow'  => false,
    'card'        => null,
    'groups'      => null,
    'imageGroup'  => null,
    'massAction'  => null,
    'template'    => null,
    'columns'     => 6,
    'mobileLines' => null,
    'mobileImage' => false,
    'indent'      => null,
])

@php
    /**
     * Whether the grid this stands in for becomes cards on small screens. When it
     * does the placeholder rows collapse the same way, so the loading state has the
     * shape of what is about to replace it rather than a desktop table the phone can
     * only reach by scrolling sideways.
     */
    $card = $card ?? $isMultiRow;

    /**
     * How many text lines sit in each of the row's column groups, e.g. [3, 5, 3].
     *
     * A grid that groups columns decides its own row shape, so a single fixed
     * skeleton is wrong for all but one of them — it leaves the wrong number of
     * groups and the wrong number of lines, and the content visibly jumps when it
     * arrives. Passing the shape keeps the placeholder the same height as the row
     * it stands in for.
     */
    $groups = $groups ?: [3, 3, 3];

    /**
     * Index of the group that leads with a thumbnail, for grids showing an image.
     */
    $imageGroup = is_null($imageGroup) ? null : (int) $imageGroup;

    /**
     * Whether the row starts with a select-all checkbox.
     */
    $massAction = $massAction ?? $isMultiRow;

    /**
     * The row's own column template, given rather than guessed — see the header.
     */
    $template = $template ?: '2fr'.str_repeat(' 1fr', max(count($groups) - 1, 1));

    /**
     * Whether the groups after the first are indented on mobile so their text lines
     * up under the first group's, clearing the select-all checkbox that sits ahead
     * of it. Grids that stack groups do this with `ps-8 md:ps-0`; it only applies
     * where there is a checkbox to clear, which is why it follows `massAction`.
     */
    $indent = $indent ?? $massAction;
@endphp

@for ($i = 0;  $i < 10; $i++)
    @if (! $isMultiRow)
        {{--
            `minmax(150px, …)` so a wide table overflows and scrolls here exactly as
            it does once the records arrive, rather than squeezing to fit a phone.
        --}}
        <div
            @class([
                'row grid gap-2.5 border-b px-4 py-4 text-gray-600 dark:border-gray-800 dark:text-gray-300',
                'datagrid-card' => $card,
            ])
            style="grid-template-columns: repeat({{ (int) $columns }}, minmax(150px, 1fr));"
        >
            @for ($column = 0; $column < (int) $columns; $column++)
                @if ($massAction && ! $column)
                    <div class="shimmer mb-0.5 h-6 w-6"></div>
                @elseif ($column === (int) $columns - 1)
                    <div class="flex gap-2.5 place-self-end">
                        <div class="shimmer h-6 w-6 p-1.5"></div>

                        <div class="shimmer h-6 w-6 p-1.5"></div>
                    </div>
                @else
                    <div class="shimmer h-[17px] w-full max-w-[100px]"></div>
                @endif
            @endfor
        </div>
    @else
        <div
            @class([
                'row grid gap-2.5 border-b px-4 py-2.5 text-gray-600 dark:border-gray-800 dark:text-gray-300',
                'datagrid-card' => $card,
            ])
            style="grid-template-columns: {{ $template }};"
        >
            @if ($mobileLines)
                {{--
                    A grid with its own mobile markup does not simply stack its desktop
                    columns, so neither can the placeholder: here it is one block — the
                    thumbnail and a single run of lines beside it, actions on the right —
                    mirroring the layout the grid swaps in below md.
                --}}
                <div class="block md:hidden">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex w-full items-start gap-2.5">
                            @if ($massAction)
                                <div class="shimmer mt-0.5 h-6 w-6 shrink-0"></div>
                            @endif

                            @if ($mobileImage)
                                <div class="shimmer h-12 w-12 shrink-0 rounded"></div>
                            @endif

                            <div class="flex w-full flex-col gap-1.5">
                                @for ($line = 0; $line < (int) $mobileLines; $line++)
                                    <div @class([
                                        'shimmer w-full',
                                        'h-[19px] max-w-[200px]' => ! $line,
                                        'h-[17px] max-w-[150px]' => $line,
                                    ])></div>
                                @endfor
                            </div>
                        </div>

                        <div class="flex shrink-0 gap-1.5">
                            <div class="shimmer h-6 w-6"></div>

                            <div class="shimmer h-6 w-6"></div>
                        </div>
                    </div>
                </div>
            @endif

            <div @class(['contents' => ! $mobileLines, 'hidden md:contents' => $mobileLines])>
                @foreach ($groups as $index => $lines)
                    <div @class([
                        'flex gap-2.5',
                        'ps-8 md:ps-0' => $indent && $index,
                    ])>
                        @if ($massAction && ! $index)
                            <div class="shimmer h-6 w-6 shrink-0"></div>
                        @endif

                        @if ($index === $imageGroup)
                            <div class="shimmer h-[60px] w-[60px] shrink-0 rounded"></div>
                        @endif

                        <div class="flex w-full flex-col gap-1.5">
                            @for ($line = 0; $line < $lines; $line++)
                                <div @class([
                                    'shimmer w-full',
                                    'h-[19px] max-w-[250px]' => ! $line,
                                    'h-[17px] max-w-[150px]' => $line,
                                ])></div>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endfor
