<v-notification-list {{ $attributes }}></v-notification-list>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-notification-list-template"
    >
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h1 v-text="title"></h1>
                </div>

                <div class="page-action">
                    <div class="control-group notif-filter">
                        <input 
                            type="text"
                            class="form-control control"
                            placeholder="Search Order"
                            @keyup="applyFilter('search',$event)"
                        >

                        <i class="icon search-icon search-btn"></i>
                    </div>

                    <div class="control-group notif-filter">
                        <select
                            @change="applyFilter('filter',$event)"
                            class="control"
                        >
                            <option 
                                v-for="orderstatus in orderTypeStatus"
                                :value="orderstatus"
                                v-text="orderstatus"
                            >
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="page-content">
                <ul
                    class="notif"
                    v-if="notifications.length" 
                >
                    <li 
                        v-for="notification in notifications"
                        :class="notification.read ? 'read' : ''"
                    >
                        <div>
                            <span hidden v-text="localeCode"></span>localeCode
                        </div>
                        
                        <a :href="`${orderViewUrl + notification.order_id}`">
                            <div
                                class="notif-icon"
                                :class="notification.order.status"
                            >
                                <span :class="ordertype[notification.order.status].icon"></span>
                            </div>

                            <div class="notif-content">
                                #@{{ notification.order.id + ' ' + orderTypeMessages[notification.order.status]}}
                            </div>

                            <div
                                class="notif-content"
                                v-text="notification.order.created_at"
                            >
                            </div>
                        </a>
                    </li>
                </ul>
                {{-- <pagination align="center" :data="pagNotif" @pagination-change-page="getResults">
                    <span slot="prev-nav">&lt;</span>
                    <span slot="next-nav">&gt;</span>
                </pagination> --}}

                <ul
                    class="notif"
                    v-if="! notifications.length"
                >
                    <li v-text="noRecordText"></li>
                </ul>

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
                    pagNotif: {},
                    id: '',
                    status: '',
                    ordertype: {
                        pending : {
                            icon: 'pending-icon',
                            message: 'Order Pending'
                        },
                        processing : {
                            icon: 'processing-icon',
                            message: 'Order Processing'
                        },
                        canceled : {
                            icon: 'canceled-icon',
                            message: 'Order Canceled'
                        },
                        completed : {
                            icon: 'completed-icon',
                            message: 'Order Completed'
                        },
                        closed : {
                            icon: 'closed-icon',
                            message: 'Order Closed'
                        },
                    },
                    orderTypeStatus: JSON.parse(this.orderStatus),
                    orderTypeMessages: JSON.parse(this.orderStatusMessages)
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
                getNotification() {
                    const params = {};

                    if (this.id) {
                        params.id = this.id;
                    }

                    if (this.status) {
                        params.status = this.status;
                    }

                    this.$axios.get(this.url, {
                            params: params
                        })
                        .then ((response) => {
                            console.log(response);
                            this.notifications = [];

                            this.notifications = response.data.search_results.data;

                            this.pagNotif = response.data.search_results;
                        }).catch (error => console.log(error));
                },

                applyFilter(type, $event) {
                    type == 'search' ? this.id = $event.target.value : this.status = $event.target.value;

                    this.getNotification();
                },

                getResults(page  = 1) {
                    axios.get(`${this.url}?page=${page}`)
                        .then(response => {
                            this.notifications = [];

                            this.notifications = response.data.search_results.data;

                            this.pagNotif = response.data.search_results;
                        });
                }
            }
        })
    </script>
@endPushOnce