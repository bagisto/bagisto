<v-notification-list {{ $attributes }}></v-notification-list>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-notification-list-template"
    >
            <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
                <div class="grid gap-[6px]">
                    <p
                        class="pt-[6px] text-[20px] text-gray-800 font-bold leading-[24px]"
                        v-text="title"
                    >
                    </p>

                    <p class="text-gray-600">List all the Notifications</p>
                </div>

                <span class="icon-settings p-[6px] rounded-[6px] text-[24px]  cursor-pointer transition-all hover:bg-gray-100"></span>
            </div>

            <div class="flex flex-col justify-between max-w-max bg-white border border-gray-300 rounded-[6px] box-shadow h-[calc(100vh-179px)]">
                <div class="">
                    <div class="flex border-b-[1px] border-gray-300 overflow-auto journal-scroll">
                        <div
                            class="flex py-[15px] px-[15px] gap-[4px] border-b-[2px] first:border-blue-600"
                            ref="tabs"
                            v-for="data in orderType"
                        >
                            <p
                                class="text-gray-600 cursor-pointer"
                                v-text="data.message"
                                @click="applyFilter(data.status, $event)"
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
                        class="grid gap-[24px] px-[24px] py-[12px] max-h-[calc(100vh-330px)] overflow-auto journal-scroll"
                        v-if="notifications.length"
                    >
                        <div
                            class="flex gap-[5px] items-start"
                            v-for="notification in notifications"
                            :class="notification.read ? 'opacity-50' : ''"
                        >
                            <a
                                :href="`${orderViewUrl + notification.order_id}`"
                                class="flex gap-[5px]"    
                            >
                                <span
                                    v-if="notification.order.status in orderType"
                                    class="h-fit text-[24px] rounded-full"
                                    :class="orderType[notification.order.status].icon"
                                >
                                </span>

                                <div class="grid">
                                    <p class="text-gray-800">
                                        #@{{ notification.order.id }}
                                        @{{ orderType[notification.order.status].message }}
                                    </p>
        
                                    <p class="text-[12px] text-gray-600">
                                        @{{ notification.order.datetime }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- For Empty Data -->
                    <div
                        class="px-[24px] py-[12px] max-h-[calc(100vh-330px)]"
                        v-else
                        v-text="noRecordText"
                    >
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex gap-x-[8px] items-center p-[24px] border-t-[1px] border-gray-300">
                    <div
                        class="inline-flex gap-x-[4px] items-center justify-between ml-[8px] text-gray-600 py-[6px] px-[8px] leading-[24px] text-center w-full max-w-max bg-white border border-gray-300 rounded-[6px] marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400 max-sm:hidden" v-text="pagination.per_page"
                    >
                    </div>

                    <span class="text-gray-600 whitespace-nowrap">of</span>

                    <p
                        class="text-gray-600 whitespace-nowrap"
                        v-text="pagination.current_page"
                    >
                    </p>

                    <!-- Prev & Next Page Button -->
                    <div class="flex gap-[4px] items-center">
                        <a @click="getResults()">
                            <div class="inline-flex gap-x-[4px] items-center justify-between ml-[8px] text-gray-600 p-[6px] text-center w-full max-w-max bg-white border rounded-[6px] border-gray-300 cursor-pointer transition-all hover:border hover:bg-gray-100 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                                <span class="icon-sort-left text-[24px]"></span>
                            </div>
                        </a>

                        <a @click="getResults(pagination.last_page)">
                            <div
                                class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all active:border-gray-300 hover:bg-gray-100 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
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

            props: [
                'url',
                'orderViewUrl',
                'pusherKey',
                'pusherCluster',
                'title',
                'orderStatus',
                'noRecordText',
                'orderStatusMessages',
                'localeCode'
            ],

            data() {
                return {
                    notifications: [],
                    pagination: {},
                    id: '',
                    status: '',
                    orderType: {
                        all : {
                            icon: 'icon',
                            message: 'All',
                            status: ''
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

                // if (Echo) {
                //     Echo.channel('notification').listen('.create-notification', (e) => {
                //         this.getNotification();
                //     });

                //     Echo.channel('notification').listen('.update-notification', (e) => {
                //         this.notifications.forEach((notification)=>{
                //             if(notification.order_id == e.id){
                //                 notification.order.status = e.status;
                //             }
                //         });
                //     });
                // }
            },

            methods: {
                getNotification($event) {
                    const params = {};

                    this.id ? params.id = this.id : '';

                    this.status ? params.status = this.status : '';

                    this.$axios.get(this.url, {
                        params: params
                    })
                    .then((response) => {
                        this.notifications = response.data.search_results.data;

                        for (let item of response.data.status_count) {
                            if (this.orderType[item.status]) {
                                this.orderType[item.status].status_count = item.status_count;
                            }
                        }

                        this.orderType['all'] = {
                            icon :  "icon",
                            message: "All",
                            status_count: response.data.status_count.reduce((sum, item) => sum + item.status_count, 0)
                        }

                        this.pagination = response.data.search_results;
                    })
                    .catch(error => console.log(error));
                },

                applyFilter(status, $event) {
                    let elements = $event.target.parentElement.parentElement.children;

                    for (let element of elements) {
                        if (element.classList.contains('border-blue-600') || element.classList.contains('first:border-blue-600')) {
                            element.classList.remove('border-blue-600', 'first:border-blue-600');
                        }
                    }

                    $event.target.parentElement.classList.add('border-blue-600')

                    this.status = status;

                    this.getNotification();
                },

                getResults(page = 1) {
                    axios.get(`${this.url}?page=${page}`)
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