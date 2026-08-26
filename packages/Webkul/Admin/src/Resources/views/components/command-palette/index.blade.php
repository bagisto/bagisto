<v-command-palette
    endpoint="{{ route('admin.command_palette.index') }}"
></v-command-palette>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-command-palette-template"
    >
        <div
            v-if="isOpen"
            class="fixed inset-0 z-10002 flex items-start justify-center bg-gray-900/40 p-4 pt-[10vh] backdrop-blur-[2px] dark:bg-gray-950/60"
            @click.self="close"
        >
            <div class="flex max-h-[70vh] w-full max-w-2xl flex-col overflow-hidden rounded-xl border bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900">
                <!-- Query -->
                <div class="flex shrink-0 items-center gap-2.5 border-b px-4 dark:border-gray-800">
                    <i class="icon-search shrink-0 text-2xl text-gray-500 dark:text-gray-400"></i>

                    <input
                        ref="query"
                        v-model="query"
                        type="text"
                        class="w-full bg-transparent py-3.5 text-base text-gray-800 outline-none placeholder:text-gray-400 dark:text-white dark:placeholder:text-gray-500"
                        :placeholder="placeholder"
                        autocomplete="off"
                        spellcheck="false"
                        @keydown.down.prevent="move(1)"
                        @keydown.up.prevent="move(-1)"
                        @keydown.enter.prevent="open(flattened[cursor])"
                        @keydown.esc.prevent="close"
                    />

                    <span
                        class="shrink-0 rounded border px-1.5 py-0.5 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400"
                        @click="close"
                    >
                        @lang('admin::app.command-palette.esc')
                    </span>
                </div>

                <!-- Results -->
                <div
                    ref="results"
                    class="grow overflow-y-auto overscroll-contain px-2 py-2"
                >
                    <template v-if="flattened.length">
                        <div
                            v-for="group in grouped"
                            :key="group.category"
                        >
                            <p class="px-2 pb-1 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                                @{{ group.label || groupLabel(group.category) }}
                            </p>

                            <a
                                v-for="item in group.items"
                                :key="item.index"
                                :href="item.url"
                                :ref="'row-' + item.index"
                                class="flex cursor-pointer items-center gap-2.5 rounded-md px-2 py-2 no-underline"
                                :class="item.index === cursor
                                    ? 'bg-violet-50 dark:bg-gray-800'
                                    : 'hover:bg-gray-100 dark:hover:bg-gray-950'"
                                @mousemove="cursor = item.index"
                                @click.prevent="open(item)"
                            >
                                <i
                                    class="shrink-0 text-xl text-gray-500 dark:text-gray-400"
                                    :class="item.icon || 'icon-dot'"
                                ></i>

                                <span class="min-w-0 grow">
                                    <span class="block truncate text-sm font-medium text-gray-800 dark:text-white">
                                        @{{ item.label }}
                                    </span>

                                    <span
                                        v-if="item.path"
                                        class="block truncate text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        @{{ item.path }}
                                    </span>
                                </span>

                                <kbd
                                    v-if="item.index === cursor"
                                    class="flex h-5 shrink-0 items-center rounded border px-1.5 font-sans text-xs leading-none text-gray-500 dark:border-gray-700 dark:text-gray-400"
                                >&crarr;</kbd>
                            </a>
                        </div>
                    </template>

                    <div
                        v-else-if="isLoading || pending"
                        class="px-3 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                    >
                        @lang('admin::app.command-palette.loading')
                    </div>

                    <div
                        v-else
                        class="px-3 py-10 text-center"
                    >
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                            @lang('admin::app.command-palette.no-results')
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            @lang('admin::app.command-palette.no-results-hint')
                        </p>
                    </div>
                </div>

                <!-- Hints -->
                <div class="flex shrink-0 flex-wrap items-center gap-x-4 gap-y-1 border-t px-4 py-2 text-xs text-gray-500 dark:border-gray-800 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <kbd class="inline-flex h-4 items-center rounded border px-1 font-sans leading-none dark:border-gray-700">&uarr;</kbd>
                        <kbd class="inline-flex h-4 items-center rounded border px-1 font-sans leading-none dark:border-gray-700">&darr;</kbd>
                        @lang('admin::app.command-palette.navigate')
                    </span>

                    <span class="flex items-center gap-1">
                        <kbd class="inline-flex h-4 items-center rounded border px-1 font-sans leading-none dark:border-gray-700">&crarr;</kbd>
                        @lang('admin::app.command-palette.select')
                    </span>

                    <span class="flex items-center gap-1">
                        <kbd class="inline-flex h-4 items-center rounded border px-1 font-sans leading-none dark:border-gray-700">esc</kbd>
                        @lang('admin::app.command-palette.close')
                    </span>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-command-palette', {
            template: '#v-command-palette-template',

            props: ['endpoint'],

            data() {
                return {
                    isOpen: false,

                    isLoading: false,

                    query: '',

                    cursor: 0,

                    items: null,

                    sources: [],

                    records: {},

                    searchId: 0,

                    pending: 0,

                    placeholder: "@lang('admin::app.command-palette.placeholder')",

                    categories: {
                        pages: "@lang('admin::app.command-palette.categories.pages')",
                        configuration: "@lang('admin::app.command-palette.categories.configuration')",
                        actions: "@lang('admin::app.command-palette.categories.actions')",
                    },

                    quickAccess: "@lang('admin::app.command-palette.categories.quick-access')",

                    order: ['pages', 'actions', 'configuration'],

                    weights: { pages: 0, actions: 1, configuration: 2 },

                    limit: 40,
                };
            },

            computed: {
                /**
                 * The results for the current query, best first.
                 */
                matches() {
                    if (! this.items) {
                        return [];
                    }

                    const query = this.query.trim().toLowerCase();

                    if (! query) {
                        return this.items.filter((item) => item.quick).slice(0, this.limit);
                    }

                    return this.items
                        .map((item) => ({ item, score: this.score(item, query) }))
                        .filter((scored) => scored.score !== null)
                        .sort((a, b) => a.score - b.score
                            || this.depth(a.item) - this.depth(b.item)
                            || a.item.label.length - b.item.label.length)
                        .slice(0, this.limit)
                        .map((scored) => scored.item);
                },

                /**
                 * The results in their groups, each row carrying its position in the whole list.
                 */
                grouped() {
                    const groups = [];

                    let index = 0;

                    this.order.forEach((category) => {
                        const items = this.matches.filter((item) => item.category === category);

                        if (! items.length) {
                            return;
                        }

                        groups.push({
                            category,
                            items: items.map((item) => ({ ...item, index: index++ })),
                        });
                    });

                    this.sources.forEach((source) => {
                        const found = this.records[source.key] || [];

                        if (! found.length) {
                            return;
                        }

                        groups.push({
                            category: source.key,
                            label: source.title,
                            items: found.map((item) => ({ ...item, index: index++ })),
                        });
                    });

                    return groups;
                },

                /**
                 * Every visible row in order, so the cursor can walk across groups.
                 */
                flattened() {
                    return this.grouped.flatMap((group) => group.items);
                },
            },

            watch: {
                query() {
                    this.cursor = 0;

                    this.searchRecords();
                },
            },

            mounted() {
                window.addEventListener('keydown', this.onKeydown);
            },

            beforeUnmount() {
                window.removeEventListener('keydown', this.onKeydown);
            },

            methods: {
                /**
                 * Open on the shortcut, wherever focus happens to be.
                 */
                onKeydown(event) {
                    if (
                        (event.ctrlKey || event.metaKey)
                        && event.key.toLowerCase() === 'k'
                    ) {
                        event.preventDefault();

                        this.isOpen ? this.close() : this.show();
                    }
                },

                /**
                 * Show the palette, fetching what it searches the first time only.
                 */
                async show() {
                    this.isOpen = true;

                    this.query = '';

                    this.cursor = 0;

                    this.records = {};

                    this.$nextTick(() => this.$refs.query?.focus());

                    if (this.items) {
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const { data } = await this.$axios.get(this.endpoint);

                        this.items = data.data.map((item) => ({
                            ...item,
                            quick: item.category === 'pages' && ! item.path,
                        }));

                        this.sources = data.sources || [];
                    } catch (error) {
                        this.items = [];
                    }

                    this.isLoading = false;
                },

                /**
                 * Close and let the page have the keyboard back.
                 */
                close() {
                    this.isOpen = false;
                },

                /**
                 * Walk the cursor, wrapping at either end.
                 */
                move(step) {
                    const total = this.flattened.length;

                    if (! total) {
                        return;
                    }

                    this.cursor = (this.cursor + step + total) % total;

                    this.$nextTick(() => {
                        const row = this.$refs['row-' + this.cursor];

                        (Array.isArray(row) ? row[0] : row)?.scrollIntoView({ block: 'nearest' });
                    });
                },

                /**
                 * Go where a result points.
                 */
                open(item) {
                    if (! item) {
                        return;
                    }

                    this.close();

                    window.location.href = item.url;
                },

                /**
                 * Ask each source the operator may search for records matching the query.
                 *
                 * Answers are stamped with the search they belong to, so a slow one arriving
                 * after the operator has typed on is discarded rather than shown.
                 */
                searchRecords() {
                    clearTimeout(this.debounce);

                    const query = this.query.trim();

                    this.records = {};

                    this.pending = 0;

                    if (query.length < 2) {
                        return;
                    }

                    this.pending = this.sources.length;

                    this.debounce = setTimeout(() => {
                        const searchId = ++this.searchId;

                        this.sources.forEach((source) => {
                            this.$axios.get(source.endpoint, { params: { query } })
                                .then(({ data }) => {
                                    if (searchId !== this.searchId) {
                                        return;
                                    }

                                    this.records = {
                                        ...this.records,
                                        [source.key]: (data.data || []).slice(0, 5).map((record) => ({
                                            label: source.prefix + source.label.map((field) => record[field]).filter(Boolean).join(' '),
                                            path: source.meta ? record[source.meta] : null,
                                            icon: source.icon,
                                            url: (source.link || '').replace(':id', record.id),
                                        })),
                                    };
                                })
                                .catch(() => {})
                                .finally(() => {
                                    if (searchId === this.searchId) {
                                        this.pending--;
                                    }
                                });
                        });
                    }, 250);
                },

                /**
                 * How far down the navigation an item sits, so a shallower one wins a tie.
                 */
                depth(item) {
                    return item.path ? item.path.split('\u203a').length : 0;
                },

                /**
                 * What a group of results is called, which the empty state renames.
                 */
                groupLabel(category) {
                    if (! this.query.trim()) {
                        return this.quickAccess;
                    }

                    return this.categories[category] || category;
                },

                /**
                 * How well an item answers the query. Lower is better, null does not match.
                 */
                score(item, query) {
                    const label = item.label.toLowerCase();

                    const weight = this.weights[item.category] ?? 9;

                    if (label === query) {
                        return weight;
                    }

                    if (label.startsWith(query)) {
                        return 10 + weight;
                    }

                    if (label.split(/\s+/).some((word) => word.startsWith(query))) {
                        return 20 + weight;
                    }

                    if (label.includes(query)) {
                        return 30 + weight;
                    }

                    const keywords = item.keywords || [];

                    if (keywords.some((keyword) => keyword === query)) {
                        return 40 + weight;
                    }

                    if (keywords.some((keyword) => keyword.startsWith(query))) {
                        return 50 + weight;
                    }

                    if ((item.path || '').toLowerCase().includes(query)) {
                        return 60 + weight;
                    }

                    return null;
                },
            },
        });
    </script>
@endPushOnce
