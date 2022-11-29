<template>
    <a class="wishlist-btn unset" :href="src">
        <i class="material-icons">favorite_border</i>

        <div class="badge-container" v-if="wishlistCount > 0">
            <span class="badge" v-text="wishlistCount"></span>
        </div>

        <span v-text="__('header.wishlist')" v-if="isText == 'true'"></span>
    </a>
</template>

<script type="text/javascript">
export default {
    props: ['isCustomer', 'isText', 'src','wishlistItemCount'],

    data: function() {
        return {
            wishlistCount: 0
        };
    },

    created: function() {
        setTimeout(function() {
            this.wishlistCount = this.wishlistItemCount;
        }.bind(this), 200);
    },
};
</script>
