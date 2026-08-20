<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.appearance.themes.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.appearance.themes.index.before') !!}

    <!-- Page Header -->
    <div class="grid gap-1.5">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.appearance.themes.index.title')
        </p>

        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
            @lang('admin::app.appearance.themes.index.info')
        </p>
    </div>

    {!! view_render_event('bagisto.admin.appearance.themes.list.before') !!}

    <v-appearance-themes
        :themes='@json($themes)'
        :channels='@json($channels->map(fn ($channel) => ['id' => $channel->id, 'name' => $channel->name])->values())'
    >
        <x-admin::shimmer.image class="mt-8 h-[300px] w-full rounded" />
    </v-appearance-themes>

    {!! view_render_event('bagisto.admin.appearance.themes.list.after') !!}

    {!! view_render_event('bagisto.admin.appearance.themes.index.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-appearance-themes-template"
        >
            <div class="mt-8 flex flex-col gap-8">
                <div
                    v-for="group in groups"
                    :key="group.key"
                >
                    <!-- Group Heading -->
                    <div class="flex items-center gap-2">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @{{ group.label }}
                        </p>

                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-950 dark:text-gray-300">
                            @{{ group.themes.length }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-[repeat(auto-fill,minmax(300px,1fr))] gap-6">
                        <div
                            class="box-shadow flex flex-col overflow-hidden rounded bg-white dark:bg-gray-900"
                            v-for="theme in group.themes"
                            :key="theme.code"
                        >
                            <!-- Screenshot -->
                            <div class="relative flex h-[190px] items-center justify-center bg-gray-100 dark:bg-gray-950">
                                <img
                                    class="h-full w-full object-cover"
                                    :src="theme.screenshot"
                                    :alt="theme.name"
                                    v-if="theme.screenshot && ! failed[theme.code]"
                                    v-on:error="failed[theme.code] = true"
                                />

                                <span
                                    class="icon-image text-5xl text-gray-400"
                                    v-else
                                ></span>

                                <!-- Active Badge -->
                                <span
                                    class="label-active absolute top-3 ltr:left-3 rtl:right-3"
                                    v-if="theme.status === 'active'"
                                >
                                    @lang('admin::app.appearance.themes.index.active')
                                </span>
                            </div>

                            <!-- Body -->
                            <div class="flex flex-1 flex-col gap-2 p-4">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @{{ theme.name }}
                                    </p>

                                    <p
                                        class="flex shrink-0 items-center gap-1 text-xs text-gray-500 dark:text-gray-300"
                                        v-if="theme.rating"
                                    >
                                        <span class="icon-star-fill text-sm text-yellow-500"></span>

                                        @{{ theme.rating }}
                                    </p>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-300">
                                    <template v-if="theme.author">
                                        @lang('admin::app.appearance.themes.index.by') @{{ theme.author }}
                                    </template>

                                    <template v-if="theme.version">
                                        · v@{{ theme.version }}
                                    </template>
                                </p>

                                <p
                                    class="line-clamp-3 text-xs text-gray-600 dark:text-gray-300"
                                    v-if="theme.description"
                                >
                                    @{{ theme.description }}
                                </p>

                                <!-- Channels this theme is live on -->
                                <p
                                    class="mt-1 text-xs font-medium text-green-600"
                                    v-if="isEverywhere(theme)"
                                >
                                    @lang('admin::app.appearance.themes.index.active-on-all')
                                </p>

                                <p
                                    class="mt-1 text-xs font-medium text-green-600"
                                    v-else-if="theme.active_on.length"
                                >
                                    @lang('admin::app.appearance.themes.index.active-on')
                                    @{{ theme.active_on.map(channel => channel.name).join(', ') }}
                                </p>

                                <p
                                    class="mt-1 text-xs text-gray-400"
                                    v-else-if="theme.is_installed"
                                >
                                    @lang('admin::app.appearance.themes.index.not-in-use')
                                </p>

                                <!-- Actions, always on their own row -->
                                <div class="mt-auto flex w-full flex-wrap items-center gap-2 pt-3">
                                    @if (bouncer()->hasPermission('appearance.themes.activate'))
                                        <button
                                            type="button"
                                            class="primary-button"
                                            v-if="theme.is_installed && availableChannels(theme).length"
                                            @click="openActivate(theme)"
                                        >
                                            @lang('admin::app.appearance.themes.index.activate-btn')
                                        </button>
                                    @endif

                                    <a
                                        class="primary-button"
                                        :href="theme.url"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        v-if="! theme.is_installed && theme.url"
                                    >
                                        @lang('admin::app.appearance.themes.index.buy-btn')
                                    </a>

                                    <a
                                        class="secondary-button"
                                        :href="theme.demo_url"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        v-if="! theme.is_installed && theme.demo_url"
                                    >
                                        @lang('admin::app.appearance.themes.index.preview-btn')
                                    </a>

                                    <a
                                        class="secondary-button"
                                        :href="'{{ route('admin.appearance.sections.index', ['code' => '__CODE__']) }}'.replace('__CODE__', theme.code)"
                                        v-if="theme.is_installed"
                                    >
                                        @lang('admin::app.appearance.themes.index.customize-btn')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activate Confirmation -->
                <Teleport to="body">
                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form @submit="handleSubmit($event, activate)">
                            <x-admin::modal ref="activateModal">
                                <x-slot:header>
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                                        @lang('admin::app.appearance.themes.index.activate.title')
                                    </p>
                                </x-slot>

                                <x-slot:content>
                                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.appearance.themes.index.activate.info', ['theme' => '@{{ selected.name }}'])
                                    </p>

                                    <!-- Channels -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.appearance.themes.index.activate.channels')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="multiselect"
                                            name="channel_ids"
                                            class="cursor-pointer"
                                            rules="required"
                                            v-model="channelIds"
                                            :label="trans('admin::app.appearance.themes.index.activate.channels')"
                                            @change="loadImpact"
                                        >
                                            <option
                                                v-for="channel in availableChannels(selected)"
                                                :key="channel.id"
                                                :value="channel.id"
                                                v-text="channel.name"
                                            ></option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="channel_ids" />

                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">
                                            @lang('admin::app.appearance.themes.index.activate.channels-hint')
                                        </p>
                                    </x-admin::form.control-group>

                                    <!--
                                        Customizations are keyed by theme code, so switching a channel
                                        leaves the ones built for the outgoing theme behind.
                                    -->
                                    <div
                                        class="mt-2 grid gap-1 rounded border border-orange-200 bg-orange-50 p-3 text-xs text-orange-800 dark:border-orange-900 dark:bg-orange-950 dark:text-orange-200"
                                        v-if="impact.length"
                                    >
                                        <p
                                            v-for="row in impact"
                                            :key="row.channel_id"
                                        >
                                            @lang('admin::app.appearance.themes.index.activate.warning', [
                                                'channel' => '@{{ row.channel }}',
                                                'count' => '@{{ row.customizations }}',
                                                'theme' => '@{{ row.current_theme }}',
                                            ])
                                        </p>
                                    </div>
                                </x-slot>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('admin::app.appearance.themes.index.activate.confirm-btn')"
                                        ::loading="isLoading"
                                        ::disabled="isLoading"
                                    />
                                </x-slot>
                            </x-admin::modal>
                        </form>
                    </x-admin::form>
                </Teleport>
            </div>
        </script>

        <script type="module">
            app.component('v-appearance-themes', {
                template: '#v-appearance-themes-template',

                props: ['themes', 'channels'],

                data() {
                    return {
                        selected: {},

                        channelIds: [],

                        impact: [],

                        failed: {},

                        isLoading: false,
                    };
                },

                computed: {
                    /**
                     * The themes under the heading each belongs to, so the ones this store
                     * already has are told apart from the ones it would have to buy. A
                     * heading with nothing under it is left out.
                     */
                    groups() {
                        return [
                            {
                                key: 'installed',
                                label: "@lang('admin::app.appearance.themes.index.my-themes')",
                                themes: this.themes.filter(theme => theme.is_installed),
                            }, {
                                key: 'available',
                                label: "@lang('admin::app.appearance.themes.index.buy-themes')",
                                themes: this.themes.filter(theme => ! theme.is_installed),
                            },
                        ].filter(group => group.themes.length);
                    },
                },

                methods: {
                    /**
                     * Channels that are not already running this theme. A theme that is
                     * live everywhere has nothing left to activate.
                     */
                    availableChannels(theme) {
                        if (! theme?.active_on) {
                            return this.channels;
                        }

                        const live = theme.active_on.map(channel => channel.id);

                        return this.channels.filter(channel => ! live.includes(channel.id));
                    },

                    /**
                     * Live on every channel there is, so naming them adds nothing.
                     */
                    isEverywhere(theme) {
                        return theme.active_on.length > 0 && ! this.availableChannels(theme).length;
                    },

                    /**
                     * Open the activation confirmation for a theme, preselecting the only
                     * available channel when there is just one.
                     */
                    openActivate(theme) {
                        this.selected = theme;

                        this.impact = [];

                        const available = this.availableChannels(theme);

                        this.channelIds = available.length === 1 ? [available[0].id] : [];

                        this.$refs.activateModal.open();

                        this.loadImpact();
                    },

                    /**
                     * Fetch what activating would strand, so the confirmation can name it.
                     */
                    loadImpact() {
                        if (! this.channelIds.length) {
                            this.impact = [];

                            return;
                        }

                        this.$axios.get("{{ route('admin.appearance.themes.impact', 'THEME_CODE') }}".replace('THEME_CODE', this.selected.code), {
                                params: { channel_ids: this.channelIds },
                            })
                            .then(response => {
                                this.impact = response.data.impact;
                            })
                            .catch(() => {
                                this.impact = [];
                            });
                    },

                    /**
                     * Apply the theme to every selected channel.
                     */
                    activate() {
                        this.isLoading = true;

                        this.$axios.post("{{ route('admin.appearance.themes.activate', 'THEME_CODE') }}".replace('THEME_CODE', this.selected.code), {
                                channel_ids: this.channelIds,
                            })
                            .then(response => {
                                window.location.href = response.data.redirect_url;
                            })
                            .catch(error => {
                                this.isLoading = false;

                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response?.data?.message ?? error.message,
                                });
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
