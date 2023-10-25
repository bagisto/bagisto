<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.notifications.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.marketing.notifications.create.before') !!}

    {{-- Vue Component --}}
    <v-notification-list></v-notification-list>

    {!! view_render_event('bagisto.admin.marketing.notifications.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-notification-list-template"
        >
                <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
                    <div class="grid gap-[6px]">
                        <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                            @lang('admin::app.notifications.title')
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.notifications.description-text')
                        </p>
                    </div>
                </div>

                <div class="flex flex-col justify-between max-w-max bg-white dark:bg-gray-900 rounded-[6px] box-shadow h-[calc(100vh-179px)]">
                    <div class="">
                        <div class="flex border-b-[1px] dark:border-gray-800 overflow-auto journal-scroll">
                            <div
                                class="flex py-[15px] px-[15px] gap-[4px] border-b-[2px] dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                                :class="{'border-blue-600 dark:border-blue-600': status == data.status}"
                                ref="tabs"
                                v-for="data in orderType"
                                @click="status = data.status; getNotification()"
                            >
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-text="data.message"
                                >
                                </p>

                                <span
                                    class="text-[12px] text-white font-semibold py-[1px] px-[6px] bg-gray-400 rounded-[35px]"
                                    v-text="data.status_count ?? '0'"
                                >
                                </span>
                            </div>    

                        </div>

                        <div
                            class="grid max-h-[calc(100vh-330px)] overflow-auto journal-scroll"
                            v-if="notifications.length"
                        >
                            <a
                                :href="'{{ route('admin.notification.viewed_notification', ':orderId') }}'.replace(':orderId', notification.order_id)"
                                class="flex gap-[5px] p-[16px] items-start hover:bg-gray-50 dark:hover:bg-gray-950"
                                v-for="notification in notifications"
                                :class="notification.read ? 'opacity-50' : ''"
                            >
                                <span
                                    v-if="notification.order.status in orderType"
                                    class="h-fit text-[24px] rounded-full"
                                    :class="orderType[notification.order.status].icon"
                                >
                                </span>

                                <div class="grid">
                                    <p class="text-gray-800 dark:text-white">
                                        #@{{ notification.order.id }}
                                        @{{ orderType[notification.order.status].message }}
                                    </p>
        
                                    <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                        @{{ notification.order.datetime }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <!-- For Empty Data -->
                        <div
                            class="px-[24px] py-[12px] max-h-[calc(100vh-330px)]"
                            v-else
                        >
                            @lang('admin::app.notifications.no-record')
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex gap-x-[8px] items-center p-[24px] border-t-[1px] dark:border-gray-800">
                        <div
                            class="inline-flex gap-x-[4px] items-center justify-between ltr:ml-[8px] rtl:mr-[8px] text-gray-600 dark:text-gray-300 py-[6px] px-[8px] leading-[24px] text-center w-full max-w-max bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black max-sm:hidden" 
                            v-text="pagination.per_page"
                        >
                        </div>

                        <span class="text-gray-600 dark:text-gray-300 whitespace-nowrap">of</span>

                        <p
                            class="text-gray-600 dark:text-gray-300 whitespace-nowrap"
                            v-text="pagination.current_page"
                        >
                        </p>

                        <!-- Prev & Next Page Button -->
                        <div class="flex gap-[4px] items-center">
                            <a @click="getResults()">
                                <div class="inline-flex gap-x-[4px] items-center justify-between ltr:ml-[8px] rtl:mr-[8px] text-gray-600 dark:text-gray-300 p-[6px] text-center w-full max-w-max bg-white dark:bg-gray-900 border rounded-[6px] dark:border-gray-800 cursor-pointer transition-all hover:border hover:bg-gray-100 dark:hover:bg-gray-950 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                                    <span class="icon-sort-left text-[24px]"></span>
                                </div>
                            </a>

                            <a @click="getResults(pagination.last_page)">
                                <div
                                    class="inline-flex gap-x-[4px] items-center justify-between ltr:ml-[8px] rtl:mr-[8px] text-gray-600 dark:text-gray-300 p-[6px] text-center w-full max-w-max bg-white dark:bg-gray-900 border rounded-[6px] dark:border-gray-800 cursor-pointer transition-all hover:border hover:bg-gray-100 dark:hover:bg-gray-950 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                                    <span class="icon-sort-right text-[24px]"></span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
        </script>

        <script type="module">
            app.component('v-notification-list',{
                template: '#v-notification-list-template',

                data() {
                    return {
                        notifications: [],

                        pagination: {},

                        status: 'all',

                        orderStatusMessages: {
                            pending: "@lang('admin::app.notifications.order-status-messages.pending')",
                            canceled: "@lang('admin::app.notifications.order-status-messages.canceled')",
                            closed: "@lang('admin::app.notifications.order-status-messages.closed')",
                            completed: "@lang('admin::app.notifications.order-status-messages.completed')",
                            processing: "@lang('admin::app.notifications.order-status-messages.processing')" 
                        },

                        orderStatus: {
                            all: "@lang('admin::app.notifications.status.all')",
                            pending: "@lang('admin::app.notifications.status.pending')",
                            canceled: "@lang('admin::app.notifications.status.canceled')",
                            closed: "@lang('admin::app.notifications.status.closed')",
                            completed: "@lang('admin::app.notifications.status.completed')",
                            processing: "@lang('admin::app.notifications.status.processing')" 
                        },
                        
                        orderType: {
                            all : {
                                icon: 'icon',
                                message: 'All',
                                status: 'all'
                            },
                            pending : {
                                icon: 'icon-information text-amber-600 bg-amber-100',
                                message: 'Order Pending',
                                status: 'pending'
                            },
                            processing : {
                                icon: 'icon-sort-right text-green-600 bg-green-100',
                                message: 'Order Processing',
                                status: 'processing'
                            },
                            canceled : {
                                icon: 'icon-cancel-1 text-red-600 bg-red-100',
                                message: 'Order Canceled',
                                status: 'canceled'
                            },
                            completed : {
                                icon: 'icon-done text-blue-600 bg-blue-100',
                                message: 'Order Completed',
                                status: 'completed'
                            },
                            closed : {
                                icon: 'icon-repeat text-red-600 bg-red-100',
                                message: 'Order Closed',
                                status: 'closed'
                            },
                        },
                    }
                },

                mounted() {
                    this.getNotification();
                },

                methods: {
                    getNotification($event) {
                        const params = {};

                        if (this.status != 'all') {
                            params.status = this.status
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
                        })
                        .catch(error => console.log(error));
                    },

                    getResults(page = 1) {
                        axios.get(`${"{{ route('admin.notification.get_notification') }}"}?page=${page}`)
                            .then(response => {
                                this.notifications = [];

                                this.notifications = response.data.search_results.data;

                                this.pagination = response.data.search_results;
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>