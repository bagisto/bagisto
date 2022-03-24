<template>
    <div
        class="col-lg-3 col-md-12 popular-category-wrapper"
        v-if="popularCategoryDetails"
    >
        <div class="card col-12 no-padding">
            <div class="category-image">
                <img
                    :data-src="popularCategoryDetails.image_url"
                    class="lazyload"
                    alt=""
                />
            </div>

            <div class="card-description">
                <h3 class="fs20">{{ popularCategoryDetails.name }}</h3>

                <ul class="font-clr pl30">
                    <li
                        :key="index"
                        v-for="(
                            subCategory, index
                        ) in popularCategoryDetails.children"
                    >
                        <a
                            :href="`${slug}/${subCategory.slug}`"
                            class="remove-decoration normal-text"
                        >
                            {{ subCategory.name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['slug'],

    data: function () {
        return {
            popularCategoryDetails: null,
        };
    },

    mounted: function () {
        this.getPopularCategories();
    },

    methods: {
        getPopularCategories: function () {
            this.$http
                .get(`${this.baseUrl}/fancy-category-details/${this.slug}`)
                .then((response) => {
                    if (response.data.status) {
                        this.popularCategoryDetails =
                            response.data.categoryDetails;
                    }
                })
                .catch((error) => {
                    console.log('Something went wrong!');
                });
        },
    },
};
</script>
