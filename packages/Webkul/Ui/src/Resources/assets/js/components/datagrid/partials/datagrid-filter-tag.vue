<template>
    <span class="filter-tag" style="text-transform: capitalize">
        <span v-if="filter.column == 'perPage'">perPage</span>

        <span v-else>{{ filter.label }}</span>

        <span class="wrapper" v-if="filter.prettyValue">
            {{ filter.prettyValue }}
            <span
                class="icon cross-icon"
                v-on:click="removeFilter(filter)"
            ></span>
        </span>

        <span class="wrapper" v-else>
            {{ decodeURIComponent(filter.val) }}
            <span
                class="icon cross-icon"
                v-on:click="removeFilter(filter)"
            ></span>
        </span>
    </span>
</template>

<script>
export default {
    props: ['filter'],

    data() {
        return {};
    },

    methods: {
        removeFilter() {
            this.$emit('onRemoveFilter');
        }
    }
};
</script>
