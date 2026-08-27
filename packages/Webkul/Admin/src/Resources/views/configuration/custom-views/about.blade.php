@php
    $columns = app(\Webkul\Admin\Helpers\SystemInformation::class)->columns();

    /**
     * A flag in words rather than as a bare one or zero.
     */
    $flag = fn (bool $value) => trans('admin::app.components.datagrid.filters.boolean-options.'.($value ? 'true' : 'false'));

    /**
     * How a resolved value reads. Flags are stored as booleans and a few facts as lists, so both
     * are given words, and a driver of `null` is read as switched off rather than as missing.
     */
    $readable = function ($value) use ($flag) {
        if (is_bool($value)) {
            return $flag($value);
        }

        if (is_array($value)) {
            return implode(', ', array_map(fn ($entry) => is_bool($entry) ? $flag($entry) : (string) $entry, $value));
        }

        if (
            $value === null
            || $value === ''
            || $value === 'null'
        ) {
            return trans('admin::app.configuration.index.about.general.values.not-configured');
        }

        return (string) $value;
    };

    /**
     * Whether a value reads as a service that is working, so it is seen at a glance.
     */
    $healthy = fn ($value) => is_string($value)
        && in_array(strtolower($value), ['available', 'connected', 'true']);

    /**
     * Whether a value reads as a service that is not.
     */
    $failing = fn ($value) => is_string($value)
        && in_array(strtolower($value), ['unreachable', 'unauthorized', 'incompatible', 'misconfigured', 'not available']);
@endphp

{{--
    Each column stacks its own cards rather than sharing rows with the other, so a short section
    does not hold open the row of a tall one.
--}}
<div class="grid grid-cols-2 items-start gap-4 max-lg:grid-cols-1">
    @foreach ($columns as $column)
        <div class="flex flex-col gap-4">
            @foreach ($column as $card)
                <div class="rounded-sm border dark:border-gray-800">
                    <div class="flex items-center gap-2 border-b px-4 py-3 dark:border-gray-800">
                        <span class="{{ $card['icon'] }} text-2xl text-gray-600 dark:text-gray-300"></span>

                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $card['heading'] }}
                        </p>
                    </div>

                    <div class="grid px-4 py-1">
                        @foreach ($card['entries'] as $label => $value)
                            <div class="flex items-baseline justify-between gap-4 border-b py-2.5 last:border-b-0 dark:border-gray-800">
                                <p class="shrink-0 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $label }}
                                </p>

                                <p class="flex items-center gap-1.5 wrap-anywhere text-sm font-semibold text-gray-800 ltr:text-right rtl:text-left dark:text-white">
                                    @if ($healthy($value) || $failing($value))
                                        <span @class([
                                            'h-1.5 w-1.5 shrink-0 rounded-full',
                                            'bg-emerald-500' => $healthy($value),
                                            'bg-red-500' => $failing($value),
                                        ])></span>
                                    @endif

                                    {{ $readable($value) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
