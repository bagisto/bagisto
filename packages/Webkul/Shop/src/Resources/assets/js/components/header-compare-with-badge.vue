<template>
<li class="compare-dropdown-container">
    <a :href="src" @endauth style="color: #242424;">

        <i class="icon wishlist-icon"></i>
        <span class="name">
            {{ text }}
            <span class="count">(<span>{{ compareCount ? compareCount : 0 }}</span>)</span>
        </span>
    </a>
</li>
</template>

<script>
export default {
    props: ['isCustomer', 'text', 'src'],

    data: function () {
        return {
            compareCount: 0
        };
    },

    created: function () {
        this.updateHeaderItemsCount();
    },

    methods: {
        updateHeaderItemsCount: function () {

            this.$http
                .get(`${this.$root.baseUrl}/items-count`)
                .then(response => {
                    
                    this.compareCount = response.data.wishlistedProductsCount;
                });
        }
    }
};
</script>
