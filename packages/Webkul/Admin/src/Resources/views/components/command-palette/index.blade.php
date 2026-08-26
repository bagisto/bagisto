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
                <!-- Trail -->
                <div
                    v-if="levels.length"
                    class="flex shrink-0 items-center gap-1.5 border-b px-3 py-2 text-xs dark:border-gray-800"
                >
                    <button
                        type="button"
                        class="flex shrink-0 items-center gap-1 rounded px-1.5 py-1 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-950"
                        @click="back"
                    >
                        <span class="text-sm leading-none">&lsaquo;</span>
                        @lang('admin::app.command-palette.back')
                    </button>

                    <template v-for="(level, index) in levels" :key="index">
                        <span class="text-gray-300 dark:text-gray-600">&rsaquo;</span>

                        <button
                            type="button"
                            class="max-w-40 truncate rounded px-1 py-1 hover:bg-gray-100 dark:hover:bg-gray-950"
                            :class="index === levels.length - 1
                                ? 'font-medium text-gray-800 dark:text-white'
                                : 'text-gray-500 dark:text-gray-400'"
                            @click="goTo(index)"
                        >
                            @{{ level.label }}
                        </button>
                    </template>
                </div>

                <!-- Query -->
                <div class="flex shrink-0 items-center gap-2.5 border-b px-4 dark:border-gray-800">
                    <i class="icon-search shrink-0 text-2xl text-gray-500 dark:text-gray-400"></i>

                    <input
                        ref="query"
                        v-model="query"
                        type="text"
                        class="w-full bg-transparent py-3.5 text-base text-gray-800 outline-none placeholder:text-gray-400 dark:text-white dark:placeholder:text-gray-500"
                        :placeholder="currentPlaceholder"
                        autocomplete="off"
                        spellcheck="false"
                        @keydown.down.prevent="move(1)"
                        @keydown.up.prevent="move(-1)"
                        @keydown.enter.prevent="open(flattened[cursor])"
                        @keydown.esc.prevent="close"
                        @keydown.left="onBack"
                        @keydown.delete="onBack"
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
                                        v-if="pathOf(item)"
                                        class="block truncate text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        @{{ pathOf(item) }}
                                    </span>
                                </span>

                                <span
                                    v-if="opensLevel(item)"
                                    class="shrink-0 text-base leading-none text-gray-400 dark:text-gray-500"
                                >&rsaquo;</span>

                                <kbd
                                    v-else-if="item.index === cursor"
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
                        v-else-if="awaitingQuery"
                        class="px-3 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                    >
                        @lang('admin::app.command-palette.start-typing')
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

                    levels: [],

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

                    contextPlaceholder: "@lang('admin::app.command-palette.context-placeholder', ['context' => ':context'])",

                    order: ['pages', 'actions', 'configuration'],

                    weights: { pages: 0, actions: 1, configuration: 2 },

                    limit: 40,

                    /**
                     * How closely a result answered the query, lowest first.
                     *
                     * The first four read its own name; the rest read what it was lent.
                     */
                    tiers: {
                        exact: 0,
                        prefix: 10,
                        word: 20,
                        contains: 30,
                        keyword: 40,
                        keywordPrefix: 50,
                        trail: 60,
                    },
                };
            },

            computed: {
                /**
                 * The level the operator is looking at, or nothing at the root.
                 */
                level() {
                    return this.levels.length ? this.levels[this.levels.length - 1] : null;
                },

                /**
                 * What the current level offers, before any query narrows it.
                 */
                scope() {
                    if (! this.items) {
                        return [];
                    }

                    return this.level ? (this.level.children || []) : this.items;
                },

                /**
                 * Everything reachable from the current level, for searching within it.
                 */
                searchable() {
                    const nodes = [];

                    const parents = new Map();

                    const walk = (candidates, parent) => candidates.forEach((node) => {
                        nodes.push(node);

                        parents.set(node, parent);

                        walk(node.children || [], node);
                    });

                    walk(this.scope, null);

                    return { nodes, parents };
                },

                /**
                 * The results for the current query, best first.
                 */
                matches() {
                    if (! this.items) {
                        return [];
                    }

                    const query = this.query.trim().toLowerCase();

                    if (this.level?.type === 'collection') {
                        return this.scope;
                    }

                    if (! query) {
                        return this.level
                            ? this.scope
                            : this.items.filter((item) => item.quick).slice(0, this.limit);
                    }

                    const { nodes, parents } = this.searchable;

                    const scored = nodes
                        .filter((item) => this.level || item.type !== 'collection')
                        .map((item) => ({ item, score: this.score(item, query) }))
                        .filter((entry) => entry.score !== null)
                        .sort((a, b) => this.depth(a.item) - this.depth(b.item)
                            || a.score - b.score
                            || a.item.label.length - b.item.label.length);

                    const shown = new Set();

                    const results = [];

                    scored.forEach(({ item, score }) => {
                        if (
                            score >= this.tiers.keyword
                            && this.hasShownAncestor(item, parents, shown)
                        ) {
                            return;
                        }

                        shown.add(item);

                        results.push(item);
                    });

                    return results.slice(0, this.limit);
                },

                /**
                 * Whether the level is waiting on a query before it has anything to show.
                 */
                awaitingQuery() {
                    return this.level?.type === 'collection'
                        && ! this.query.trim()
                        && ! this.matches.length;
                },

                /**
                 * The trail leading to the current level, as its own children spell it.
                 */
                levelTrail() {
                    if (! this.level) {
                        return null;
                    }

                    return this.level.path
                        ? this.level.path + '\u0020›\u0020' + this.level.label
                        : this.level.label;
                },

                /**
                 * What the query box invites at this level.
                 */
                currentPlaceholder() {
                    return this.level
                        ? this.contextPlaceholder.replace(':context', this.level.label)
                        : this.placeholder;
                },

                /**
                 * The results in their groups, each row carrying its position in the whole list.
                 */
                grouped() {
                    const groups = [];

                    let index = 0;

                    if (this.level) {
                        return this.matches.length
                            ? [{ category: 'level', label: this.level.label,
                                 items: this.matches.map((item) => ({ ...item, index: index++ })) }]
                            : [];
                    }

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

                    this.levels = [];

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
                 * The trail worth showing under a result, which is none inside its own level.
                 */
                pathOf(item) {
                    if (
                        this.level
                        && item.path === this.levelTrail
                    ) {
                        return null;
                    }

                    return item.path;
                },

                /**
                 * Whether selecting this result opens another level rather than leaving.
                 */
                opensLevel(item) {
                    return item.type === 'branch' || item.type === 'collection';
                },

                /**
                 * Select a result: step into it when it has a level, otherwise go there.
                 */
                open(item) {
                    if (! item) {
                        return;
                    }

                    if (this.opensLevel(item)) {
                        this.enter(item);

                        return;
                    }

                    if (! item.url) {
                        return;
                    }

                    this.close();

                    window.location.href = item.url;
                },

                /**
                 * Step into a level, fetching its records first when it holds them.
                 */
                async enter(item) {
                    this.levels.push(item);

                    this.query = '';

                    this.cursor = 0;

                    this.$nextTick(() => this.$refs.query?.focus());

                    if (item.type !== 'collection' || item.children.length) {
                        return;
                    }

                    this.isLoading = true;

                    item.children = await this.fetchRecords(item.source, '');

                    this.isLoading = false;
                },

                /**
                 * Step back out of the current level.
                 */
                back() {
                    this.levels.pop();

                    this.query = '';

                    this.cursor = 0;

                    this.$nextTick(() => this.$refs.query?.focus());
                },

                /**
                 * Step back, but only when nothing is typed, so editing a query is unhurt.
                 */
                onBack(event) {
                    if (
                        ! this.levels.length
                        || this.query
                    ) {
                        return;
                    }

                    event.preventDefault();

                    this.back();
                },

                /**
                 * Jump straight to a level named in the trail.
                 */
                goTo(index) {
                    this.levels = this.levels.slice(0, index + 1);

                    this.query = '';

                    this.cursor = 0;

                    this.$nextTick(() => this.$refs.query?.focus());
                },

                /**
                 * Records of a source, each carrying the actions it opens.
                 */
                async fetchRecords(key, query) {
                    const source = this.sources.find((candidate) => candidate.key === key);

                    if (! source) {
                        return [];
                    }

                    try {
                        const { data } = await this.$axios.get(source.endpoint, { params: { query } });

                        return (data.data || []).slice(0, 20).map((record) => this.toRecord(source, record));
                    } catch (error) {
                        return [];
                    }
                },

                /**
                 * Turn a record into an entry, whose level is what may be done with it.
                 */
                toRecord(source, record) {
                    const label = source.prefix + source.label.map((field) => record[field]).filter(Boolean).join(' ');

                    return {
                        label,
                        path: source.meta ? record[source.meta] : null,
                        icon: source.icon,
                        url: (source.link || '').replace(':id', record.id),
                        category: 'pages',
                        keywords: [],
                        type: source.actions.length ? 'branch' : 'navigate',
                        children: source.actions.map((action) => ({
                            label: action.title,
                            path: label,
                            icon: action.icon,
                            url: action.link.replace(':id', record.id),
                            category: 'actions',
                            keywords: [],
                            type: 'navigate',
                            children: [],
                        })),
                    };
                },

                /**
                 * Ask each source the operator may search for records matching the query.
                 *
                 * Answers are stamped, so one arriving after the query moved on is discarded.
                 */
                searchRecords() {
                    clearTimeout(this.debounce);

                    const query = this.query.trim();

                    if (this.level) {
                        this.searchWithinLevel(query);

                        return;
                    }

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
                 * Re-ask the source behind the current level, so its records follow the query.
                 */
                searchWithinLevel(query) {
                    if (this.level.type !== 'collection') {
                        return;
                    }

                    const searchId = ++this.searchId;

                    this.debounce = setTimeout(async () => {
                        const found = await this.fetchRecords(this.level.source, query);

                        if (searchId === this.searchId) {
                            this.level.children = found;
                        }
                    }, 250);
                },

                /**
                 * Whether something this item sits beneath is already among the results.
                 *
                 * Such an entry matched on a name it was lent, so the ancestor already says it.
                 */
                hasShownAncestor(item, parents, shown) {
                    let parent = parents.get(item);

                    while (parent) {
                        if (shown.has(parent)) {
                            return true;
                        }

                        parent = parents.get(parent);
                    }

                    return false;
                },

                /**
                 * How far down the navigation an item sits.
                 *
                 * Results are ordered by this first, so a section is offered ahead of its pages.
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
                        return this.tiers.exact + weight;
                    }

                    if (label.startsWith(query)) {
                        return this.tiers.prefix + weight;
                    }

                    if (label.split(/\s+/).some((word) => word.startsWith(query))) {
                        return this.tiers.word + weight;
                    }

                    if (label.includes(query)) {
                        return this.tiers.contains + weight;
                    }

                    const keywords = item.keywords || [];

                    if (keywords.some((keyword) => keyword === query)) {
                        return this.tiers.keyword + weight;
                    }

                    if (keywords.some((keyword) => keyword.startsWith(query))) {
                        return this.tiers.keywordPrefix + weight;
                    }

                    if ((item.path || '').toLowerCase().includes(query)) {
                        return this.tiers.trail + weight;
                    }

                    return null;
                },
            },
        });
    </script>
@endPushOnce
