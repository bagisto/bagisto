@php
    $engine = \Webkul\Product\Enums\SearchEngineEnum::from(explode('.', $child->getKey())[1]);

    $verdict = app(\Webkul\Product\Services\Search\SearchEngineAvailability::class)->cached($engine);

    $engineName = trans("admin::app.configuration.index.search-engines.engines.{$engine->value}");

    /**
     * How this section's fields are named on the form, so the values on screen can be read
     * back out of it: `search_engines[elastic][settings][`.
     */
    $prefix = collect(explode('.', $child->getKey()))
        ->map(fn ($part, $index) => $index === 0 ? $part : "[{$part}]")
        ->implode('').'[';
@endphp

<v-search-engine-connection
    initial="{{ json_encode($verdict) }}"
    endpoint="{{ route('admin.configuration.search-engines.test-connection', $engine->value) }}"
    prefix="{{ $prefix }}"
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

            props: ['initial', 'endpoint', 'prefix'],

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
                /**
                 * Whether the last verdict says the engine may be used.
                 */
                isAvailable() {
                    return this.verdict?.status === 'available';
                },
            },

            methods: {
                /**
                 * Ask the engine where it stands, through the settings as they are on screen.
                 */
                testConnection() {
                    if (this.isTesting) {
                        return;
                    }

                    this.isTesting = true;

                    this.$axios.post(this.endpoint, { settings: this.settings() })
                        .then((response) => this.settle(response.data, 'success'))
                        .catch((error) => this.settle(error.response?.data, 'error'));
                },

                /**
                 * The section's settings as the form holds them right now.
                 * A field the chosen authentication hides is not rendered, so it is not read.
                 */
                settings() {
                    const form = this.$el.closest('form');

                    if (! form) {
                        return {};
                    }

                    const settings = {};

                    new FormData(form).forEach((value, name) => {
                        if (
                            ! name.startsWith(this.prefix)
                            || ! name.endsWith(']')
                        ) {
                            return;
                        }

                        settings[name.slice(this.prefix.length, -1)] = value;
                    });

                    return settings;
                },

                /**
                 * Show the verdict the engine answered with and stop the button spinning.
                 */
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
