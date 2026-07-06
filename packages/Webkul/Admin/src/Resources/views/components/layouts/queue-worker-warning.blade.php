@if (
    config('queue.default') !== 'sync' && 
    (! cache()->has('queue_worker_heartbeat') || cache('queue_worker_heartbeat') < now()->subMinutes(5)->timestamp)
)
    <div class="flex items-center justify-between p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200" role="alert">
        <div class="flex items-center gap-2">
            <span class="icon-warning text-xl"></span>
            <div>
                <span class="font-medium">Queue Worker Not Running:</span> 
                The application is configured to use the <strong>{{ config('queue.default') }}</strong> queue driver, but no active queue worker has been detected. Background jobs (emails, indexing, imports) may not be processed.
            </div>
        </div>
        <div class="whitespace-nowrap">
            <code class="px-2 py-1 bg-red-100 text-red-900 rounded font-mono text-xs">php artisan queue:work</code>
        </div>
    </div>
@endif