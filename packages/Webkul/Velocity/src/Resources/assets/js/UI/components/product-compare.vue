<template>
    <a class="unset compare-icon text-right" @click="addProductToCompare">
        <i class="material-icons">compare_arrows</i>
    </a>
</template>

<script>
    export default {
        props: ['slug', 'customer', 'productId'],

        methods: {
            addProductToCompare: function () {
                if (this.customer == "true") {
                    this.$http.put(
                        `${this.$root.baseUrl}/comparison`, {
                            productId: this.productId,
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
                } else {
                    let updatedItems = [this.slug];
                    let existingItems = window.localStorage.getItem('compared_product');

                    if (existingItems) {
                        existingItems = JSON.parse(existingItems);

                        if (existingItems.indexOf(this.slug) == -1) {
                            updatedItems = existingItems.concat([this.slug]);

                            window.localStorage.setItem('compared_product', JSON.stringify(updatedItems));

                            window.showAlert(
                                `alert-success`,
                                this.__('shop.general.alert.success'),
                                `${this.__('customer.compare.added')}`
                            );
                        } else {
                            window.showAlert(
                                `alert-success`,
                                this.__('shop.general.alert.success'),
                                `${this.__('customer.compare.already_added')}`
                            );
                        }
                    } else {
                        window.localStorage.setItem('compared_product', JSON.stringify([this.slug]));

                        window.showAlert(
                            `alert-success`,
                            this.__('shop.general.alert.success'),
                            `${this.__('customer.compare.added')}`
                        );
                    }
                }
            }
        }
    }
</script>