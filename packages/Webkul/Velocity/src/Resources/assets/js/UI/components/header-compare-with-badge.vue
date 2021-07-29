<template>
    <a class="compare-btn unset" :href="src">
        <i class="material-icons">compare_arrows</i>

        <div class="badge-container" v-if="compareCount > 0">
            <span class="badge" v-text="compareCount"></span>
        </div>

        <span v-text="__('customer.compare.text')" v-if="isText == 'true'"></span>
    </a>
</template>

<script type="text/javascript">
export default {
    props: ['isCustomer', 'isText', 'src'],

    data: function() {
        return {
            compareCount: 0
        };
    },

    watch: {
        '$root.headerItemsCount': function() {
            this.updateHeaderItemsCount();
        }
    },

    created: function() {
        this.updateHeaderItemsCount();
    },

    methods: {
        updateHeaderItemsCount: function() {
            if (this.isCustomer !== 'true') {
                let comparedItems = this.getStorageValue('compared_product');

                if (comparedItems) {
                    this.compareCount = comparedItems.length;
                }
            } else {
                this.$http
                    .get(`${this.$root.baseUrl}/items-count`)
                    .then(response => {
                        this.compareCount = response.data.compareProductsCount;
                    })
                    .catch(exception => {
                        console.log(this.__('error.something_went_wrong'));
                    });
            }
        }
    }
};
</script>
