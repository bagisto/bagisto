<template>   
    <div class="notifications">                
        <div class="dropdown-toggle">
            <span class="notification-badge" v-if="totalUnRead">{{ totalUnRead }}</span>
            <i class="icon notification-icon active" style="margin-left:0px"></i>
        </div>
        <div class="dropdown-list bottom-right notification">
            <div class="dropdown-container">
                <ul class="notif">
                    <div id="notif-title">{{ title }}</div>
                    <li v-for="(notification,index) in notifications" :key="notification.id" :class="notification.read ? 'read': ' '">
                         <template v-if="notification.order.status == 'pending'">
                            <div class="notif-icon pending">
                                <span :class="ordertype.pending.icon"></span>
                            </div>                
                            <div class="notif-content">
                                <a :href="`${orderViewUrl}${notification.order_id}`">
                                    #{{ notification.order.id }} {{orderTypeMessages.pending}}
                                </a>  
                            </div>
                            <div class="notif-content">
                                {{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </template>
                        <template v-if="notification.order.status == 'canceled'">
                            <div class="notif-icon canceled">
                                <span :class="ordertype.canceled.icon"></span>
                            </div>                
                            <div class="notif-content">
                                <a :href="`${orderViewUrl}${notification.order_id}`">
                                    #{{ notification.order.id }} {{orderTypeMessages.canceled}}
                                </a> 
                            </div>
                            <div class="notif-content">
                                {{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </template>
                        <template v-if="notification.order.status == 'completed'">
                            <div class="notif-icon completed">
                                <span :class="ordertype.completed.icon"></span>
                            </div>                
                            <div class="notif-content">
                                <a :href="`${orderViewUrl}${notification.order_id}`">
                                    #{{ notification.order.id }} {{orderTypeMessages.completed}}
                                </a>
                            </div>
                            <div class="notif-content">
                                {{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </template>
                        <template v-if="notification.order.status == 'processing'">
                            <div class="notif-icon processing">
                                <span :class="ordertype.processing.icon"></span>
                            </div>                   
                            <div class="notif-content">
                                <a :href="`${orderViewUrl}${notification.order_id}`">
                                    #{{ notification.order.id }} {{orderTypeMessages.processing}}
                                </a>
                            </div>
                            <div class="notif-content">
                                {{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </template>
                        <template v-if="notification.order.status == 'closed'">
                            <div class="notif-icon closed">
                                <span :class="ordertype.closed.icon"></span>
                            </div>                
                            <div class="notif-content">
                                <a :href="`${orderViewUrl}${notification.order_id}`">
                                    #{{ notification.order.id }} {{orderTypeMessages.closed}}
                                </a>
                            </div>
                            <div class="notif-content">
                                {{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </template>
                    </li>
                    <li class="bottom-li">
                        <a :href="viewAll">{{ viewAllTitle }}</a>
                        <span @click="readAll()" class="read-all">{{ readAllTitle }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

export default {  

    props: [
        'getNotificationUrl',
        'viewAll',
        'orderViewUrl',
        'pusherKey',
        'pusherCluster',
        'title',
        'viewAllTitle',
        'getReadAllUrl',
        'readAllTitle',
        'orderStatusMessages'   
    ],

    data(){

        return {
            notifications: [],
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
                }
            },  
            totalUnRead: 0,
            orderTypeMessages:JSON.parse(this.orderStatusMessages) 
        }
    },

    mounted(){
        this.getNotification();

        if(this.pusherKey != undefined && this.pusherCluster != undefined){
            Echo = new Echo({
                broadcaster: 'pusher',
                key: this.pusherKey,
                cluster: this.pusherCluster,
                encrypted: true
            });

            Echo.channel('notification').listen('.create-notification', (e) => {
                this.getNotification();
            });
        
            Echo.channel('notification').listen('.update-notification', (e) => {
                this.notifications.forEach((notification)=>{
                    if(notification.order_id == e.id){
                        notification.order.status = e.status;
                    }
                });
            });
        }          
    },

    methods: {
        getNotification: function(){

            const params = {
                limit: 5,
                read: 0
            };

            let this_this = this;

            this.$http.get (this.getNotificationUrl, {params: params})
                .then (function(response) {
                    this_this.notifications  = response.data.search_results.data;
                    this_this.totalUnRead = response.data.total_unread;
                })
                .catch (function (error) {})
        },
        readAll: function(){

            let this_this = this;

            this.$http.post(this.getReadAllUrl)
                .then (function(response) {
                    this_this.notifications  = response.data.search_results.data;
                    
                    this_this.totalUnRead = response.data.total_unread;

                    window.flashMessages.push({'type': 'alert-success', 'message': response.data.success_message });

                    this_this.$root.addFlashMessages();
                })
                .catch (function (error) {})
        }
    }
}
</script>