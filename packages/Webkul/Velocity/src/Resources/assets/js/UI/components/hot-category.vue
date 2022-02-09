<template>
    <div
        class="col-lg-3 col-md-12 hot-category-wrapper"
        v-if="hotCategoryDetails"
    >
        <div class="card">
            <div class="row velocity-divide-page">
                <div class="left">
                    <img :src="hotCategoryDetails.category_icon_url" alt="" />
                </div>

                <div class="right">
                    <h3 class="fs20 clr-light text-uppercase">
                        <a href="${slug}" class="unset">
                            {{ hotCategoryDetails.name }}
                        </a>
                    </h3>

                    <ul type="none">
                        <li
                            :key="index"
                            v-for="(
                                subCategory, index
                            ) in hotCategoryDetails.children"
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
    </div>
</template>

<script>
export default {
    props: ['slug'],

    data: function () {
        return {
            hotCategoryDetails: null,
        };
    },

    mounted: function () {
        this.getHotCategories();
    },

    methods: {
        getHotCategories: function () {
            this.$http
                .get(`${this.baseUrl}/fancy-category-details/${this.slug}`)
                .then((response) => {
                    if (response.data.status)
                        this.hotCategoryDetails = response.data.categoryDetails;
                })
                .catch((error) => {
                    console.log('something went wrong');
                });
        },
    },
};
</script>
