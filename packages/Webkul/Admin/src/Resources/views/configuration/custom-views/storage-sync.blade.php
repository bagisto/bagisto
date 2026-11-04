@php
    $driver = \Webkul\Core\Enums\SupportedFilesystemEnum::currentDriver();

    $chosen = \Webkul\Core\Enums\SupportedFilesystemEnum::fromConfig(
        core()->getConfigData(\Webkul\Core\Enums\SupportedFilesystemEnum::CONFIG_KEY)
    );

    $isIncomplete = $chosen !== $driver
        && $chosen->supportsSync();
@endphp

@if (
    $driver->supportsSync()
    || $isIncomplete
)
    <v-storage-sync
        status-endpoint="{{ route('admin.configuration.file_management.sync_status') }}"
        endpoint="{{ route('admin.configuration.file_management.sync') }}"
        depend-name="{{ $field->getDependFieldName() }}"
        depend-value="{{ $field->getDependFieldValue() }}"
    >
    </v-storage-sync>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-storage-sync-template"
        >
            <div
                class="mb-4 flex flex-col gap-2.5"
                v-if="isVisible"
            >
                @if ($isIncomplete)
                    <p class="text-sm text-orange-600 dark:text-orange-400">
                        @lang('admin::app.configuration.index.file-management.general.settings.incomplete', ['driver' => trans($chosen->title())])
                    </p>
                @else
                    <div class="flex flex-wrap items-center gap-2.5">
                        <button
                            type="button"
                            class="secondary-button"
                            :disabled="isSyncing || isUnreachable"
                            @click="sync"
                        >
                            <span v-if="! isSyncing">
                                @lang('admin::app.configuration.index.file-management.general.settings.sync.title')
                            </span>

                            <span v-else>
                                @lang('admin::app.configuration.index.file-management.general.settings.sync.syncing')
                            </span>
                        </button>

                        <span
                            class="text-sm"
                            :class="pending ? 'text-orange-600 dark:text-orange-400' : 'text-emerald-600 dark:text-emerald-400'"
                            v-if="! isChecking && ! isUnreachable"
                        >
                            @{{ pending ? pendingLabel.replace(':count', pending) : upToDateLabel }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-300">
                        @lang('admin::app.configuration.index.file-management.general.settings.sync.info')
                    </p>
                @endif
            </div>
        </script>

        <script type="module">
            app.component('v-storage-sync', {
                template: '#v-storage-sync-template',

                props: ['statusEndpoint', 'endpoint', 'dependName', 'dependValue'],

                data() {
                    return {
                        isChecking: true,

                        isSyncing: false,

                        isUnreachable: false,

                        isVisible: true,

                        isIncomplete: @json($isIncomplete),

                        pending: 0,

                        pendingLabel: @json(trans('admin::app.configuration.index.file-management.general.settings.sync.pending')),

                        upToDateLabel: @json(trans('admin::app.configuration.index.file-management.general.settings.sync.up-to-date')),
                    };
                },

                mounted() {
                    this.watchDriver();

                    if (! this.isIncomplete) {
                        this.check();
                    }
                },

                methods: {
                    /**
                     * Follow the driver on screen, so picking one that cannot be copied onto takes
                     * the button away before the choice is even saved.
                     */
                    watchDriver() {
                        if (! this.dependName) {
                            return;
                        }

                        document.addEventListener('change', (event) => {
                            if (event.target.id !== this.dependName) {
                                return;
                            }

                            this.isVisible = this.dependValue.split(',').includes(event.target.value);
                        });
                    },

                    /**
                     * Count what the configured disk is missing, after the page has rendered so a
                     * large store does not wait on it.
                     */
                    check() {
                        this.$axios.get(this.statusEndpoint)
                            .then((response) => {
                                this.pending = response.data.pending;

                                this.isUnreachable = response.data.unreachable;
                            })
                            .finally(() => this.isChecking = false);
                    },

                    /**
                     * Hand the copy to the queue, then count again once it has been taken.
                     */
                    sync() {
                        if (this.isSyncing) {
                            return;
                        }

                        this.isSyncing = true;

                        this.$axios.post(this.endpoint)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.check();
                            })
                            .catch((error) => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message });
                            })
                            .finally(() => this.isSyncing = false);
                    },
                },
            });
        </script>
    @endPushOnce
@endif
