@php
    $clearActions = [
        'clear-all'      => trans('admin::app.configuration.index.cache-management.actions.clear-all'),
        'clear-config'   => trans('admin::app.configuration.index.cache-management.actions.clear-config'),
        'clear-cache'    => trans('admin::app.configuration.index.cache-management.actions.clear-cache'),
        'clear-compiled' => trans('admin::app.configuration.index.cache-management.actions.clear-compiled'),
        'clear-events'   => trans('admin::app.configuration.index.cache-management.actions.clear-events'),
        'clear-routes'   => trans('admin::app.configuration.index.cache-management.actions.clear-routes'),
        'clear-views'    => trans('admin::app.configuration.index.cache-management.actions.clear-views'),
    ];

    $buildActions = [
        'build-all'    => trans('admin::app.configuration.index.cache-management.actions.build-all'),
        'build-config' => trans('admin::app.configuration.index.cache-management.actions.build-config'),
        'build-routes' => trans('admin::app.configuration.index.cache-management.actions.build-routes'),
        'build-views'  => trans('admin::app.configuration.index.cache-management.actions.build-views'),
    ];
@endphp

<v-cache-management></v-cache-management>

@pushOnce('scripts')
    <script type="text/x-template" id="v-cache-management-template">
        <div>
            <!-- Edge Cases Warning -->
            <div class="mb-4 rounded border border-amber-200 bg-amber-50 p-3 dark:border-amber-800 dark:bg-amber-900/20">
                <p class="mb-1 text-sm font-semibold text-amber-800 dark:text-amber-300">
                    @lang('admin::app.configuration.index.cache-management.warning-title')
                </p>

                <ul class="list-disc pl-5 text-xs text-amber-700 dark:text-amber-400">
                    <li>@lang('admin::app.configuration.index.cache-management.warning-route-closures')</li>
                    <li>@lang('admin::app.configuration.index.cache-management.warning-config-closures')</li>
                    <li>@lang('admin::app.configuration.index.cache-management.warning-permissions')</li>
                </ul>
            </div>

            <!-- Clear Cache Section -->
            <div class="mb-4">
                <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.configuration.index.cache-management.clear-cache-title')
                </p>

                <p class="mb-3 text-xs text-gray-600 dark:text-gray-300">
                    @lang('admin::app.configuration.index.cache-management.clear-cache-info')
                </p>

                <div class="flex flex-wrap gap-2.5">
                    @foreach ($clearActions as $action => $label)
                        <button
                            type="button"
                            class="cursor-pointer rounded-md border border-red-300 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition-all hover:bg-red-600 hover:text-white disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-700 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white"
                            :disabled="runningAction !== null"
                            @click="executeAction('{{ $action }}', '{{ addslashes($label) }}')"
                        >
                            <span
                                class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-current border-t-transparent align-middle"
                                v-if="runningAction === '{{ $action }}'"
                            ></span>

                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Build Cache Section -->
            <div class="mb-4">
                <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.configuration.index.cache-management.build-cache-title')
                </p>

                <p class="mb-3 text-xs text-gray-600 dark:text-gray-300">
                    @lang('admin::app.configuration.index.cache-management.build-cache-info')
                </p>

                <div class="flex flex-wrap gap-2.5">
                    @foreach ($buildActions as $action => $label)
                        <button
                            type="button"
                            class="cursor-pointer rounded-md border border-green-300 bg-white px-3 py-1.5 text-xs font-semibold text-green-600 transition-all hover:bg-green-600 hover:text-white disabled:cursor-not-allowed disabled:opacity-50 dark:border-green-700 dark:bg-gray-900 dark:text-green-400 dark:hover:bg-green-600 dark:hover:text-white"
                            :disabled="runningAction !== null"
                            @click="executeAction('{{ $action }}', '{{ addslashes($label) }}')"
                        >
                            <span
                                class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-current border-t-transparent align-middle"
                                v-if="runningAction === '{{ $action }}'"
                            ></span>

                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Output Console -->
            <div class="overflow-hidden rounded border dark:border-gray-800">
                <div class="flex items-center justify-between border-b bg-gray-50 px-3 py-2 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.configuration.index.cache-management.console-title')
                    </p>

                    <div class="flex items-center gap-3">
                        <span class="text-[11px] text-gray-500 dark:text-gray-400">
                            @{{ logs.length }} @lang('admin::app.configuration.index.cache-management.console-entries')
                        </span>

                        <button
                            type="button"
                            class="text-[11px] text-gray-500 underline hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            @click="clearConsole"
                            v-if="logs.length"
                        >
                            @lang('admin::app.configuration.index.cache-management.clear-console')
                        </button>
                    </div>
                </div>

                <div
                    class="max-h-[400px] min-h-[120px] overflow-y-auto bg-gray-950 p-3"
                    ref="console"
                >
                    <!-- Empty State -->
                    <div
                        class="flex h-[90px] items-center justify-center font-mono text-xs text-gray-600"
                        v-if="! logs.length"
                    >
                        @lang('admin::app.configuration.index.cache-management.console-empty')
                    </div>

                    <!-- Log Entries -->
                    <div
                        v-for="(log, index) in logs"
                        :key="index"
                        class="mb-2"
                    >
                        <!-- Command Line -->
                        <div class="flex items-center gap-2 font-mono text-xs">
                            <span class="select-none text-gray-600">@{{ log.time }}</span>
                            <span class="select-none text-green-400">&#10095;</span>
                            <span class="text-white">php artisan @{{ log.command }}</span>

                            <!-- Running Spinner -->
                            <span
                                class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-yellow-400 border-t-transparent"
                                v-if="log.status === 'running'"
                            ></span>
                        </div>

                        <!-- Raw Output Block -->
                        <pre
                            v-if="log.output"
                            class="mt-1 font-mono text-xs leading-5"
                            :class="{
                                'text-gray-300': log.status === 'success',
                                'text-red-300': log.status === 'error',
                            }"
                        >@{{ log.output }}</pre>

                        <!-- Status line -->
                        <div
                            v-if="log.status === 'success' || log.status === 'error'"
                            class="mt-1 font-mono text-xs"
                        >
                            <span
                                v-if="log.status === 'success'"
                                class="text-green-400"
                            >&#10003; @{{ log.message }}</span>

                            <span
                                v-if="log.status === 'error'"
                                class="text-red-400"
                            >&#10007; @{{ log.message }}</span>
                        </div>

                        <!-- Separator -->
                        <div
                            v-if="index < logs.length - 1"
                            class="my-2 border-t border-gray-800"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-cache-management', {
            template: '#v-cache-management-template',

            data() {
                return {
                    runningAction: null,

                    logs: [],
                };
            },

            methods: {
                executeAction(action, label) {
                    if (this.runningAction) {
                        return;
                    }

                    this.runningAction = action;

                    let logEntry = {
                        status: 'running',
                        command: action,
                        message: '',
                        output: '',
                        time: new Date().toLocaleTimeString('en-GB', { hour12: false }),
                    };

                    this.logs.push(logEntry);

                    this.scrollToBottom();

                    this.$axios.post("{{ route('admin.configuration.cache-management.execute') }}", {
                            action: action,
                        })
                        .then((response) => {
                            let data = response.data;

                            logEntry.status = 'success';
                            logEntry.command = data.command || action;
                            logEntry.message = data.message;
                            logEntry.output = data.output || '';

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: data.message,
                            });
                        })
                        .catch((error) => {
                            let data = error.response?.data || {};

                            logEntry.status = 'error';
                            logEntry.command = data.command || action;
                            logEntry.message = data.message || '@lang("admin::app.configuration.index.cache-management.console-unknown-error")';
                            logEntry.output = data.output || '';

                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: logEntry.message,
                            });
                        })
                        .finally(() => {
                            this.runningAction = null;

                            this.scrollToBottom();
                        });
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        if (this.$refs.console) {
                            this.$refs.console.scrollTop = this.$refs.console.scrollHeight;
                        }
                    });
                },

                clearConsole() {
                    this.logs = [];
                },
            },
        });
    </script>
@endpushOnce
