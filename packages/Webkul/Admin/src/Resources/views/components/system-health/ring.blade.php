@props([
    'score' => null,
    'state' => 'healthy',
])

@php
    $stroke = [
        'failing' => 'stroke-red-500',
        'unconfirmed' => 'stroke-amber-500',
        'healthy' => 'stroke-emerald-500',
    ][$state] ?? 'stroke-gray-400';
@endphp

<span {{ $attributes->merge(['class' => 'relative inline-flex shrink-0 items-center justify-center']) }}>
    <svg
        class="absolute inset-0 h-full w-full -rotate-90"
        viewBox="0 0 36 36"
        aria-hidden="true"
    >
        <circle
            class="fill-none stroke-gray-200 dark:stroke-gray-800"
            cx="18"
            cy="18"
            r="15.5"
            stroke-width="3.5"
        >
        </circle>

        @if (! is_null($score))
            <circle
                class="fill-none {{ $stroke }}"
                cx="18"
                cy="18"
                r="15.5"
                stroke-width="3.5"
                stroke-linecap="round"
                pathLength="100"
                stroke-dasharray="{{ $score }} 100"
            >
            </circle>
        @endif
    </svg>

    <span class="relative leading-none">
        {{ $slot }}
    </span>
</span>
