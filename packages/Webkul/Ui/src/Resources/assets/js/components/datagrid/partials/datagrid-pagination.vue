<template>
    <div
        class="pagination shop mt-50"
        v-if="
            typeof paginated !== 'undefined' &&
                paginated &&
                records.last_page > 1
        "
    >
        <a
            v-for="(link, index) in records.links"
            :key="index"
            href="javascript:void(0);"
            :data-page="link.url"
            :class="
                `page-item ${index == 0 ? 'previous' : ''} ${
                    link.active ? 'active' : ''
                } ${index == records.links.length - 1 ? 'next' : ''}`
            "
            @click="changePage(link.url)"
        >
            <i class="icon angle-left-icon" v-if="index == 0"></i>

            <i
                class="icon angle-right-icon"
                v-else-if="index == records.links.length - 1"
            ></i>

            <span v-text="link.label" v-else></span>
        </a>
    </div>
</template>

<script>
export default {
    props: ['paginated', 'records'],

    data() {
        return {};
    },

    methods: {
        changePage(pageLink) {
            this.$emit('onChangePage', { data: { pageLink } });
        }
    }
};
</script>
