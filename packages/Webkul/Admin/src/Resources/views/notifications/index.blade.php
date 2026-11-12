<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.notifications.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.notifications.index.before') !!}

    <v-notification-feed>
        <!-- Shimmer Effect -->
        <x-admin::shimmer.notifications />
    </v-notification-feed>

    {!! view_render_event('bagisto.admin.notifications.index.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-notification-feed-template"
        >
            <div>
                <!-- Page Header -->
                <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
                    <div class="grid gap-1.5">
                        <p class="pt-1.5 text-xl font-bold leading-6 text-gray-800 dark:text-white">
                            @lang('admin::app.notifications.title')
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.notifications.description')
                        </p>
                    </div>

                    <button
                        type="button"
                        class="secondary-button"
                        :disabled="! unread"
                        @click="readAll"
                    >
                        @lang('admin::app.notifications.mark-all-read')
                    </button>
                </div>

                <div class="flex items-start gap-4 max-lg:flex-col">
                    <!-- Filters -->
                    <div class="box-shadow w-64 shrink-0 rounded-md bg-white p-2 dark:bg-gray-900 max-lg:flex max-lg:w-full max-lg:gap-1 max-lg:overflow-x-auto">
                        <button
                            type="button"
                            class="flex w-full cursor-pointer items-center gap-2.5 whitespace-nowrap rounded-md px-3 py-2 text-sm transition-all max-lg:w-auto"
                            :class="filterClasses(filter.key)"
                            :aria-pressed="filter.key === activeFilter"
                            v-for="filter in baseFilters"
                            :key="filter.key"
                            @click="applyFilter(filter.key)"
                        >
                            <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center text-xl"
                                aria-hidden="true"
                                :class="filter.icon"
                            >
                            </span>

                            <span v-text="filter.name"></span>

                            <span
                                class="rounded-full bg-blue-600 px-1.5 py-px text-xs font-semibold text-white ltr:ml-auto rtl:mr-auto"
                                v-if="filter.unread"
                                v-text="filter.unread"
                            >
                            </span>
                        </button>

                        <div
                            class="my-1.5 border-t dark:border-gray-800 max-lg:hidden"
                            v-if="groups.length"
                        >
                        </div>

                        <button
                            type="button"
                            class="flex w-full cursor-pointer items-center gap-2.5 whitespace-nowrap rounded-md px-3 py-2 text-sm transition-all max-lg:w-auto"
                            :class="filterClasses(group.key)"
                            :aria-pressed="group.key === activeFilter"
                            v-for="group in groups"
                            :key="group.key"
                            @click="applyFilter(group.key)"
                        >
                            <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center text-xl"
                                aria-hidden="true"
                                :class="group.icon"
                            >
                            </span>

                            <span v-text="group.name"></span>

                            <span
                                class="rounded-full bg-blue-600 px-1.5 py-px text-xs font-semibold text-white ltr:ml-auto rtl:mr-auto"
                                v-if="group.unread"
                                v-text="group.unread"
                            >
                            </span>
                        </button>
                    </div>

                    <!-- Feed -->
                    <div class="box-shadow flex min-w-0 flex-1 flex-col rounded-md bg-white dark:bg-gray-900 max-lg:w-full">
                        <template v-if="isLoading">
                            <x-admin::shimmer.notifications.list />
                        </template>

                        <div
                            role="feed"
                            v-else-if="notifications.length"
                        >
                            <div
                                class="border-b last:border-b-0 dark:border-gray-800"
                                v-for="day in days"
                                :key="day.key"
                            >
                                <p
                                    class="px-4 pb-1 pt-4 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400"
                                    v-text="day.label"
                                >
                                </p>

                                <x-admin::notifications.item
                                    v-for="notification in day.items"
                                    ::key="notification.id"
                                    ::notification="notification"
                                    @toggle-read="toggleRead"
                                />
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div
                            class="grid justify-items-center gap-3 px-6 py-16 text-center"
                            v-else
                        >
                            <span
                                class="icon-notification flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-4xl text-gray-400 dark:bg-gray-800 dark:text-gray-500!"
                                aria-hidden="true"
                            >
                            </span>

                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.notifications.empty-title')
                            </p>

                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                @lang('admin::app.notifications.empty-description')
                            </p>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="flex items-center justify-between gap-2 border-t px-4 py-3 dark:border-gray-800"
                            v-if="meta.total > meta.per_page"
                        >
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <select
                                    class="custom-select rounded-md border bg-white px-2.5 py-1.5 text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pr-8 rtl:pl-8"
                                    v-model.number="perPage"
                                    @change="fetch(1)"
                                >
                                    <option
                                        v-for="option in perPageOptions"
                                        :key="option"
                                        :value="option"
                                        v-text="option"
                                    >
                                    </option>
                                </select>

                                @lang('admin::app.notifications.per-page')
                            </div>

                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <span>
                                    @{{ meta.current_page }} @lang('admin::app.notifications.of') @{{ meta.last_page }}
                                </span>

                                <button
                                    type="button"
                                    class="inline-flex cursor-pointer items-center rounded-md border bg-white p-1.5 transition-all hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
                                    :disabled="meta.current_page <= 1"
                                    @click="fetch(meta.current_page - 1)"
                                >
                                    <span class="icon-sort-left rtl:icon-sort-right text-2xl"></span>
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex cursor-pointer items-center rounded-md border bg-white p-1.5 transition-all hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
                                    :disabled="meta.current_page >= meta.last_page"
                                    @click="fetch(meta.current_page + 1)"
                                >
                                    <span class="icon-sort-right rtl:icon-sort-left text-2xl"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-notification-feed', {
                template: '#v-notification-feed-template',

                data() {
                    return {
                        notifications: [],

                        groups: [],

                        unread: 0,

                        meta: {},

                        activeFilter: 'all',

                        perPage: 10,

                        perPageOptions: [10, 20, 50],

                        isLoading: true,
                    };
                },

                computed: {
                    /**
                     * The filters every admin has, ahead of the groups their role may see.
                     */
                    baseFilters() {
                        return [
                            {
                                key: 'all',
                                name: @json(trans('admin::app.notifications.all')),
                                icon: 'icon-notification',
                                unread: 0,
                            },
                            {
                                key: 'unread',
                                name: @json(trans('admin::app.notifications.unread')),
                                icon: 'icon-dot',
                                unread: this.unread,
                            },
                        ];
                    },

                    /**
                     * The notifications of the page, gathered under the day they were raised.
                     */
                    days() {
                        return this.notifications.reduce((days, notification) => {
                            const last = days[days.length - 1];

                            if (last?.key === notification.day) {
                                last.items.push(notification);
                            } else {
                                days.push({
                                    key: notification.day,
                                    label: notification.day_label,
                                    items: [notification],
                                });
                            }

                            return days;
                        }, []);
                    },
                },

                mounted() {
                    this.fetch();

                    this.$emitter.on('notifications-changed', this.refresh);

                    window.Echo?.private('admin.notifications')
                        .listen('.notification-created', () => this.refresh());
                },

                beforeUnmount() {
                    this.$emitter.off('notifications-changed', this.refresh);
                },

                methods: {
                    /**
                     * Fetch a page of the feed under the active filter, with the unread count of every group.
                     */
                    fetch(page = 1) {
                        const params = {
                            page,
                            per_page: this.perPage,
                        };

                        if (this.activeFilter === 'unread') {
                            params.unread = 1;
                        } else if (this.activeFilter !== 'all') {
                            params.group = this.activeFilter;
                        }

                        return this.$axios.get("{{ route('admin.notification.list') }}", { params })
                            .then((response) => {
                                this.notifications = response.data.data;

                                this.meta = response.data.meta;

                                this.groups = response.data.groups;

                                this.unread = response.data.unread;

                                this.isLoading = false;
                            })
                            .catch(() => {
                                this.isLoading = false;
                            });
                    },

                    /**
                     * Fetch the page on show again, after a notification arrives or another view changed one.
                     */
                    refresh() {
                        this.fetch(this.meta.current_page ?? 1);
                    },

                    /**
                     * Show the feed under a filter, from its first page.
                     */
                    applyFilter(filter) {
                        this.activeFilter = filter;

                        this.isLoading = true;

                        this.fetch();
                    },

                    /**
                     * The classes of a filter, highlighting the active one.
                     */
                    filterClasses(filter) {
                        return this.activeFilter === filter
                            ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-gray-950 dark:text-blue-400'
                            : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950';
                    },

                    /**
                     * Mark a notification read or unread, and tell the bell its count has changed.
                     */
                    toggleRead(notification) {
                        const request = notification.read
                            ? this.$axios.delete("{{ route('admin.notification.mark_unread', ':id') }}".replace(':id', notification.id))
                            : this.$axios.post("{{ route('admin.notification.mark_read', ':id') }}".replace(':id', notification.id));

                        request.then(() => {
                            this.refresh();

                            this.$emitter.emit('notifications-changed');
                        });
                    },

                    /**
                     * Mark everything under the active group read, or everything when no group is chosen.
                     */
                    readAll() {
                        const group = ['all', 'unread'].includes(this.activeFilter) ? null : this.activeFilter;

                        this.$axios.post("{{ route('admin.notification.read_all') }}", { group })
                            .then((response) => {
                                this.refresh();

                                this.$emitter.emit('notifications-changed');

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
