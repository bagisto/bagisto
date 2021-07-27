<template>
    <div class="container-fluid hot-categories-container">
        <card-list-header :heading="heading"></card-list-header>

        <div class="row">
            <hot-category
                v-for="(category, index) in categories"
                :key="index"
                :slug="category"
            ></hot-category>
        </div>
    </div>
</template>

<script>
export default {
    props: ['heading', 'categories']
};
</script>
