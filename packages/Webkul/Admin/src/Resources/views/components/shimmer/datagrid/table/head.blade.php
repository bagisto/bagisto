@props([
    'isMultiRow' => false,
    'card'       => null,
    'groups'     => null,
    'massAction' => null,
    'template'   => null,
    'columns'    => 6,
])

@php
    /**
     * Whether the grid this stands in for becomes cards on small screens, where its
     * header is dropped — so the placeholder has to be dropped with it, or the screen
     * shows a header while loading and loses it the moment records arrive.
     *
     * Defaults to `isMultiRow`, since a grid that groups columns is exactly the kind
     * that cards, but it can be set on its own for one that cards without grouping.
     */
    $card = $card ?? $isMultiRow;

    /**
     * The row's column groups. Only the count matters here — one label per group —
     * but it is the same shape the body is given, so both are described in one place
     * at the call site.
     */
    $groupCount = count($groups ?: [1, 2, 3]);

    $massAction = $massAction ?? $isMultiRow;

    /**
     * The row's own column template. Given rather than guessed, because a guess is
     * how the placeholder ends up with columns the grid does not have — the widths
     * differ per grid and only the grid knows them.
     */
    $template = $template ?: '2fr'.str_repeat(' 1fr', max($groupCount - 1, 1));
@endphp

@if (! $isMultiRow)
    {{--
        `minmax(150px, …)` rather than a plain fraction, so a table with more columns
        than the screen can hold overflows and scrolls exactly as the real one does,
        instead of squeezing itself to fit a phone the real table never fits.
    --}}
    <div
        @class([
            'row datagrid-head grid',
            'datagrid-head-cards' => $card,
        ])
        style="grid-template-columns: repeat({{ (int) $columns }}, minmax(150px, 1fr));"
    >
        @for ($column = 0; $column < (int) $columns; $column++)
            @if ($massAction && ! $column)
                <div class="shimmer h-[26px] w-6"></div>
            @else
                <div @class([
                    'shimmer h-[17px] w-full max-w-[100px]',
                    'place-self-end' => $column === (int) $columns - 1,
                ])></div>
            @endif
        @endfor
    </div>
@else
    <div
        @class([
            'row datagrid-head grid',
            'datagrid-head-cards' => $card,
        ])
        style="grid-template-columns: {{ $template }};"
    >
        @for ($group = 0; $group < $groupCount; $group++)
            <div class="flex items-center gap-2.5">
                @if ($massAction && ! $group)
                    <div class="shimmer h-6 w-6 shrink-0"></div>
                @endif

                <div class="shimmer h-[17px] w-full max-w-[200px]"></div>
            </div>
        @endfor
    </div>
@endif
