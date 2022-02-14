<template>
    <a
        :title="`${addTooltip}`"
        class="unset compare-icon text-right"
        @click="addProductToCompare">
        <i class="material-icons">compare_arrows</i>
    </a>
</template>
<script>
    export default {
        props: ['slug','customer','productId','addTooltip'],
        data: function () {
            return {
                'deviceTokenNumber': null,
            }
        },
        methods: {
            addProductToCompare: function () {
                if (this.customer == "true" || this.customer == true) {
                    this.$http.put(
                        `${this.$root.baseUrl}/comparison`, {
                            productId: this.productId,
                        }
                    ).then(response => {
                        this.$root.headerItemsCount++;
                        
                        window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                    }).catch(error => {
                        window.showAlert(
                            'alert-danger',
                            this.__('shop.general.alert.error'),
                            this.__('error.something_went_wrong')
                        );
                    });
                } else {
                    //save compare list for the guest users.
                    this.$http.put(
                        `${this.$root.baseUrl}/guest-comparison`, {
                            productId: this.productId,
                            device_token: this.deviceTokenNumber,
                        }
                    ).then(response => {
                        this.$root.headerItemsCount++;
                        window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                    }).catch(error => {
                        window.showAlert(
                            'alert-danger',
                            this.__('shop.general.alert.error'),
                            this.__('error.something_went_wrong')
                        );
                    });
                }

                this.$root.headerItemsCount++;
            }
        },
        mounted:function(){
            this.deviceTokenNumber = localStorage.getItem("deviceTokenNumber");
            if(this.deviceTokenNumber == null || this.deviceTokenNumber == 'null'){
                let deviceToken = Date.now()+""+Math.floor(Math.random() * 1000000)
                localStorage.setItem("deviceTokenNumber",deviceToken);
            }
        }
    }
</script>