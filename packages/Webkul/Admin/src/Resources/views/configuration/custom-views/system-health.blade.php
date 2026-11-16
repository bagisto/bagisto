@php
    $translation = 'admin::app.configuration.index.about.system-health';

    $systemHealth = app(\Webkul\Admin\SystemHealth\SystemHealth::class);

    $report = collect($systemHealth->report());

    $counts = $systemHealth->tally($report->all());

    $attention = $counts['failing'] + $counts['unconfirmed'];

    $sections = array_filter([
        'failing' => $report->where('state', 'failing')->merge($report->where('state', 'unconfirmed')),
        'healthy' => $report->where('state', 'healthy'),
        'idle' => $report->where('state', 'idle'),
    ], fn ($checks) => $checks->isNotEmpty());

    $tones = [
        'failing' => [
            'icon' => 'icon-cancel',
            'text' => 'text-red-600 dark:text-red-400',
            'dot' => 'bg-red-500',
            'tile' => 'bg-red-50 text-red-600 dark:bg-red-950 dark:text-red-400',
            'card' => 'border-red-200 dark:border-red-900',
        ],
        'unconfirmed' => [
            'icon' => 'icon-information',
            'text' => 'text-amber-600 dark:text-amber-400',
            'dot' => 'bg-amber-500',
            'tile' => 'bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400',
            'card' => 'border-amber-200 dark:border-amber-900',
        ],
        'healthy' => [
            'icon' => 'icon-done',
            'text' => 'text-emerald-600 dark:text-emerald-400',
            'dot' => 'bg-emerald-500',
            'tile' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400',
            'card' => 'border-gray-200 dark:border-gray-800',
        ],
        'idle' => [
            'icon' => 'icon-pause',
            'text' => 'text-gray-500 dark:text-gray-400',
            'dot' => 'bg-gray-400',
            'tile' => 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500',
            'card' => 'border-gray-200 dark:border-gray-800',
        ],
    ];
@endphp

<div class="grid gap-6">
    <!-- Summary -->
    <div class="flex items-center gap-6 rounded-xl border border-gray-200 p-5 dark:border-gray-800 max-md:flex-col max-md:items-start">
        <!-- Score -->
        <x-admin::system-health.ring
            class="h-20 w-20 text-base font-bold text-gray-800 dark:text-white"
            :score="$systemHealth->score($counts)"
            :state="$systemHealth->overall($counts)"
        >
            {{ $counts['healthy'] }}/{{ $counts['failing'] + $counts['unconfirmed'] + $counts['healthy'] }}
        </x-admin::system-health.ring>

        <!-- Headline -->
        <div class="grid min-w-0 flex-1 gap-1">
            <p class="text-lg font-bold text-gray-800 dark:text-white">
                {{ $attention
                    ? trans($translation.'.summary-attention', ['count' => $attention, 'total' => $report->count()])
                    : trans($translation.'.summary-healthy') }}
            </p>

            <p class="text-sm text-gray-600 dark:text-gray-300">
                @lang($translation.'.info')
            </p>
        </div>

        <!-- Totals -->
        <div class="flex shrink-0 gap-2.5 max-md:w-full max-md:flex-wrap">
            @foreach (['healthy', 'failing', 'unconfirmed', 'idle'] as $state)
                <div class="grid min-w-24 gap-1 rounded-lg bg-gray-50 px-3.5 py-2.5 dark:bg-gray-950">
                    <p class="text-xl font-bold leading-none text-gray-800 dark:text-white">
                        {{ $counts[$state] }}
                    </p>

                    <p class="flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-300">
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $tones[$state]['dot'] }}"></span>

                        @lang($translation.'.states.'.$state)
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Checks -->
    @foreach ($sections as $section => $checks)
        <div class="grid gap-3">
            <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                @lang($translation.'.states.'.$section)

                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    {{ $checks->count() }}
                </span>
            </p>

            <div class="grid grid-cols-2 gap-3 max-lg:grid-cols-1">
                @foreach ($checks as $check)
                    <div class="flex items-start gap-3.5 rounded-xl border bg-white p-4 dark:bg-gray-900 {{ $tones[$check['state']]['card'] }}">
                        <!-- State Icon -->
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xl {{ $tones[$check['state']]['tile'] }} {{ $tones[$check['state']]['icon'] }}"></span>

                        <div class="grid min-w-0 flex-1 gap-1">
                            <div class="flex items-start justify-between gap-2.5">
                                <!-- What It Is -->
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                    @lang($translation.'.checks.'.$check['name'].'.title')
                                </p>

                                <!-- State -->
                                <p class="flex shrink-0 items-center gap-1.5 text-xs font-semibold {{ $tones[$check['state']]['text'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $tones[$check['state']]['dot'] }}"></span>

                                    @lang($translation.'.states.'.$check['state'])
                                </p>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                @lang($translation.'.checks.'.$check['name'].'.info')
                            </p>

                            <!-- What To Do -->
                            @if ($check['message'])
                                <div class="mt-2 grid gap-2 rounded-lg bg-red-50 px-3 py-2.5 dark:bg-red-950">
                                    <p class="wrap-anywhere text-sm font-medium text-red-700 dark:text-red-300">
                                        {{ $check['message'] }}
                                    </p>

                                    @if ($check['url'])
                                        <a
                                            class="flex w-max items-center gap-1 text-sm font-semibold text-blue-600 hover:underline"
                                            href="{{ $check['url'] }}"
                                        >
                                            @lang($translation.'.open-settings')

                                            <span class="icon-sort-right rtl:icon-sort-left text-lg"></span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
