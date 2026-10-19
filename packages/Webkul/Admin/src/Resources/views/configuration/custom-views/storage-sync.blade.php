@php
    $driver = \Webkul\Core\Enums\SupportedFilesystemEnum::currentDriver();
@endphp

@if ($driver->supportsSync())
    <v-storage-sync
        status-endpoint="{{ route('admin.configuration.file_management.sync_status') }}"
        endpoint="{{ route('admin.configuration.file_management.sync') }}"
        depend-name="{{ $field->getDependFieldName() }}"
        depend-value="{{ $field->getDependFieldValue() }}"
    ></v-storage-sync>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-storage-sync-template">
            <div
                class="mb-4 flex flex-col gap-2.5"
                v-if="isVisible"
            >
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
                        v-if="! isChecking && ! isUnreachable"
                        class="text-sm"
                        :class="pending ? 'text-orange-600 dark:text-orange-400' : 'text-emerald-600 dark:text-emerald-400'"
                    >
                        @{{ pending ? pendingLabel.replace(':count', pending) : upToDateLabel }}
                    </span>
                </div>

                <p class="text-xs text-gray-600 dark:text-gray-300">
                    @lang('admin::app.configuration.index.file-management.general.settings.sync.info')
                </p>
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

                        pending: 0,

                        pendingLabel: @json(trans('admin::app.configuration.index.file-management.general.settings.sync.pending')),

                        upToDateLabel: @json(trans('admin::app.configuration.index.file-management.general.settings.sync.up-to-date')),
                    };
                },

                mounted() {
                    this.watchDriver();

                    this.check();
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
