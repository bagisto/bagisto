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
        <div>
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
                <x-slot:content class="!p-[0px]">
                    <span   
                        class="block text-gray-600 text-left font-semibold p-2"
                        v-text="notifTitle"
                    >
                    </span>

                    <div 
                        class="py-2"
                        v-if="notifications.length > 0"
                    >
                        <a 
                            :href="`${orderViewUrl + notification.order_id}`"
                            class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2"
                            v-for="notification in notifications"
                        >
                            <span   
                                class="rounded-full object-cover text-[24px] mx-1"
                                :class="ordertype[notification.order.status].icon"
                            >
                            </span>

                            <p class="text-gray-600 text-sm mx-2">
                                <span class="font-bold">
                                    #@{{ notification.order.id }}
                                    @{{ orderTypeMessages[notification.order.status] }}
                                    @{{ notification.order.human_readable_datetime }}
                                </span>
                            </p>
                        </a>
                    </div>

                    <!-- Notification Footer -->
                    <div class="flex gap-[10px] items-center justify-between">
                        <a  
                            :href="viewAll"
                            class="block text-gray-600 text-center font-bold p-2"
                            :text="viewAllTitle"
                        >
                        </a>
                        
                        <a  
                            class="block text-gray-600 text-center font-bold p-2 cursor-pointer"
                            :text="readAllTitle"
                            @click="readAll()"
                            v-if="notifications.length > 0"
                        >
                        </a>
                    </div>
                </x-slot:content>
            </x-admin::dropdown>
        </div>
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