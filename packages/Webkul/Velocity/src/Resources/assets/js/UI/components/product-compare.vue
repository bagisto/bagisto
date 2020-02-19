<template>
    <a class="unset compare-icon text-right" @click="addProductToCompare">
        <i class="material-icons">compare_arrows</i>
    </a>
</template>

<script>
    export default {
        props: ['slug'],

        data: function () {
            return {}
        },

        methods: {
            addProductToCompare: function () {
                this.$http.put(
                    `${this.$root.baseUrl}/customer/account/compare`, {
                        slug: this.slug,
                    }
                ).then(response => {
                    window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                }).catch(error => {
                    window.showAlert(
                        'alert-danger',
                        this.__('shop.general.alert.error'),
                        this.__('error.something-went-wrong')
                    );
                });
            }
        }
    }
</script>