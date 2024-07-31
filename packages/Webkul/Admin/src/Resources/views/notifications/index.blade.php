<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.notifications.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.notifications.create.before') !!}

    <!-- Vue Component -->
    <v-notification-list>
        <!-- Shimmer Effect -->
        <x-admin::shimmer.notifications />
    </v-notification-list>

    {!! view_render_event('bagisto.admin.marketing.notifications.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-notification-list-template"
        >
            <template v-if="isLoading">
                <!-- Shimmer Effect -->
                <x-admin::shimmer.notifications />
            </template>

            <template v-else>
                <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
                    <div class="grid gap-1.5">
                        <p class="pt-1.5 text-xl font-bold leading-6 text-gray-800 dark:text-white">
                            @lang('admin::app.notifications.title')
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.notifications.description-text')
                        </p>
                    </div>
                </div>

                <div class="box-shadow flex h-[calc(100vh-179px)] max-w-max flex-col justify-between rounded-md bg-white dark:bg-gray-900">
                    <div>
                        <div class="journal-scroll flex overflow-auto border-b dark:border-gray-800">
                            <div
                                class="flex cursor-pointer items-center gap-1 border-b-2 px-4 py-4 hover:bg-gray-100 dark:hover:bg-gray-950"
                                :class="{'border-blue-600 dark:border-blue-600': status == data.status}"
                                v-for="data in orderType"
                                @click="status=data.status; getNotification()"
                            >
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ data.message }}
                                </p>

                                <span class="rounded-full bg-gray-400 px-1.5 py-px text-xs font-semibold text-white">
                                    @{{ data.status_count ?? '0' }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="journal-scroll grid max-h-[calc(100vh-330px)] overflow-auto"
                            v-if="notifications.length"
                        >
                            <a
                                :href="'{{ route('admin.notification.viewed_notification', ':orderId') }}'.replace(':orderId', notification.order_id)"
                                class="flex h-14 items-start gap-1.5 p-4 hover:bg-gray-50 dark:hover:bg-gray-950"
                                v-for="notification in notifications"
                            >
                                <span
                                    v-if="notification.order.status in orderType"
                                    class="h-fit rounded-full text-2xl"
                                    :class="orderType[notification.order.status].icon"
                                >
                                </span>

                                <div class="grid">
                                    <p  
                                        class="text-gray-800 dark:text-white"
                                        :class="notification.read ? 'font-normal' : 'font-semibold'"
                                    >
                                        #@{{ notification.order.id }}
                                        @{{ orderType[notification.order.status].message }}
                                    </p>

                                    <p class="text-xs text-gray-600 dark:text-gray-300">
                                        @{{ notification.order.datetime }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <!-- For Empty Data -->
                        <div
                            class="max-h-[calc(100vh-330px)] px-6 py-3 text-gray-600 dark:text-gray-300"
                            v-else
                        >
                            @lang('admin::app.notifications.no-record')
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center gap-x-2 border-t p-4 dark:border-gray-800">
                        <div class="inline-flex w-full max-w-max appearance-none items-center justify-between gap-x-1 rounded-md border bg-white px-2 py-1.5 text-center leading-6 text-gray-600 marker:shadow focus:outline-none focus:ring-2 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 max-sm:hidden ltr:ml-2 rtl:mr-2">
                            @{{ pagination.per_page }}
                        </div>

                        <span class="whitespace-nowrap text-gray-600 dark:text-gray-300">
                            @lang('admin::app.notifications.per-page')
                        </span>

                        <p class="whitespace-nowrap text-gray-600 dark:text-gray-300">
                            @{{ pagination.current_page }}
                        </p>

                        <span class="whitespace-nowrap text-gray-600 dark:text-gray-300">
                            @lang('admin::app.notifications.of')
                        </span>

                        <p class="whitespace-nowrap text-gray-600 dark:text-gray-300">
                            @{{ pagination.last_page }}
                        </p>

                        <!-- Prev & Next Page Button -->
                        <div class="flex items-center gap-1">
                            <a @click="getResults(pagination.prev_page_url)">
                                <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border bg-white p-1.5 text-center text-gray-600 transition-all marker:shadow hover:border hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-950 ltr:ml-2 rtl:mr-2">
                                    <span class="icon-sort-left rtl:icon-sort-right text-2xl"></span>
                                </div>
                            </a>

                            <a @click="getResults(pagination.next_page_url)">
                                <div
                                    class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border bg-white p-1.5 text-center text-gray-600 transition-all marker:shadow hover:border hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-950 ltr:ml-2 rtl:mr-2">
                                    <span class="icon-sort-right rtl:icon-sort-left text-2xl"></span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </script>

        <script type="module">
            app.component('v-notification-list',{
                template: '#v-notification-list-template',

                data() {
                    return {
                        notifications: [],

                        pagination: {},

                        status: 'all',

                        orderType: {
                            all : {
                                icon: 'icon',
                                message: "@lang('admin::app.notifications.order-status-messages.all'),
                                status: 'all'
                            },

                            pending : {
                                icon: 'icon-information bg-amber-100 text-amber-600 dark:!text-amber-600',
                                message: "@lang('admin::app.notifications.order-status-messages.pending')",
                                status: 'pending'
                            },

                            processing : {
                                icon: 'icon-sort-right bg-green-100 text-green-600 dark:!text-green-600',
                                message: "@lang('admin::app.notifications.order-status-messages.processing')",
                                status: 'processing'
                            },

                            canceled : {
                                icon: 'icon-cancel-1 bg-red-100 text-red-600 dark:!text-red-600',
                                message: "@lang('admin::app.notifications.order-status-messages.canceled')",
                                status: 'canceled'
                            },

                            completed : {
                                icon: 'icon-done bg-blue-100 text-blue-600 dark:!text-blue-600',
                                message: "@lang('admin::app.notifications.order-status-messages.completed')",
                                status: 'completed'
                            },

                            closed : {
                                icon: 'icon-repeat bg-red-100 text-red-600 dark:!text-red-600',
                                message: "@lang('admin::app.notifications.order-status-messages.closed')",
                                status: 'closed'
                            },
                        },

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getNotification();
                },

                methods: {
                    getNotification() {
                        const params = {};

                        if (this.status != 'all') {
                            params.status = this.status;
                        }

                        this.$axios.get("{{ route('admin.notification.get_notification') }}", {
                            params: params
                        })
                        .then((response) => {
                            this.notifications = response.data.search_results.data;

                            let total = 0;

                            response.data.status_count.forEach((item) => {
                                this.orderType[item.status].status_count = item.status_count;

                                total += item.status_count;
                            });

                            this.orderType['all'].status_count = total;

                            this.pagination = response.data.search_results;

                            this.isLoading = false;
                        })
                        .catch(error => console.log(error));
                    },

                    getResults(url) {
                        if (url) {
                            this.$axios.get(url)
                                .then(response => {
                                    this.notifications = response.data.search_results.data;

                                    this.pagination = response.data.search_results;
                                })
                                .catch(error => console.log(error));
                        }
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>