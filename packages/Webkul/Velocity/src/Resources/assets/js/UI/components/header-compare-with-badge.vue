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
    props: ['isCustomer', 'isText', 'src','compareItemCount'],

    data: function() {
        return {
            compareCount: 0
        };
    },

    watch: {
        'compareItemCount': function() {
            this.compareCount = this.compareItemCount;
        }
    },

    created: function() {
        setTimeout(function() {
            this.compareCount = this.compareItemCount;
        }.bind(this), 200);
    },
};
</script>
