<template>
    <div class="control-group">
        <label
            class="per-page-label"
            for="perPage"
            v-text="translations.itemsPerPage"
        ></label>

        <select
            id="perPage"
            class="control"
            name="perPage"
            v-model="currentSelection"
            v-on:change="paginate"
        >
            <option
                v-for="index in this.perPageCount"
                v-text="index"
                :key="index"
                :value="index"
            ></option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['perPage', 'translations'],

    data() {
        return {
            perPageCount: [10, 20, 30, 40, 50],
            currentSelection: this.perPage
        };
    },

    methods: {
        paginate() {
            this.$emit('onFilter', {
                data: { perPage: this.currentSelection }
            });
        }
    }
};
</script>
