<template>
    <span class="filter-tag">
        <span v-if="filter.column == 'perPage'">perPage</span>

        <span v-else>{{ filter.label }} | {{ filter.cond }}</span>

        <span class="wrapper" v-if="filter.prettyValue">
            {{ filter.prettyValue }} 
            <span
                class="icon cross-icon"
                v-on:click="removeFilter(filter)"
            ></span>
        </span>

        <span class="wrapper" v-else>
            <span v-if="filter.type == 'boolean'">
                {{
                    filter.val == 1
                        ? translations.active
                        : translations.inactive
                }} 
            </span>

            <span v-else>
                {{ decodeURIComponent(filter.val) }}
            </span>

            <span
                class="icon cross-icon"
                v-on:click="removeFilter(filter)"
            ></span>
        </span>
    </span>
</template>

<script>
export default {
    props: ['filter', 'translations'],

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
