{{-- Notificatin Component --}}
<v-notification {{ $attributes }}>
    <span class="relative">
        <span 
            class="icon-notification p-[6px] bg-gray-100 rounded-[6px] text-[24px] text-red cursor-pointer transition-all" 
            title="@lang('admin::app.components.layouts.header.notifications')"
        >
        </span>
    </span>
</v-notification>

@pushOnce('scripts')
    <script 
        type="text/x-template"
        id="v-notification-template"
    >
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <!-- Notification Toggle -->
            <x-slot:toggle>
                <span class="relative">
                    <span
                        class="icon-notification p-[6px] bg-gray-100 rounded-[6px] text-[24px] text-red cursor-pointer transition-all" 
                        title="@lang('admin::app.components.layouts.header.notifications')"
                    >
                    </span>
                
                    <span
                        class="absolute px-[7px] top-[-15px] left-[18px] py-[5px] bg-[#060C3B] rounded-[44px] text-[#fff] text-[10px] font-semibold leading-[9px] cursor-pointer"
                        v-text="totalUnRead"
                        v-if="totalUnRead"
                    >
                    </span>
                </span>
            </x-slot:toggle>

            <!-- Notification Content -->
            <x-slot:content class="!p-0">
                <div class="box-shadow max-w-[320px]">
                    <div class="p-[24px]">
                        <p
                            class="text-[16px] text-gray-600 font-semibold mb-[12px]"
                            v-text="notifTitle"
                        >
                        </p>

                        <div
                            class="grid gap-[24px]"
                            v-if="notifications?.length"
                        >
                            <a
                                class="flex gap-[5px] items-start"
                                :href="`${orderViewUrl + notification.order_id}`"
                                v-for="notification in notifications"
                            >
                                <!-- Notification Icon -->
                                <span
                                    v-if="notification.order.status in notificationStatusIcon"
                                    class="h-fit"
                                    :class="notificationStatusIcon[notification.order.status]"
                                >
                                </span>

                                <div class="grid">
                                    <!-- Order Id & Status -->
                                    <p class="text-gray-800">
                                        #@{{ notification.order.id }}
                                        @{{ orderTypeMessages[notification.order.status] }}
                                    </p>

                                    <!-- Craeted Date In humand Readable Format -->
                                    <p class="text-[12px] text-gray-600">
                                        @{{ notification.order.datetime }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                        <!-- Notification Footer -->
                    <div class="flex gap-[10px] justify-between p-[24px] border-t-[1px] border-b-[1px] border-gray-300">
                        <a
                            :href="viewAll"
                            class="text-[12px] text-blue-600 font-semibold cursor-pointer"
                            :text="viewAllTitle"
                        >
                        </a>

                        <a
                            class="text-[12px] text-blue-600 font-semibold cursor-pointer"
                            :text="readAllTitle"
                            v-if="notifications?.length"
                            @click="readAll()"
                        >
                        </a>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::dropdown>
    </script>

    <script type="module">
        app.component('v-notification', {
            template: '#v-notification-template',

            props: [
                'notifTitle',
                'getNotificationUrl',
                'viewAll',
                'orderViewUrl',
                'pusherKey',
                'pusherCluster',
                'title',
                'viewAllTitle',
                'getReadAllUrl',
                'readAllTitle',
                'orderStatusMessages',
                'localeCode'
            ],

            data() {
                return {
                    notifications: [],

                    ordertype: {
                        pending: {
                            icon: 'icon-information',
                            message: 'Order Pending',
                        },
                        processing: {
                            icon: 'icon-processing',
                            message: 'Order Processing'
                        },
                        canceled: {
                            icon: 'icon-cancel-1',
                            message: 'Order Canceled'
                        },
                        completed: {
                            icon: 'icon-done',
                            message: 'Order Completed'
                        },
                        closed: {
                            icon: 'icon-cancel-1',
                            message: 'Order Closed'
                        },
                        pending_payment: {
                            icon: 'icon-information',
                            message: 'Payment Pending'
                        },
                    },

                    totalUnRead: 0,

                    orderTypeMessages: JSON.parse(this.orderStatusMessages)
                }
            },

            computed: {
                notificationStatusIcon() {
                    return {
                        pending: 'icon-information text-[24px] text-amber-600 bg-amber-100 rounded-full',
                        closed: 'icon-repeat text-[24px] text-red-600 bg-red-100 rounded-full',
                        completed: 'icon-done text-[24px] text-blue-600 bg-blue-100 rounded-full',
                        canceled: 'icon-cancel-1 text-[24px] text-red-600 bg-red-100 rounded-full',
                        processing: 'icon-sort-right text-[24px] text-green-600 bg-green-100 rounded-full',
                    };
                },
            },

            mounted() {
                this.getNotification();

                // todo echo @suraj-webkul need discussion with @devash sir

                // if (this.pusherKey != undefined && this.pusherCluster != undefined) {
                //     Echo = new Echo({
                //         broadcaster: 'pusher',
                //         key: this.pusherKey,
                //         cluster: this.pusherCluster,
                //         encrypted: true
                //     });

                //     Echo.channel('notification').listen('.create-notification', (e) => {
                //         this.getNotification();
                //     });

                //     Echo.channel('notification').listen('.update-notification', (e) => {
                //         this.notifications.forEach((notification) => {
                //             if (notification.order_id == e.id) {
                //                 notification.order.status = e.status;
                //             }
                //         });
                //     });
                // }
            },

            methods: {
                getNotification() {
                    this.$axios.get(this.getNotificationUrl, {
                            params: {
                                limit: 5,
                                read: 0
                            }
                        })
                        .then((response) => {
                            this.notifications = response.data.search_results.data;

                            this.totalUnRead =   response.data.total_unread;
                        })
                        .catch(error => console.log(error))
                },

                readAll() {
                    this.$axios.post(this.getReadAllUrl)
                        .then((response) => {
                            this.notifications = response.data.search_results.data;

                            this.totalUnRead = response.data.total_unread;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.success_message });
                        })
                        .catch((error) => {});
                }
            }
        });
    </script>
@endPushOnce