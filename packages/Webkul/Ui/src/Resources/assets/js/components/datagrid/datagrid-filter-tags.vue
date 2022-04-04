<template>
    <div class="filtered-tags">
        <template v-if="filters.length > 0">
            <datagrid-filter-tag
                :key="filterKey"
                :filter="filter"
                v-for="(filter, filterKey) in filters"
                @onRemoveFilter="removeFilter(filter)"
            ></datagrid-filter-tag>
        </template>
    </div>
</template>

<script>
import DatagridFilterTag from './datagrid-filter-tag.vue';

export default {
    props: ['filters'],

    components: {
        DatagridFilterTag
    },

    data() {
        return {};
    },

    methods: {
        removeFilter(filter) {
            this.$emit('onRemoveFilter', { data: { filter } });
        }
    }
};
</script>
