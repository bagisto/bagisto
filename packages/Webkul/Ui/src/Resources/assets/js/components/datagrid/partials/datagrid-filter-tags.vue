<template>
    <div class="filtered-tags">
        <template v-if="filters.length > 0">
            <datagrid-filter-tag
                :key="filterKey"
                :filter="filter"
                :translations="translations"
                v-for="(filter, filterKey) in filters"
                @onRemoveFilter="removeFilter(filter)"
            ></datagrid-filter-tag>
            
            <p @click="removeAllFilters" v-if="filters.length > 0">{{ translations.clearAll }}</p>
        </template>
    </div>
</template>

<script>
import DatagridFilterTag from './datagrid-filter-tag.vue';

export default {
    props: ['filters', 'translations'],

    components: {
        DatagridFilterTag
    },

    data() {
        return {};
    },

    methods: {
        removeFilter(filter) {
            this.$emit('onRemoveFilter', { data: { filter } });
        },

        removeAllFilters() {
            this.$emit('onRemoveAllFilter');
        }
    }
};
</script>
