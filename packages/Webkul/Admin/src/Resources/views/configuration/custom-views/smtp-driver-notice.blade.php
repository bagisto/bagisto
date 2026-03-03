@if (config('mail.default') !== 'bagisto-dynamic-smtp')
    <div class="mb-4 flex gap-3 rounded border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-950">
        <span class="icon-warning mt-0.5 text-xl text-amber-600 dark:text-amber-400"></span>

        <div>
            <p class="font-semibold text-amber-800 dark:text-amber-400">
                @lang('admin::app.configuration.index.email.smtp.driver-mismatch-title')
            </p>

            <p class="mt-1 text-sm text-amber-700 dark:text-amber-500">
                @lang('admin::app.configuration.index.email.smtp.driver-mismatch-info', [
                    'driver' => config('mail.default') ?? 'not set',
                ])
            </p>
        </div>
    </div>
@endif
