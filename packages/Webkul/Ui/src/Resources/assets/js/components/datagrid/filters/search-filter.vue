<template>
    <div class="search-filter">
        <input
            type="search"
            id="search-field"
            class="control"
            :placeholder="translations.search"
            v-model="searchValue"
            v-on:keyup.enter="search(searchValue)"
        />

        <div class="icon-wrapper">
            <span
                class="icon search-icon search-btn"
                v-on:click="search(searchValue)"
            ></span>
        </div>
    </div>
</template>

<script>
export default {
    props: ['translations'],

    data() {
        return {
            searchValue: ''
        };
    },

    methods: {
        search(searchValue) {
            this.$emit('onFilter', { data: { searchValue } });
        }
    }
};
</script>
