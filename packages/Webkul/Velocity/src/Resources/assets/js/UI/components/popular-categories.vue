<template>
    <div class="container-fluid popular-categories-container">
        <card-list-header :heading="heading"></card-list-header>

        <div class="row">
            <popular-category
                v-for="(category, index) in categories"
                :key="index"
                :slug="category"
            ></popular-category>
        </div>
    </div>
</template>

<script>
export default {
    props: ['heading', 'categories']
};
</script>
