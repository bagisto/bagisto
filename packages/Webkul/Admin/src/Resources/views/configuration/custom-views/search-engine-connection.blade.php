@php
    $engine = \Webkul\Product\Enums\SearchEngineEnum::from(explode('.', $child->getKey())[1]);

    $verdict = app(\Webkul\Product\Services\Search\SearchEngineAvailability::class)->cached($engine);

    $engineName = trans("admin::app.configuration.index.search-engines.engines.{$engine->value}");
@endphp

<v-search-engine-connection
    initial="{{ json_encode($verdict) }}"
    endpoint="{{ route('admin.configuration.search-engines.test-connection', $engine->value) }}"
></v-search-engine-connection>

@pushOnce('scripts')
    <script type="text/x-template" id="v-search-engine-connection-template">
        <div class="mb-4 flex flex-col gap-2.5">
            <div class="flex flex-wrap items-center gap-2.5">
                <button
                    type="button"
                    class="secondary-button"
                    :disabled="isTesting"
                    @click="testConnection"
                >
                    <span v-if="! isTesting">
                        @lang('admin::app.configuration.index.search-engines.test-connection.title')
                    </span>

                    <span v-else>
                        @lang('admin::app.configuration.index.search-engines.test-connection.testing')
                    </span>
                </button>

                <span
                    v-if="verdict"
                    class="flex items-center gap-1.5 text-sm"
                    :class="isAvailable ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'"
                >
                    <span
                        class="inline-block h-1.5 w-1.5 shrink-0 rounded-full"
                        :class="isAvailable ? 'bg-emerald-500 dark:bg-emerald-400' : 'bg-red-500 dark:bg-red-400'"
                    ></span>

                    @{{ statuses[verdict.status] }}
                </span>
            </div>

            <p
                v-if="verdict && verdict.host"
                class="text-xs text-gray-600 dark:text-gray-300"
            >
                @{{ verdict.host }}<template v-if="verdict.version"> &middot; @{{ verdict.version }}</template><template v-if="verdict.cluster"> &middot; @{{ verdict.cluster }}</template>
            </p>
        </div>
    </script>

    <script type="module">
        app.component('v-search-engine-connection', {
            template: '#v-search-engine-connection-template',

            props: ['initial', 'endpoint'],

            data() {
                return {
                    isTesting: false,

                    verdict: JSON.parse(this.initial),

                    statuses: {
                        available: @json(trans('admin::app.configuration.index.search-engines.test-connection.statuses.available', ['engine' => $engineName])),
                        unreachable: @json(trans('admin::app.configuration.index.search-engines.test-connection.statuses.unreachable', ['engine' => $engineName])),
                        unauthorized: @json(trans('admin::app.configuration.index.search-engines.test-connection.statuses.unauthorized', ['engine' => $engineName])),
                        incompatible: @json(trans('admin::app.configuration.index.search-engines.test-connection.statuses.incompatible', ['engine' => $engineName])),
                        misconfigured: @json(trans('admin::app.configuration.index.search-engines.test-connection.statuses.misconfigured', ['engine' => $engineName])),
                    },
                };
            },

            computed: {
                isAvailable() {
                    return this.verdict?.status === 'available';
                },
            },

            methods: {
                testConnection() {
                    if (this.isTesting) {
                        return;
                    }

                    this.isTesting = true;

                    this.$axios.post(this.endpoint)
                        .then((response) => this.settle(response.data, 'success'))
                        .catch((error) => this.settle(error.response?.data, 'error'));
                },

                settle(data, type) {
                    this.isTesting = false;

                    if (! data) {
                        return;
                    }

                    this.verdict = data;

                    this.$emitter.emit('add-flash', {
                        type: type,
                        message: data.message,
                    });
                },
            },
        });
    </script>
@endPushOnce
