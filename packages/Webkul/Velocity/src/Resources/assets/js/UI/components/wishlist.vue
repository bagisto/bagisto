<template>
    <i
        v-if="isCustomer == 'true'"
        :class="`material-icons ${addClass ? addClass : ''}`"
        @mouseover="isActive ? isActive = !isActive : ''"
        @mouseout="active !== '' && !isActive ? isActive = !isActive : ''">

        {{ isActive ? 'favorite_border' : 'favorite' }}
    </i>

    <a
        v-else
        @click="addProductWishlist(productId)"
        :href="`${$root.baseUrl}/customer/login`"
        :class="`unset wishlist-icon ${addClass ? addClass : ''} text-right`">
        <i
            :class="`material-icons ${addClass ? addClass : ''}`"
            @mouseover="isActive ? isActive = !isActive : ''"
            @mouseout="active !== '' && !isActive ? isActive = !isActive : ''">

            {{ isActive ? 'favorite_border' : 'favorite' }}
        </i>
    </a>
</template>

<script type="text/javascript">
    export default {
        props: ['active', 'addClass', 'isCustomer', 'productId'],

        data: function () {
            return {
                isActive: this.active,
            }
        },

        methods: {
            addProductWishlist: function () {
            }
        }
    }
</script>