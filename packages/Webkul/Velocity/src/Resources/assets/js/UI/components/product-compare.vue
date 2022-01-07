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
        props: ['slug', 'customer', 'productId', 'addTooltip'],

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
                    let updatedItems = [this.productId];
                    let existingItems = this.getStorageValue('compared_product');

                    if (existingItems) {
                        if (existingItems.indexOf(this.productId) == -1) {
                            updatedItems = existingItems.concat(updatedItems);

                            this.setStorageValue('compared_product', updatedItems);

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
                        this.setStorageValue('compared_product', updatedItems);

                        window.showAlert(
                            `alert-success`,
                            this.__('shop.general.alert.success'),
                            `${this.__('customer.compare.added')}`
                        );
                    }
                }

                this.$root.headerItemsCount++;
            }
        }
    }
</script>